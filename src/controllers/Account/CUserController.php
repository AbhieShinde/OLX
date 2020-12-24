<?php

namespace Olx\controllers\Account;

use Olx\controllers\CBaseController;

use Olx\Models\CAdvertisements;

class CUserController extends CBaseController {

    protected $m_intId;

    public function getUserAc( $objRequest, $objResponce ) {

        $arrmixAdvertisements = [];

        $arrobjAdvertisements = CAdvertisements::with( 'photos', 'category' )
                                ->where( 'created_by', '=', $_SESSION['user'] )
                                ->get();

        if ( 0 < count( $arrobjAdvertisements ) ) {

            $arrmixProductPending   = [];
            $arrmixProductApproved  = [];
            $arrmixProductRejected  = [];

            foreach ( $arrobjAdvertisements as $objAdvertisement ) {

                $arrmixAdvertisementAttributes = $objAdvertisement->getAttributes();

                $arrmixAdvertisementAttributes['category'] = $objAdvertisement->category()->getResults()->getAttributes();

                foreach ( $objAdvertisement->photos()->getResults() as $objMedia ) {
                    $arrmixAdvertisementAttributes['photos'][] = $objMedia->getAttributes();
                }

                $arrmixAdvertisements[] = $arrmixAdvertisementAttributes;

                switch ( $arrmixAdvertisementAttributes['advertisement_status_type_id'] ) {
                
                    case 0:
                        array_push( $arrmixProductPending, $arrmixAdvertisementAttributes );
                        $this->view->getEnvironment()->addGlobal( 'productsP', $arrmixProductPending );
                        break;
    
                    case 1:
                        array_push( $arrmixProductApproved, $arrmixAdvertisementAttributes );
                        $this->view->getEnvironment()->addGlobal( 'productsA', $arrmixProductApproved );
                        break;
                    
                    case 2:
                        array_push( $arrmixProductRejected, $arrmixAdvertisementAttributes );
                        $this->view->getEnvironment()->addGlobal( 'productsR', $arrmixProductRejected );
                        break;
                }
            }
        }

        $_SESSION['userproducts'] = $arrmixAdvertisements;

        return $this->view->render( $objResponce, 'account/user.twig' );
    }

    public function deleteAdvertisement ( $objRequest, $objResponce )  {
        
        $this->m_intId = $objRequest->getQueryParams()['id'];

        CAdvertisements::where( 'id' , $this->m_intId )->delete();

        $this->flash->addMessage( 'info', 'Product has been deleted' );

        return $objResponce->withRedirect( $this->m_objContainer->router->pathFor( 'myaccount' ) );
    }
}