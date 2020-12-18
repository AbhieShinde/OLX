<?php
namespace Olx\classes;

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

  class CDislikeNotifications {

    function sendMail( $strName, $strTitle, $strEmail )  {

      $strUsername = getenv('EMAIL');
      $strPassword = getenv('PASSWORD');

      $objTransport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'TLS'))
            ->setUsername( $strUsername )
            ->setPassword( $strPassword );

      $objMailer = new Swift_Mailer($objTransport);

      $objMessage = (new Swift_Message('Oops! your product got a Dislike.'))
            ->setFrom(['admin@xentoolx.in' => 'AbhieShinde'])
            ->setTo([ $strEmail ])
            ->setBody('Your product, ' . "'$strTitle'" . ' has been disliked by ' . $strName . '!');

      $objMailer->send($objMessage);
    }

  }