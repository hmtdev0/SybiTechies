<?php

namespace App\Models;

use App\Mail\AdminResetPasswordMail;
use App\Mail\AdminVerifyEmailMail;
use App\Services\MailConfigurator;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'role', 'avatar', 'is_active', 'last_login_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * @deprecated Superseded by Spatie roles/permissions — kept only so the
     * legacy `role` column remains readable. Do not use for authorization.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Whether this account has any admin-panel role at all (Super Admin,
     * Admin, Editor, or Viewer). This is the panel-entry gate — fine-grained
     * access within the panel is decided per-permission, not per-role.
     */
    public function hasAdminPanelAccess(): bool
    {
        return $this->roles()->exists();
    }

    /**
     * Routes password-reset delivery through the admin-configured dynamic
     * SMTP settings instead of Laravel's default mail config, mirroring
     * how ContactReplyMail/ContactAutoReplyMail already send.
     */
    public function sendPasswordResetNotification($token): void
    {
        $emailSettings = EmailSetting::current();

        if (! $emailSettings->canSendMail()) {
            Log::warning('Password reset requested but email sending is currently disabled.', ['user_id' => $this->id]);

            return;
        }

        try {
            MailConfigurator::apply($emailSettings);
            Mail::to($this->email)->send(new AdminResetPasswordMail($this, $token));
        } catch (\Throwable $e) {
            Log::error('Password reset email failed to send.', ['user_id' => $this->id, 'error' => $e->getMessage()]);
        }
    }

    public function sendEmailVerificationNotification(): void
    {
        $emailSettings = EmailSetting::current();

        if (! $emailSettings->canSendMail()) {
            Log::warning('Verification email requested but email sending is currently disabled.', ['user_id' => $this->id]);

            return;
        }

        try {
            MailConfigurator::apply($emailSettings);
            Mail::to($this->email)->send(new AdminVerifyEmailMail($this));
        } catch (\Throwable $e) {
            Log::error('Verification email failed to send.', ['user_id' => $this->id, 'error' => $e->getMessage()]);
        }
    }
}
