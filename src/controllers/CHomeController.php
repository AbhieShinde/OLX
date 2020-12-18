<?php
namespace Olx\controllers;

class CHomeController extends CBaseController {

    public function index( $objRequest, $objResponce ) {

        $this->m_objContainer->get( 'view-extensions' )->setBasePath( $objRequest );

        try {
            $this->m_boolDatabaseConnected = validObject( \Illuminate\Database\Capsule\Manager::connection()->getPdo(), \PDO::class )
                                            && validString( \Illuminate\Database\Capsule\Manager::connection()->getDatabaseName() );
            show( \Illuminate\Database\Capsule\Manager::connection()->getDatabaseName() );
        } catch( \Exception $objException ) {
            $this->m_boolDatabaseConnected = false;
            ( $this->errorLogger )( $objException );
        }
        
        if ( isset( $_SESSION['name'] ) ) {

            return $this->view->render( $objResponce, 'home.twig', [
                'name' => $_SESSION['name'],
            ]);

        } elseif ( isset( $_SESSION['admin_name'] ) ) {

            return $this->view->render( $objResponce, 'home.twig', [
                'name' => $_SESSION['admin_name']
            ]);

        } else {

            return $this->view->render( $objResponce, 'home.twig', [
                'name' => '',
                'bool_is_database_connected' => $this->m_boolDatabaseConnected
            ]);
        }
    }
}