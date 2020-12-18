<?php
namespace Olx\controllers\Notifications;

use Olx\controllers\CBaseController;

class CNotificationsController extends CBaseController {

    public function __construct( $strNotificationType ) {
 
        switch ( $strNotificationType ) {
            case 'sign_up':
                return \Olx\controllers\Notifications\CUserNotificationsController( $strNotificationType );
                break;
        }
    }

    public function sendEmail() {
        
    }
}