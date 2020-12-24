<?php
namespace Olx\controllers\Products;

use Olx\controllers\CBaseController;

use Olx\Models\CAdvertisements;

use Olx\classes\CAdStatusNotifications as Notify;

class CAdminPanelController extends CBaseController {

    public function getAdminPanel ( $req , $res )   {

        $arrobjQuery = CAdvertisements::with(['photos','owner','category','comments.by'])
                                ->get();

        $arrmixProducts = json_decode( $arrobjQuery , true );

        for ($i=0; $i < count($arrmixProducts); $i++) { 
            
            $_SESSION['titles'][ $arrmixProducts[$i]['id'] ] = [
                'title' => $arrmixProducts[$i]['title'],
                'email' => $arrmixProducts[$i]['owner']['email']
            ]; 
        }

        $arrmixProductsPending = [];
        $arrmixProductsApproved = [];
        $arrmixProductsRejected = [];
            
        for ($i=0; $i < count( $arrmixProducts ); $i++) { 

            switch ($arrmixProducts[$i]['advertisement_status_type_id']) {
                
                case 0:
                    array_push($arrmixProductsPending, $arrmixProducts[$i]);
                    $this->view->getEnvironment()->addGlobal('productsP', $arrmixProductsPending );
                    break;
    
                case 1:
                    array_push($arrmixProductsApproved, $arrmixProducts[$i]);
                    $this->view->getEnvironment()->addGlobal('productsA', $arrmixProductsApproved );
                    break;
                    
                case 2:
                    array_push($arrmixProductsRejected, $arrmixProducts[$i]);
                    $this->view->getEnvironment()->addGlobal('productsR', $arrmixProductsRejected );
                    break;
            }
        }

        $_SESSION['adminproducts'] = $arrmixProducts;

        return $this->view->render($res, 'products/adminpanel.twig');
    }

    public function approveAdvertisement ( $req , $res )  {
        
        $intId = $req->getQueryParams()['id'];

        $arrobjQuery = CAdvertisements::where('id', $intId )->update([
            'advertisement_status_type_id' => 1,
            'updated_by' => $_SESSION['admin']
            ]); //1-true

        if ($arrobjQuery) {

            Notify::approveMail( $_SESSION['titles'][$intId]['title'] , $_SESSION['titles'][$intId]['email'] );

            $this->flash->addMessage('info','Product has been Approved and Published into the Marketplace');

            return $res->withRedirect($this->m_objContainer->router->pathFor('adminpanel'));

        }else {
            
            $this->flash->addMessage('error','Database updation error');

            return $res->withRedirect($this->m_objContainer->router->pathFor('adminpanel'));
        }
    }

    public function rejectAdvertisement ( $req , $res )  {
        
        $intId = $req->getQueryParams()['id'];

        $arrobjQuery = CAdvertisements::where('id',$intId)->update(['advertisement_status_type_id' => 2, 'updated_by' => $_SESSION['admin']]);

        if ($arrobjQuery) {

            Notify::rejectMail( $_SESSION['titles'][$intId]['title'] , $_SESSION['titles'][$intId]['email'] );

            $this->flash->addMessage('info','Product has been Rejected');

            return $res->withRedirect($this->m_objContainer->router->pathFor('adminpanel'));

        }else {
            
            $this->flash->addMessage('error','Database updation error');

            return $res->withRedirect($this->m_objContainer->router->pathFor('adminpanel'));
        }
    }
}