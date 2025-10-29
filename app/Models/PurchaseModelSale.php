<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PurchaseModelSale extends Model
{


    protected $table = 'purchase_model_sales';
    public $timestamps = false;
    protected $dateFormat = 'U';

    public $fillable = ['id', 'purchase_model_id', 'buyer_id', 'created_at'];

}
