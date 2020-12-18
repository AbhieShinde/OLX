<?php
namespace Olx\models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    protected $table = 'users';

    //the columns to be updated must be specified into fillables
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'phone',
        'city',
        'updated_by'
    ];
}