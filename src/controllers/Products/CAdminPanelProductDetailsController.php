<?php

namespace Olx\controllers\Products;

use Olx\controllers\CBaseController;

class CAdminPanelProductDetailsController extends CBaseController {

    public function getProductDetails ( $req , $res )   {

        $intId = $req->getQueryParam('id');

        for ($i=0; $i < count( $_SESSION['adminproducts'] ); $i++) { 
           
            if ( $_SESSION['adminproducts'][$i]['id'] == $intId ) {
                
                $this->view->getEnvironment()->addGlobal('adminproductdetails', $_SESSION['adminproducts'][$i] );
            }
        }

        return $this->view->render($res, 'products/adminpanelproductdetails.twig');
    }

}