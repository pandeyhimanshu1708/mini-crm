<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'logo',
        'website',
    ];

    /**
     * Get the employees for the company.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}