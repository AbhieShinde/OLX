<?php
namespace Olx\classes;

    class CStoreUserData {

        function createUserFile ( $strName , $strEmail , $strPass , $intPhone, $strCity ) {

            $resDir = 'public/data';

            if (!file_exists( $resDir ) && !is_dir( $resDir )) {
                    
                mkdir( $resDir );
                mkdir("$resDir/$_SESSION[user]");

            }else {

                mkdir("$resDir/$_SESSION[user]");
            }

            //data to be inserted in .txt file
            $strName = 'Hello ' . " $strName " . "!\n";
            $strLabel = "\n1. Your credentials are as follows:-\n";
            $strEmail = "	Email    : ". $strEmail ."\n";
            $strPassword = "	Password : " . $strPass . "\n";
            $strLabel1 = "\n2. Other details :-\n";
            $strPhone = "        Mobile  : $intPhone\n";
            $strCity = "        City    : $strCity";
            
            //creating .txt file in respective folder of a user
            $resTextFile = fopen( "$resDir/$_SESSION[user]/" . "Credentials.txt", "w");
            fwrite( $resTextFile , $strName );
            fwrite( $resTextFile , $strLabel );
            fwrite( $resTextFile , $strEmail );
            fwrite( $resTextFile , $strPassword );
            fwrite( $resTextFile , $strLabel1 );
            fwrite( $resTextFile , $strPhone );
            fwrite( $resTextFile , $strCity );
            fclose( $resTextFile );
        }
    }