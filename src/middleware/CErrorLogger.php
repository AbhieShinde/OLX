<?php
namespace Olx\middleware;

class CErrorLogger extends CMiddleware {

    public function __invoke( $objException, $boolIsPhpError = false ) {
        
        if ( true == $boolIsPhpError ) {
            $strLogFilePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'php-error.log';
        } else {
            $strLogFilePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'slim-app-error.log';
        }

        $resLog = fopen( $strLogFilePath, 'a' );

        $objDate = new \DateTime();

        fwrite( $resLog, 'Time: '      . $objDate->format("y:m:d h:i:s")    . "\n" );
        fwrite( $resLog, 'Type: '      . get_class($objException)           . "\n" );
        fwrite( $resLog, 'Code: '      . $objException->getCode()           . "\n" );
        fwrite( $resLog, 'Message: '   . $objException->getMessage()        . "\n" );
        fwrite( $resLog, 'File: '      . $objException->getFile()           . "\n" );
        fwrite( $resLog, 'Line: '      . $objException->getLine()           . "\n" );
        fwrite( $resLog, '---------------------------------------'          . "\n" );

        fclose( $resLog );
    }
}