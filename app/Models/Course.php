<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // Menentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'name',
        'desc',
        'user_id',
        'media_full_access',
        'level',
        'audio_book',
        'lifetime_access',
        'certificate',
        'image',
    ];

    // Menentukan relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
