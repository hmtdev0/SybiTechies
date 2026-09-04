<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption',
    'from_address', 'from_name', 'email_enabled', 'autoreply_enabled',
    'autoreply_subject', 'autoreply_body',
])]
class EmailSetting extends Model
{
    protected function casts(): array
    {
        return [
            'smtp_password' => 'encrypted',
            'email_enabled' => 'boolean',
            'autoreply_enabled' => 'boolean',
        ];
    }

    /**
     * This table only ever holds a single row. Always use this accessor
     * instead of querying the model directly, so callers never have to
     * worry about the row not existing yet.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate(['id' => 1]);
    }

    /**
     * Whether SMTP is configured and switched on at all — the shared
     * precondition for any outgoing mail (auto-reply, admin replies, ...).
     */
    public function canSendMail(): bool
    {
        return $this->email_enabled && filled($this->smtp_host);
    }

    public function canSendAutoReply(): bool
    {
        return $this->canSendMail() && $this->autoreply_enabled;
    }
}
