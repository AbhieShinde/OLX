<?php
namespace Olx\middleware;

/**
 * @deprecated
 * @since Slim 4
 */
class CValidationErrorMiddleware extends CMiddleware {

    public function __invoke( $objRequest, $objRequestHandler ) {

        $objResponse = $objRequestHandler->handle( $objRequest );
        
        if ( isset( $_SESSION['errors'] ) ) {
            $this->view->getEnvironment()->addGlobal( 'errors', $_SESSION['errors'] );
        }
        unset($_SESSION['errors']);

        return $objResponse;
    }
}