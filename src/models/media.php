<?php
namespace Olx\models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model {

    protected $table = 'media';

    protected $fillable = [
        'file_size',
        'file_name',
        'file_path',
        'advertisement_id',
        'product_category_id',
        'created_by'
    ];

    const UPDATED_AT = NULL;

    protected $visible = ['file_path'];
}