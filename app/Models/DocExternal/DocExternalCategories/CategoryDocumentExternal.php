<?php

namespace App\Models\DocExternal\DocExternalCategories;

use Illuminate\Database\Eloquent\Model;

class CategoryDocumentExternal extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Method to define relation one to many with menus table
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function menu()
    {
        return $this->belongsTo('App\Models\Menu', 'menu_id', 'id');
    }

    /**
     * Method to define relation one to many with document_external_document_types table
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function disciplinecode()
    {
        return $this->belongsTo('App\Models\DocExternal\Properties\DocumentExternalDisciplineCode', 'discipline_code_id', 'id');
    }
}
