<?php
namespace Olx\middleware;

class CUserMiddleware extends CMiddleware {

    public function __invoke( $objRequest, $objRequestHandler ) {
        
        $objResponse = $objRequestHandler->handle( $objRequest );
        
        if ( !$this->AuthController->checkUser() ) {
            
            if ( $this->AuthController->checkAdmin() ) {
                
                $this->flash->addMessage( 'error', 'Access Prohibited' );

                return $objResponse->withHeader( 'Location', $this->urlFor( 'home' ) )->withStatus( 403 );
                
            } else {

                $this->flash->addMessage( 'error', 'Please Sign in first.' );

                return $objResponse->withHeader( 'Location', $this->urlFor( 'signin' ) )->withStatus( 403 );
            }
        }

        return $objResponse;
    }
}