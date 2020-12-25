<?php
namespace Olx\middleware;

class CAdminMiddleware extends CMiddleware {

    public function __invoke( $objRequest, $objRequestHandler ) {
        
        if ( !$this->AuthController->checkAdmin() ) {
            
            $this->flash->addMessage('error', 'Access Prohibited');

            return $this->m_objResponse->withHeader( 'Location', $this->urlFor( 'home' ) )->withStatus( 403 );
        }

        return $objRequestHandler->handle( $objRequest );
    }
}