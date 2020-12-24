<?php
namespace Olx\Models;

use Illuminate\Database\Eloquent\Model;

class CAdvertisementComments extends Model {

    protected $table = 'advertisement_comments';

    protected $fillable = [
        'advertisement_id',
        'comment_type_id',
        'message',
        'updated_by',
        'created_by'
    ];

    public function by()
    {
        return $this->belongsTo('Olx\models\user', 'created_by', 'id')->select('id','name');
    }
}