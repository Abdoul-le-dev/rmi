<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Models\Post_media;
use App\Models\Poll;

class Post extends Model
{
    use HasFactory;
    

    protected $fillable = ['user_id', 'forum_id','content', 'type', 'scheduled_at', 'status'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function channel() {
        return $this->belongsTo(Forum::class);
    }

    public function media() {
        return $this->hasMany(Post_media::class);
    }

    public function poll() {
        return $this->hasOne(Poll::class);
    }
}
