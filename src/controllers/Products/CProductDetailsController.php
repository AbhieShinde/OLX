<?php

namespace Olx\controllers\Products;

use Olx\controllers\CBaseController;

class CProductDetailsController extends CBaseController {

    public function getProductDetails ( $req , $res )   {

        $intId = $req->getQueryParam('id');

        for ($i=0; $i < count( $_SESSION['products'] ); $i++) { 
           
            if ( $_SESSION['products'][$i]['id'] == $intId ) {
                
                $this->view->getEnvironment()->addGlobal('productdetails', $_SESSION['products'][$i] );
            }
        }

        return $this->view->render($res, 'products/productdetails.twig');
    }

}