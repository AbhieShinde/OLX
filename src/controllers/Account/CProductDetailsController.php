<?php

namespace Olx\controllers\Account;

use Olx\controllers\CBaseController;

class CProductDetailsController extends CBaseController {

    public function getProductDetails ( $req , $res )   {

        $intId = $req->getQueryParam('id');

        for ($i=0; $i < count( $_SESSION['userproducts'] ); $i++) { 
           
            if ( $_SESSION['userproducts'][$i]['id'] == $intId ) {
                
                $this->view->getEnvironment()->addGlobal('userproductdetails', $_SESSION['userproducts'][$i] );
            }
        }

        return $this->view->render($res, 'account/productdetails.twig');
    }

}