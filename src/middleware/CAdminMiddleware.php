<?php
namespace Olx\middleware;

class CAdminMiddleware extends CMiddleware {

    public function __invoke( $objRequest, $objRequestHandler ) {

        $objResponse = $objRequestHandler->handle( $objRequest );
        
        if ( !$this->AuthController->checkAdmin() ) {
            
            $this->flash->addMessage('error', 'Access Prohibited');

            return $objResponse->withHeader( 'Location', $this->urlFor( 'home' ) )->withStatus( 403 );
        }

        return $objResponse;
    }
}