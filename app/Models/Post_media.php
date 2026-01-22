<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Post_media extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'path'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
