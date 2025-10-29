<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'promos';
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['title', 'description', 'percentage', 'start_date', 'end_date', 'status', 'type', 'created_at'];
}
