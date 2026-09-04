<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['job_opening_id', 'name', 'email', 'phone', 'resume_path', 'message'])]
class JobApplication extends Model
{
    protected function casts(): array
    {
        return ['is_read' => 'boolean'];
    }

    public function jobOpening(): BelongsTo
    {
        return $this->belongsTo(JobOpening::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
