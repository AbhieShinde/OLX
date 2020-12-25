<?php
namespace Olx\middleware;

class CGuestMiddleware extends CMiddleware {

    public function __invoke( $objRequest, $objRequestHandler ) {

        if ( $this->AuthController->check() ) {
            
            $this->flash->addMessage( 'error', 'You\'re already Signed in.' );

            return $this->m_objResponse->withStatus( 403 )->withHeader( 'Location', $this->urlFor( 'home' ) );
        }

        return $objRequestHandler->handle( $objRequest );
    }
}