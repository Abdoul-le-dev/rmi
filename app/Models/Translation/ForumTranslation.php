<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Model;
use App\Models\Forum;
use App\Models\Post;
class ForumTranslation extends Model
{
    protected $table = 'forum_translations';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    

    public function forum() {
        return $this->belongsTo(Forum::class);
    }

    public function topics() {
        return $this->hasMany(Post::class, 'forum_id');
    }
}
