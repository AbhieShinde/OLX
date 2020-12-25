<?php
namespace Olx\validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Olx\models\CUsers;
use Olx\classes\CPasswordEncryptor;

class MatchesPasswordAdmin extends AbstractRule   {

    /**
    * Reset Password Form
    * @param Password String
    * Ecnrypting this user entered password
    * @return boolean matching result of these password hashes
    */
    public function validate( $strPassword ) {
        $password = CUsers::select('password')->find($_SESSION['admin'])->password;
        $passhash = encrypt( $strPassword );

        return ( 0 == \strcmp( $passhash, $password ) ) ? true : false;
    }
}