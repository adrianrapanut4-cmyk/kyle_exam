<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function images()
    {
        return $this->hasMany(TweetImage::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reposts()
    {
        return $this->hasMany(Repost::class);
    }

    public function isRepostedBy($user)
    {
        return $this->reposts()->where('user_id', $user->id)->exists();
    }
}
