<?php
namespace Olx\classes;

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

class CForgetPasswordAuthMailer {

  function sendMail( $strEmail , $strHash) {

    $strUsername = getenv('EMAIL');
    $strPassword = getenv('PASSWORD');

    $objTransport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'TLS'))
      ->setUsername( $strUsername )
      ->setPassword( $strPassword )
    ;

    $objMailer = new Swift_Mailer($objTransport);

    $objMessage = (new Swift_Message('Password Reset Help'))
      ->setFrom(['admin@abhieshindeolx.in' => 'AbhieShinde'])
      ->setTo([ $strEmail ])
      ->setBody('You requested a password reset of your AbhieShinde-OLX account. Please follow this link to reset your password.
                  '. $_SESSION['BASE_URL'] . "/resetpassword?email=$strEmail&hash=$strHash
                  ".' Thank you!!'
                  .'- Team AbhieShinde-OLX.')
      ;

    $objMailer->send($objMessage);

    }
}