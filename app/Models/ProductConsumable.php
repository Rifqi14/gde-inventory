<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;
class ProductConsumable extends Model
{
    protected $guarded = [];    
    use AutoNumberTrait;

    public function getAutoNumberOptions()
    {
        $code  = 'CSM';
        $year  = date('Y');
        
        return [
            'key_number' => [
                'format' => "$code-$year-?",
                'length' => 6
            ]
        ];
    }

    public function products()
    {
        return $this->hasMany('App\Models\ProductConsumableDetail','product_consumable_id');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\ProductConsumableDocument','product_consumable_id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\ProductConsumableDocument','product_consumable_id')->where('type','file');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ProductConsumableDocument','product_consumable_id')->where('type','photo');
    }
}