<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuscriberTeam extends Model
{
    use HasFactory;
    protected $table = 'suscriber_team';
    protected $fillable = ['user_id','email','who', 'nbr_jours','valide'];
  

}
