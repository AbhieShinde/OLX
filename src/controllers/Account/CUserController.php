<?php

namespace Olx\controllers\Account;

use Olx\controllers\CBaseController;

use Olx\models\advertisement;

class CUserController extends CBaseController {

    protected $m_intId;

    public function getUserAc ($req,$res)   {

        $arrobjQuery = Advertisement::with('photos','category')
                                ->where('created_by', '=', $_SESSION['user'])
                                ->get();
            
        $arrmixProducts = json_decode( $arrobjQuery , TRUE );

        if ( $arrmixProducts != NULL ) {

            $arrmixProductPending = [];
            $arrmixProductApproved = [];
            $arrmixProductRejected = [];

            for ($i=0; $i < count($arrmixProducts); $i++) {

                switch ($arrmixProducts[$i]['advertisement_status_type_id']) {
                
                    case 0:
                        array_push($arrmixProductPending, $arrmixProducts[$i]);
                        $this->view->getEnvironment()->addGlobal('productsP', $arrmixProductPending );
                        break;
    
                    case 1:
                        array_push($arrmixProductApproved, $arrmixProducts[$i]);
                        $this->view->getEnvironment()->addGlobal('productsA', $arrmixProductApproved );
                        break;
                    
                    case 2:
                        array_push($arrmixProductRejected, $arrmixProducts[$i]);
                        $this->view->getEnvironment()->addGlobal('productsR', $arrmixProductRejected );
                        break;
                }
            }
        }

        $_SESSION['userproducts'] = $arrmixProducts;

        return $this->view->render($res, 'account/user.twig');
    }

    public function deleteAdvertisement ($req,$res)  {
        
        $this->m_intId = $req->getQueryParams()['id'];

        $arrobjQuery = Advertisement::where( 'id' , $this->m_intId )->delete();

        $this->flash->addMessage('info','Product has been deleted');

        return $res->withRedirect($this->m_objContainer->router->pathFor('myaccount'));
    }
}