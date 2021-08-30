<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductTransfer extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    use AutoNumberTrait;    
    use SoftDeletes;

    public function getAutoNumberOptions()
    {
        $code  = 'TRF';
        $year  = date('Y');
        
        return [
            'key_number' => [
                'format' => "$code-$year-?",
                'length' => 6
            ]
        ];
    }

    public function originsites()
    {
        return $this->belongsTo('App\Models\Site','origin_site_id','id');
    }

    public function destinationsites()
    {
        return $this->belongsTo('App\Models\Site','destination_site_id','id');
    }

    public function originwarehouses()
    {
        return $this->belongsTo('App\Models\Warehouse','origin_warehouse_id','id');
    }

    public function destinationwarehouses()
    {
        return $this->belongsTo('App\Models\Warehouse','destination_warehouse_id','id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\ProductTransferDetail','product_transfer_id');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\ProductTransferDocument','product_transfer_id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\ProductTransferDocument','product_transfer_id')->where('type','file');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ProductTransferDocument','product_transfer_id')->where('type','photo');
    }
}