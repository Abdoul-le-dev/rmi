<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Models\Post_media;
use App\Models\Poll;
use App\Models\CommentPost;

class Post extends Model
{
    use HasFactory;
    

    protected $fillable = ['user_id', 'forum_id','content', 'type', 'scheduled_at', 'status'];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'shares_count' => 'integer'
    ];

    protected $appends = ['plaque', 'montant'];
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

    public function comments()
    {
        return $this->hasMany(CommentPost::class);
    }

     public function getPlaqueAttribute()
    {
        $trophe = Trophe::where('user_id', $this->user_id)
            ->where('status', 'validated')
            ->latest()
            ->first();

        if (!$trophe) {
            return 'none';
        }

        $montant = (float) $trophe->montant_généré;

        if ($montant >= 10000) return 'diamond';
        if ($montant >= 5000) return 'gold';
        if ($montant >= 1000) return 'silver';
        if ($montant >= 100) return 'bronze';

        return 'Aucune';
    }

    // 🔥 Accessor pour le montant
    public function getMontantAttribute()
    {
        $trophe = Trophe::where('user_id', $this->user_id)
            ->where('status', 'validated')
            ->latest()
            ->first();

        return $trophe ? (float) $trophe->montant_généré : 0;
    }

    
}
