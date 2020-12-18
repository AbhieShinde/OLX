<?php
Namespace Olx\controllers;

use Slim\Views\TwigExtension;

class CBaseController {

    protected $m_objContainer;
    
    protected $m_intUserId;
    protected $m_intUserName;
    protected $m_boolIsAdmin;

    protected $m_boolDatabaseConnected;

    public function __construct( $objContainer ) {
        
        $this->m_objContainer = $objContainer;
    }

    public function __get( $strPropertyName ) {
        return $this->m_objContainer->get( $strPropertyName );
    }

    public function urlFor( $strRoutePath ) {
        return $this->m_objContainer->get( 'routeParser' )->urlFor( $strRoutePath );
    }

    public function addDefaultGlobals() {
        $this->view->getEnvironment()->addGlobal( 'bool_is_database_connected', true );
    }
}