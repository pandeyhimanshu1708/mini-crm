<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'path',
        'mime_type',
        'size',
        'attachable_id',
        'attachable_type',
        'user_id',
    ];

    /**
     * Get the parent attachable model (company or employee).
     */
    public function attachable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the attachment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full URL to the attachment.
     */
    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->path);
    }
}
