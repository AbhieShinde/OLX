<?php
namespace Olx\middleware;

use function DI\string;

class CMiddleware {

    protected $m_objContainer;
    protected $m_objApp;

    public function __construct( $objContainer, $objApp = null ) {

        $this->m_objContainer = $objContainer;
        $this->m_objApp       = $objApp;
    }

    public function __get( $strPropertyName ) {
        return $this->m_objContainer->get( $strPropertyName );
    }

    public function urlFor( $strRouteName ) {
        return $this->m_objContainer->get( 'routeParser' )->urlFor( $strRouteName );
    }
}