<?php
namespace Olx\models;

use Illuminate\Database\Eloquent\Model;

class CProductCategories extends Model {

    protected $table = 'product_categories';
   
    protected $fillable = [
        'name',
        'updated_by'
    ];    
}