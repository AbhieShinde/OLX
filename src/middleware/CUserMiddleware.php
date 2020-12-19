<?php
namespace Olx\middleware;

class CUserMiddleware extends CMiddleware {

    public function __invoke( $objRequest, $objRequestHandler ) {
        
        if ( !$this->AuthController->checkUser() ) {
            
            if ( $this->AuthController->checkAdmin() ) {
                
                $this->flash->addMessage( 'error', 'Access Prohibited' );

                return $this->m_objResponse->withHeader( 'Location', $this->urlFor( 'home' ) )->withStatus( 403 );
                
            } else {

                $this->flash->addMessage( 'error', 'Please Sign in first.' );

                return $this->m_objResponse->withHeader( 'Location', $this->urlFor( 'signin' ) )->withStatus( 403 );
            }
        }

        return $objRequestHandler->handle( $objRequest );
    }
}