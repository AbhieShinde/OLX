<?php
namespace Olx\controllers\Auth;

use Olx\controllers\CBaseController;
use Olx\models\user;

use Respect\Validation\Validator;

use Olx\classes\CSignUpNotifications as Notify;
use Olx\classes\CStoreUserData as Store;

class CAuthController extends CBaseController {

    /**
     * Authentication Functions
     */

    public function check() {
        if( isset( $_SESSION['user'] ) || isset( $_SESSION['admin'] ) ) return true;
    }

    public function checkUser() {
        return isset( $_SESSION['user'] );
    }

    public function checkAdmin() {
        return isset( $_SESSION['admin'] );
    }

    public function login( $strEmail, $strPass ) {

        $arrobjQueryResult = User::select('id', 'name', 'email', 'password')
                        ->where([
                            ['email', $strEmail],
                            ['is_admin', false]
                        ])
                        ->get();

        $arrmixUser = json_decode( $arrobjQueryResult, true );

        if ( !validArray( $arrmixUser ) ) return false;

        $strUsername = $arrmixUser[0]['email'];
        $strPassword = $arrmixUser[0]['password'];

        $strPassHash = encrypt( $strPass );

        if ( $strUsername === $strEmail && $strPassHash === $strPassword ) {

            $_SESSION['user'] = $arrmixUser[0]['id'];
            $_SESSION['name'] = $arrmixUser[0]['name'];

            return true;

        } else {

            return false;
        }
    }

    public function adminLogin( $strEmail , $pass )  {

        $arrobjQuery = User::select('id', 'name', 'email', 'password')
                        ->where([
                            ['email', '=', $strEmail],
                            ['is_admin', '=', true]
                        ])
                        ->get();
        
        $arrmixAdmin = json_decode($arrobjQuery, TRUE);

        $strUsername = $arrmixAdmin[0]['email'];
        $strPassword = $arrmixAdmin[0]['password'];
        
        $strPassHash = encrypt($pass);

        if ($strUsername === $strEmail && $strPassHash === $strPassword) {

            $_SESSION['admin'] = $arrmixAdmin[0]['id'];
            $_SESSION['admin_name'] = $arrmixAdmin[0]['name'];

            return true;

        } else {

            return false;
        }
    }

    public function logout() {
        session_destroy();
    }

    /**
     * Route Functions
     */

    public function getSignUp( $objRequest, $objResponse ) {
        return $this->view->render($objResponse, 'auth/signup.twig');
    }

    public function postSignUp( $objRequest, $objResponse ) {

        $arrmixData = $objRequest->getParsedBody();

        $objValidation = $this->validator->validate( $objRequest, [
            "name"  => Validator::notEmpty()->alpha(),
            "email" => Validator::noWhitespace()->notEmpty()->email()->EmailAvailable(),
            "password"  => Validator::noWhitespace()->notEmpty()->length(8, 24),
            "phone" => Validator::phone()->notEmpty()->length(10, 10)->digit(),
            "city"  => Validator::notEmpty(),
        ]);

        if( $objValidation->failed() ) {

            // Adding server side errors from Respect Validation engine
            if ( isset( $_SESSION['errors'] ) ) {
                $this->view->getEnvironment()->addGlobal( 'errors', $_SESSION['errors'] );
                unset( $_SESSION['errors'] );
            }

            // Persisting user inserted form field values
            $this->view->getEnvironment()->addGlobal( 'old', $arrmixData );

            return $this->view->render($objResponse, 'auth/signup.twig');

        } else {

            $strName    = $arrmixData['name'];
            $strEmail   = $arrmixData['email'];
            $strPass    = $arrmixData['password'];
            $intPhone   = $arrmixData['phone'];
            $strCity    = $arrmixData['city'];

            $strEncPass = encrypt( $strPass );
            $intMobile  = '+91' . $intPhone;

            User::create([
                'name'          => $strName,
                'email'         => $strEmail,
                'password'      => $strEncPass,
                'phone'         => $intPhone,
                'city'          => $strCity,
                'updated_by'    => 0
            ] );

            $this->login( $arrmixData['email'], $strPass );

            Store::createUserFile( $strName, $strEmail, $strPass, $intMobile, $strCity );

            Notify::sendMail( $_SESSION['user'], $strName, $strEmail , $strPass );
            // Notify::sendSms( $intMobile, $strName );

            $this->flash->addMessage( 'info', 'Signed up Successfully!' );

            return $objResponse->withHeader( 'Location', $this->urlFor( 'home' ) );
        }
    }

