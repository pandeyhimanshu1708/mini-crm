<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'noteable_id',
        'noteable_type',
        'user_id',
    ];

    /**
     * Get the parent noteable model (company or employee).
     */
    public function noteable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
