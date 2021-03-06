<?php
namespace Olx\models;

use Illuminate\Database\Eloquent\Model;

class CAdvertisements extends Model {

    protected $table = 'advertisements';

    //the columns to be updated must be specified into fillables
    protected $fillable = [
        'title',
        'product_category_id',
        'price',
        'description',
        'amount',
        'purchased_date',
        'updated_by',
        'created_by'
    ];

    public function photos()    {
        return $this->hasMany( CMedia::class, 'advertisement_id', 'id' )->select( [ 'file_path', 'advertisement_id' ] );
        // the foreign key (advertisement_id) must be selected above so that Laravel knows how to link the models together when building the relationships.
    }

    public function comments()  {
        return $this->hasMany( CAdvertisementComments::class, 'advertisement_id', 'id' );
    }

    public function owner() {
        return $this->belongsTo( CUsers::class, 'created_by', 'id' )->select( 'id', 'name', 'email', 'phone', 'city' );
    }

    public function category()  {
        return $this->belongsTo( CProductCategories::class, 'product_category_id', 'id' )->select( 'id', 'name' );
    }
}