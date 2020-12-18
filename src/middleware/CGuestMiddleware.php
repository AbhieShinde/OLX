<?php
namespace Olx\middleware;

class CGuestMiddleware extends CMiddleware {

    public function __invoke( $objRequest, $objRequestHandler ) {

        $objResponse = $objRequestHandler->handle( $objRequest );

        if ( $this->AuthController->check() ) {
            
            $this->flash->addMessage( 'error', 'You\'re already Signed in.' );

            return $objResponse->withStatus( 403 )->withHeader( 'Location', $this->urlFor( 'home' ) );
        }

        return $objResponse;
    }
}