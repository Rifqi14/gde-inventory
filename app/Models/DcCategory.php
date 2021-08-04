<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DcCategory extends Model
{
    protected $table = "dc_categorys";
    protected $guarded = [];

    public function menu()
    {
        return $this->belongsTo('App\Models\Menu', 'menu_id', 'id');
    }

    public function doctype()
    {
        return $this->belongsTo('App\Models\DocumentType', 'document_type_id', 'id');
    }
}
