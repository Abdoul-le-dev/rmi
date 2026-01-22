<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PollOption;
use App\Models\Post;
class Poll extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'question'];

    public function options() {
        return $this->hasMany(PollOption::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    

    // Calculer le total de votes
    public function getTotalVotesAttribute()
    {
        return $this->options->sum('votes');
    }
}
