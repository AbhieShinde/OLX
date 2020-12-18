<?php
namespace Olx\middleware;

/**
 * @deprecated
 * @since Slim 4
 */
class CCsrfViewMiddleware extends CMiddleware {

    public function __invoke( $objRequest, $objRequestHandler ) {

        $objResponse = $objRequestHandler->handle( $objRequest );
        
        $this->view->getEnvironment()->addGlobal('csrf', [
            'field' => '
                <input type="hidden" name="' . $this->csrf->getTokenNameKey() . '" value="' . $this->csrf->getTokenName() . '">
                <input type="hidden" name="' . $this->csrf->getTokenValueKey() . '" value="' . $this->csrf->getTokenValue() . '">
            ',
        ]);

        return $objResponse;
    }
}