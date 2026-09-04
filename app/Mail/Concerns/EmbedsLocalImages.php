<?php

namespace App\Mail\Concerns;

use Illuminate\Support\Str;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

/**
 * Rewrites <img> tags pointing at our own /uploads/ files into inline CID
 * attachments, so images render inside the recipient's actual mail client.
 *
 * Without this, an <img src="http://your-app.test/uploads/..."> only shows
 * up when the URL is reachable from the *recipient's* network — which is
 * never true on local dev (http://localhost:8000) and unreliable even in
 * production, since many clients block/proxy remote images by default.
 * Embedding the image bytes directly in the email sidesteps both problems.
 */
trait EmbedsLocalImages
{
    protected function embedLocalImages(): void
    {
        $this->withSymfonyMessage(function (Email $message) {
            $html = $message->getHtmlBody();

            if (! is_string($html) || ! str_contains($html, '/uploads/')) {
                return;
            }

            $html = preg_replace_callback(
                '/<img([^>]*)\ssrc=["\']([^"\']*\/uploads\/[^"\']+)["\']/i',
                function (array $matches) use ($message) {
                    $relativePath = 'uploads/'.Str::after($matches[2], '/uploads/');
                    $fullPath = public_path($relativePath);

                    if (! is_file($fullPath)) {
                        return $matches[0];
                    }

                    // Email::embedFromPath() returns the message itself (for
                    // chaining), not the content-id — build the DataPart
                    // directly so we can read its generated cid back out.
                    $part = (new DataPart(new File($fullPath)))->asInline();
                    $message->addPart($part);

                    return '<img'.$matches[1].' src="cid:'.$part->getContentId().'"';
                },
                $html
            );

            $message->html($html);
        });
    }
}
