<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PurchaseModule extends Model
{


    protected $table = 'purchase_models';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $fillable = ['title', 'price', 'actual_price', 'is_popular', 'subscribe_id', 'status', 'id', 'created_at'];

    public function subscription()
    {
        return $this->belongsTo('App\Models\Subscribe', 'subscribe_id', 'id');
    }

    public function purchase_sales()
    {
        return $this->hasMany('App\Models\PurchaseModelSale', 'purchase_model_id', 'id');
    }

}
