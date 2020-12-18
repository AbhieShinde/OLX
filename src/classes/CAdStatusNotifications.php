<?php
namespace Olx\classes;

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

class CAdStatusNotifications {

  function approveMail ( $strTitle , $strEmail )  {

    $strUsername = getenv('email');
    $strPassword = getenv('password');

    $objTransport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'TLS'))
          ->setUsername( $strUsername )
          ->setPassword( $strPassword );

    $objMailer = new Swift_Mailer($objTransport);

    $objMessage = (new Swift_Message('Product Published'))
            ->setFrom(['admin@xentoolx.in' => 'AbhieShinde'])
            ->setTo([ $strEmail ])
            ->setBody('Congratulations, your product \'' . $strTitle . '\' is now approved and published into Xento Marketplace.');

    $objMailer->send($objMessage);
  }

  function rejectMail ( $strTitle , $strEmail )  {

    $strUsername = getenv('email');
    $strPassword = getenv('password');

    $objTransport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'TLS'))
          ->setUsername( $strUsername )
          ->setPassword( $strPassword );

    $objMailer = new Swift_Mailer($objTransport);

    $objMessage = (new Swift_Message('Product Rejected'))
            ->setFrom(['admin@xentoolx.in' => 'AbhieShinde'])
            ->setTo([ $strEmail ])
            ->setBody('Oops, your product \'' . $strTitle . '\' is rejected and cannot be in Xento Marketplace right now.');

    $objMailer->send($objMessage);
  }
}