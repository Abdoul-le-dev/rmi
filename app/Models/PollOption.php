<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Poll;
class PollOption extends Model
{
    use HasFactory;
    protected $fillable = ['poll_id', 'option','votes'];

    protected $casts = [
        'votes' => 'integer'
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    // Calculer le pourcentage
    public function getPercentageAttribute()
    {
        $total = $this->poll->total_votes;
        return $total > 0 ? round(($this->votes / $total) * 100, 1) : 0;
    }
}
