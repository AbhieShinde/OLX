<?php

namespace Olx\controllers\Products;

use Olx\controllers\CBaseController;

class CProductDetailsController extends CBaseController {

    public function getProductDetails ( $objRequest, $objResponce ) {

        $this->view->getEnvironment()->addGlobal( 'productdetails', $_SESSION[ 'products' ][ $objRequest->getQueryParams()[ 'id' ] ] );

        return $this->view->render( $objResponce, 'products/productdetails.twig' );
    }

}