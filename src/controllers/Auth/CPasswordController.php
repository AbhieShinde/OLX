<?php
namespace Olx\controllers\Auth;

use Olx\controllers\CBaseController;

use Respect\Validation\Validator as v;

use Olx\models\user;

use Olx\classes\CPasswordEncryptor as Encrypt;
use Olx\classes\CForgetPasswordAuthMailer as AuthMail;

class CPasswordController extends CBaseController {

    public function getChangePassword( $req , $res ) {

        return $this->view->render( $res , 'auth/password/change.twig');
    }

    public function postChangePassword( $req , $res ) {
        
        $objValidation = $this->validator->validate( $req , [
            'pass_old' => V::noWhitespace()->notEmpty()->matchesPassword(),
            'pass' => V::noWhitespace()->notEmpty(),
        ]);

        if ($objValidation->failed()) {
            
            return $res->withRedirect($this->m_objContainer->router->pathFor('changepassword'));
        
        }else {

            $strPass = $req->getParam('pass');
            $strEncPass = Encrypt::Encrypt($strPass);
            
            $arrobjQuery = User::find($_SESSION['user'])->update([
                'password' => $strEncPass,
                'updated_by' => $_SESSION['user']
                ]);
            
            $this->flash->addMessage('info', 'Password Updated!');

            return $res->withRedirect($this->m_objContainer->router->pathFor('home'));
        }
    }

    public function getChangePasswordAdmin( $req , $res ) {

        return $this->view->render( $res , 'auth/password/adminchange.twig');
    }

    public function postChangePasswordAdmin( $req , $res ) {
        
        $objValidation = $this->validator->validate( $req , [
            'pass_old' => v::noWhitespace()->notEmpty()->matchesPasswordAdmin(),
            'pass' => v::noWhitespace()->notEmpty(),
        ]);

        if ( $objValidation->failed() ) {
            
            return $res->withRedirect($this->m_objContainer->router->pathFor('changepasswordadmin'));
        
        }else {

            $strPass = $req->getParam('pass');
            $strEncPass = Encrypt::Encrypt($strPass);
            
            $arrobjQuery = User::find($_SESSION['admin'])->update([
                'password' => $strEncPass,
                'updated_by' => $_SESSION['admin']
                ]);
            
            $this->flash->addMessage('info', 'Password Updated!');

            return $res->withRedirect($this->m_objContainer->router->pathFor('home'));
        }
    }

    public function getRecoverPassword( $req , $res ) {

        return $this->view->render( $res , 'auth/password/recover.twig');
    }

    public function postRecoverPassword($req, $res) {

        //TODO JS validation on this page

        $strEmail = $req->getParam('email');
        
        if( User::where('email', '=', $strEmail)->select('id')->first() !== NULL )    {

            $strHash = Encrypt::Encrypt( bin2hex(random_bytes(5)) );

            $arrobjQuery = User::where('email', '=', $strEmail )->update([
                'hash' => $strHash
               ]);

            AuthMail::sendMail($strEmail, $strHash);
        
            $this->flash->addMessage('info', 'Reset password instructions are sent to your email address.');

            return $res->withRedirect($this->m_objContainer->router->pathFor('home'));

        }else {
        
            $this->flash->addMessage('error', 'No such User Found !!');

            return $res->withRedirect($this->m_objContainer->router->pathFor('recoverpassword'));
        }
    }

    public function getResetPassword( $req , $res ) {

        $strEmail = $req->getQueryParam('email');
        $strHash = $req->getQueryParam('hash');

        $strDbHash= User::where('email', '=', $strEmail)->select('hash')->first()->hash;

        if ($strHash === $strDbHash ) {

            return $this->view->render($res, 'auth/password/reset.twig', [
                'email' => $strEmail
            ]);

        }else {

            $this->flash->addMessage('error', 'Access Denied');

            return $res->withRedirect($this->m_objContainer->router->pathFor('home'));
        }
    }

    public function postResetPassword( $req, $res ) {

        //TODO JS validation on this page

        $strEmail = $req->getParam('email');
        $strPass = $req->getParam('password');

        $strEncPass = Encrypt::Encrypt($strPass);
            
        $arrobjQuery= User::where('email', '=', $strEmail)->select('id')->get();
        $id = $arrobjQuery->first()->id;

        $arrobjQuery = User::where('email','=',$strEmail)->update([
            'password' => $strEncPass,
            'updated_by' => $id,
            'hash' => NULL
            ]);
        
        $this->flash->addMessage('info', 'Password Updated!');

        return $res->withRedirect($this->m_objContainer->router->pathFor('home'));
    }
}