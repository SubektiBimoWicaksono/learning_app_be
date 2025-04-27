<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'dob', 'email', 'no_telp', 'gender', 'role', 'password', 'pin', 'photo', 'skill'
    ];

    protected $hidden = ['password', 'pin', 'remember_token',];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function courseAccesses()
    {
        return $this->hasMany(CourseAccess::class);
    }

    public function videoAccesses()
    {
        return $this->hasMany(VideoAccess::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }



    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
