<?php
namespace Olx\classes;

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

class CCommentNotifications {

  function sendMail( $strName, $strTitle, $strComment, $strEmail )  {

    $strUsername = getenv('EMAIL');
    $strPassword = getenv('PASSWORD');

    $objTransport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'TLS'))
            ->setUsername( $strUsername )
            ->setPassword( $strPassword );

    $objMailer = new Swift_Mailer($objTransport);

    $objMessage = (new Swift_Message('Your product got a Comment!'))
            ->setFrom(['admin@xentoolx.in' => 'AbhieShinde'])
            ->setTo([ $strEmail ])
            ->setBody("$strName commented on your product '$strTitle'!
                        Comment :- $strComment
                        ...");

    $objMailer->send($objMessage);
  }
  
  /**
  * @deprecated
  * @ignore
  * @uses TextLocal API
  * @todo get free text messaging vendor
  */
  function sendSms($strName, $strTitle, $strComment, $intPhone )  {

	  $strUsername = getenv('usernameTxtlcl');
    $strHash = getenv('hashTxtlcl');
    // testing mode
	  $boolTest = "1";
          
	  $strSender = "TXTLCL";
	  $arrintNumbers = "91$intPhone";
	  $strMessage = "$strName commented on your product '$strTitle' :- $strComment .";
          
	  $strMessage = urlencode($strMessage);
	  $strData = "username=".$strUsername."&hash=".$strHash."&message=".$strMessage."&sender=".$strSender."&numbers=".$arrintNumbers."&test=".$boolTest;
    
    $resApi = curl_init('http://api.textlocal.in/send/?');
	  curl_setopt($resApi, CURLOPT_POST, true);
	  curl_setopt($resApi, CURLOPT_POSTFIELDS, $strData);
	  curl_setopt($resApi, CURLOPT_RETURNTRANSFER, true);
    $objResult = curl_exec($resApi);
  }
  
}