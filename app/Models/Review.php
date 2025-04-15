<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['reviews', 'rating', 'datetime', 'course_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
