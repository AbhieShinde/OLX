<?php
namespace Olx\classes;

/**
 * @deprecated
 */
class CPasswordEncryptor {

    public function Encrypt( $strPass ) {

        $strSalt = $_ENV['salt'];
        //Password encryption using SHA-512
        $strEncPass = hash("sha512", $strPass . $strSalt);
        
        return $strEncPass;
    }
}