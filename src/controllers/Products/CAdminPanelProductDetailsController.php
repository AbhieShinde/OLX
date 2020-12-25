<?php

namespace Olx\controllers\Products;

use Olx\controllers\CBaseController;

class CAdminPanelProductDetailsController extends CBaseController {

    public function getProductDetails ( $objRequest , $objResponce )   {

        $this->view->getEnvironment()->addGlobal( 'adminproductdetails', $_SESSION[ 'adminproducts' ][ $objRequest->getQueryParams()[ 'id' ] ] );

        return $this->view->render( $objResponce, 'products/adminpanelproductdetails.twig' );
    }

}