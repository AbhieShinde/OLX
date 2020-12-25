<?php
namespace Olx\classes;

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

class CLikeNotifications {

  function sendMail ( $strName , $strTitle , $strEmail )  {

    $strUsername = getenv('EMAIL');
    $strPassword = getenv('PASSWORD');

    $objTransport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'TLS'))
          ->setUsername( $strUsername )
          ->setPassword( $strPassword );

    $objMailer = new Swift_Mailer($objTransport);

    $objMessage = (new Swift_Message('Your product got a Like!'))
            ->setFrom(['admin@abhieshindeolx.in' => 'AbhieShinde'])
            ->setTo([ $strEmail ])
            ->setBody('Congratulations, ' . $strName . ' liked your product \'' . $strTitle . '\'');

    $objMailer->send($objMessage);
  }
}