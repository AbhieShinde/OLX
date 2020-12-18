<?php
namespace Olx\classes;

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

class CForgetPasswordAuthMailer {

  function sendMail( $strEmail , $strHash) {

    $strUsername = getenv('email');
    $strPassword = getenv('password');

    $objTransport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'TLS'))
      ->setUsername( $strUsername )
      ->setPassword( $strPassword )
    ;

    $objMailer = new Swift_Mailer($objTransport);

    $objMessage = (new Swift_Message('Password Reset Help'))
      ->setFrom(['admin@xentoolx.in' => 'AbhieShinde'])
      ->setTo([ $strEmail ])
      ->setBody('You requested a password reset of your Xento-OLX account. Please follow this link to reset your password.
                  '."http://olx.xento.in/resetpassword?email=$strEmail&hash=$strHash
                  ".' Thank you!!'
                  .'- Team Xento-OLX.')
      ;

    $objMailer->send($objMessage);

    }
}