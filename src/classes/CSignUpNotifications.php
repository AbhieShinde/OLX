<?php
namespace Olx\classes;

use Olx\controllers\Tools\CEmailConsumerController;

    class CSignUpNotifications {

        function sendMail( $intId, $strName, $strUser , $strPass )  {

            $strSubject = 'Welcome to Xento-OLX';
            $strBody = '<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <style>
                            body{font-family:Cambria, Cochin, Georgia, Times, Times New Roman, serif}
                            li{list-style-type:none}
                            h4{color:chocolate}
                            ul{color:rgb(104, 50, 11)}
                            p{color:gray}
                        </style>
                    </head>
                    <body>
                        <h4>Congratulations' . " $strName " . '! You\'ve successfully registered.</h4>
                        <ul>
                            <li>Username : ' . " $strUser " . '</li>
                            <li>Password : ' . " $strPass " . '</li>
                        </ul>
                        <p>Your all detailed infomation is within the attachment of this mail.</p>
                    </body>
                    </html>';

            $strAttachmentPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $intId . DIRECTORY_SEPARATOR . 'Credentials.txt';

            $objEmailConsumer = new CEmailConsumerController( $strUser, $strSubject, $strBody, $strAttachmentPath );
            $objEmailConsumer->send();
        }

        /**
        * @deprecated
        * @ignore
        * @uses TextLocal API
        * @todo get free text messaging vendor
        */
        function sendSms( $intMobile , $strName )  {
          
	        $strUsername = getenv('usernameTxtlcl');
          $strHash = getenv('hashTxtlcl');
	        // testing mode
	        $boolTest = "1";
	        
	        $strSender = "TXTLCL";
	        $arrintNumbers = $intMobile;
	        $strMessage = 'Congratulations' . " $strName " . '! You\'ve successfully registered into Xento-OLX. Your infomation is sent to registered email address.';
	        
	        $strMessage = urlencode($strMessage);
	        $strData = "username=".$strUsername."&hash=".$strHash."&message=".$strMessage."&sender=".$strSender."&numbers=".$arrintNumbers."&test=".$boolTest;
          
          $resApi = curl_init('http://api.textlocal.in/send/?');
	        curl_setopt($resApi, CURLOPT_POST, true);
	        curl_setopt($resApi, CURLOPT_POSTFIELDS, $strData);
	        curl_setopt($resApi, CURLOPT_RETURNTRANSFER, true);
	        $objResult = curl_exec($resApi);
        }
        
    }