    // Admin SignUp
    public function getSignUpAdmin( $objRequest , $objResponse )   {

        return $this->view->render( $objResponse , 'auth/signupAdmin.twig');
    }

    public function postSignUpAdmin( $objRequest , $objResponse )  {

        $_SESSION['old'] = $objRequest->getParsedBody();

        $objValidation = $this->validator->validate( $objRequest, [
            "name" => Validator::notEmpty()->alpha(),
            "email" => Validator::noWhitespace()->notEmpty()->email()->EmailAvailable(),
            "password" => Validator::noWhitespace()->notEmpty()->length(8, 24),
            "phone" => Validator::phone()->notEmpty()->length(10, 10)->digit(),
            "city" => Validator::notEmpty(),
        ]);

        if( $objValidation->failed() ) {

            if ( isset( $_SESSION['errors'] ) ) {
                $this->view->getEnvironment()->addGlobal( 'errors', $_SESSION['errors'] );
                unset( $_SESSION['errors'] );
            }

            if ( isset( $_SESSION['old'] ) ) {
                $this->view->getEnvironment()->addGlobal( 'old', $_SESSION['old'] );
                unset( $_SESSION['old'] );
            }

            return $this->view->render( $objResponse, 'auth/signupAdmin.twig');

        } else {

            $arrmixData = $objRequest->getParsedBody();

            $strName    = $arrmixData['name'];
            $strEmail   = $arrmixData['email'];
            $strPass    = $arrmixData['password'];
            $intPhone   = $arrmixData['phone'];
            $strCity    = $arrmixData['city'];

            $strEncPass = encrypt($strPass);

            $arrobjQuery = User::create([
                'name' => $strName,
                'email' => $strEmail,
                'password' => $strEncPass,
                'is_admin' => TRUE,
                'phone' => $intPhone,
                'city' => $strCity,
                'updated_by' => 0
            ]);

            //TODO: notify through Mail to New and added_by Admin

            $this->flash->addMessage('info', 'Admin account added Successfully!');
            return $objResponse->withHeader( 'Location', $this->urlFor( 'home' ) );
        }
    }

    public function getSignIn( $objRequest , $objResponse ) {
        return $this->view->render( $objResponse , 'auth/signin.twig');
    }

    public function postSignIn( $objRequest, $objResponse ) {

        $arrmixData = $objRequest->getParsedBody();
        $boolAuth = $this->login(
            $arrmixData['email'],
            $arrmixData['password']
        );

        if ( !$boolAuth ) {
            $this->flash->addMessage('error', 'Wrong Credentials!');
            return $objResponse->withHeader( 'Location', $this->urlFor( 'signin' ) );
        }

        $this->flash->addMessage('info', 'Signed in');
        return $objResponse->withHeader( 'Location', $this->urlFor( 'home' ) );
    }

    public function postAdminSignIn( $objRequest, $objResponse ) {

        $arrmixData = $objRequest->getParsedBody();
        $boolAuth = $this->adminLogin(
            $arrmixData['email'],
            $arrmixData['password']
        );

        if ( !$boolAuth ) {
            $this->flash->addMessage('error', 'Wrong Credentials!');
            return $objResponse->withHeader( 'Location', $this->urlFor( 'signin' ) );
        }
        
        $this->flash->addMessage('info', 'Welcome Admin');
        return $objResponse->withHeader( 'Location', $this->urlFor( 'home' ) );
    }

    public function getSignOut( $objRequest , $objResponse ) {
        $this->logout();
        return $objResponse->withHeader( 'Location', $this->urlFor( 'home' ) );
    }

    public function getSignOutAdmin( $objRequest , $objResponse ) {
        $this->logout();
        return $objResponse->withHeader( 'Location', $this->urlFor( 'home' ) );
    }
}