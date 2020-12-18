<?php
namespace Olx\controllers\Extensions;

use Olx\controllers\CBaseController;

class CTwigExtensionsController extends CBaseController {

    private $m_objApp;
    private $m_strBasePath;

    public function setBasePath( $objRequest ) {
        $strScheme = ( 'LCL' === APP_ENV || 'LCL_OLDB' === APP_ENV ) ? 'http' : 'https';
        $this->m_strBasePath = $strScheme . '://' . $objRequest->getUri()->getHost() . ( validInteger( $objRequest->getUri()->getPort() ) ? ':' . $objRequest->getUri()->getPort() : '' );
    }

    public function setTwigFunctions( $objApp ) {

        $this->m_objApp = $objApp;

        $objBasePathTwigFunction = new \Twig\TwigFunction('base_path', function () {
            return $this->m_strBasePath;
        });
        $this->view->getEnvironment()->addFunction( $objBasePathTwigFunction );

        $objUrlForTwigFunction = new \Twig\TwigFunction('url_for', function ( $strRouteName ) {
            $routeParser = $this->m_objApp->getRouteCollector()->getRouteParser();
            return $routeParser->urlFor( $strRouteName );
        });
        $this->view->getEnvironment()->addFunction( $objUrlForTwigFunction );
    }

    public function setTwigFilters( $objApp ) {
         
        $this->m_objApp = $objApp;

        $objShowTwigFilter = new \Twig\TwigFilter('show', function ( $mixData ) {
            echo '<pre style="background-color:white; color:rgb(32, 56, 18);padding:5px; border: 1px solid black; border-radius: 4px;">', htmlentities( print_r( $mixData, true ) ), '</pre>';
        });
        $this->view->getEnvironment()->addFilter( $objShowTwigFilter );
    }

    public function setRouteParser( $objApp ) {

        $this->m_objApp = $objApp;
        $this->m_objContainer->set( 'routeParser', function() {
            return $this->m_objApp->getRouteCollector()->getRouteParser();
        });
    }
}