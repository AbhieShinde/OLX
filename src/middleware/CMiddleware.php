<?php
namespace Olx\middleware;

use Slim\Psr7\Response;

class CMiddleware {

    protected $m_objContainer;
    protected $m_objApp;
    protected $m_objResponse;

    public function __construct( $objContainer, $objApp = null ) {

        $this->m_objContainer   = $objContainer;
        $this->m_objApp         = $objApp;
        $this->m_objResponse    = new Response();
    }

    public function __get( $strPropertyName ) {
        return $this->m_objContainer->get( $strPropertyName );
    }

    public function urlFor( $strRouteName ) {
        return $this->m_objContainer->get( 'routeParser' )->urlFor( $strRouteName );
    }
}