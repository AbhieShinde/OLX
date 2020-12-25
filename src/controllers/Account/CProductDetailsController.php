<?php

namespace Olx\controllers\Account;

use Olx\controllers\CBaseController;

class CProductDetailsController extends CBaseController {

    public function getProductDetails ( $objRequest , $objResponce ) {

        $this->view->getEnvironment()->addGlobal( 'userproductdetails', $_SESSION[ 'userproducts' ][ $objRequest->getQueryParams()[ 'id' ] ] );
        
        return $this->view->render( $objResponce, 'account/productdetails.twig' );
    }

}