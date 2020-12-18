<?php
namespace Olx\middleware;

/**
 * @deprecated
 * @since Slim 4
 */
class COldInputMiddleware extends CMiddleware {

    public function __invoke( $objRequest, $objRequestHandler ) {

        $objResponse = $objRequestHandler->handle( $objRequest );

        if ( isset( $_SESSION['old'] ) ) {
            $this->view->getEnvironment()->addGlobal('old', $_SESSION['old'] );
        }
        $_SESSION['old'] = $objRequest->getParsedBody();

        return $objResponse;
    }
}