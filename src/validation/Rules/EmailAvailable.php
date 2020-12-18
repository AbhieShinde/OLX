<?php
namespace Olx\validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Olx\models\user;

class EmailAvailable extends AbstractRule   {

    /**
    * SignUp Form
    * @param EmailAddress String
    * @return boolean if email is present in Users table or not
    */
    public function validate( $strEmailAddress )  {

        $objQueryResult = User::select('id')->from('users')->where('email', $strEmailAddress)->get();
        
        return 0 == count( $objQueryResult );
    }
}