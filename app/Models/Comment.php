<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Add 'post_id' to the fillable array
    protected $fillable = ['user_id', 'post_id', 'parent_id','content'];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class, 'comment_id');
    }
    public function islikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
