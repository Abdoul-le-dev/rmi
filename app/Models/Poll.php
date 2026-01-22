<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PollOption;

class Poll extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'question'];

    public function options() {
        return $this->hasMany(PollOption::class);
    }
}
