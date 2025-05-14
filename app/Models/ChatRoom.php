<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'mentor_id'];

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function mentor() {
        return $this->belongsTo(User::class, 'mentor_id');
    }
    
    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }
}
