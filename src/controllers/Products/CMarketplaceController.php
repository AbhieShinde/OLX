<?php
namespace Olx\controllers\Products;

use Olx\controllers\CBaseController;
use Olx\models\CAdvertisements;

class CMarketplaceController extends CBaseController {

    public function getMarketplace ( $req , $res )   {

        if ( empty($_SESSION['user']) ) {

            $arrobjQuery = CAdvertisements::with(['photos','owner','category','comments.by'])
                                ->where([
                                    ['advertisement_status_type_id', '=', 1]
                                    ])
                                ->get();
        } else {

            $arrobjQuery = CAdvertisements::with(['photos','owner','category','comments.by'])
                                ->where([
                                    ['advertisement_status_type_id', '=', 1],
                                    ['created_by', '!=', $_SESSION['user']]
                                    ])
                                ->get();
        }

        $arrmixProducts = json_decode( $arrobjQuery, true );

        if ( $arrmixProducts != NULL ) {

            $arrmixProductsE = [];
            $arrmixProductsH = [];
            $arrmixProductsF = [];
            $arrmixProductsC = [];
            $arrmixProductsB = [];
            $arrmixProductsO = [];

            for ($i=0; $i < count($arrmixProducts); $i++) {

                $arrmixProducts[$i]['likes'] = count($arrobjQuery[$i]['comments']->where('comment_type_id', '=', '0'));
                $arrmixProducts[$i]['dislikes'] = count($arrobjQuery[$i]['comments']->where('comment_type_id', '=', '1'));
                //overwriting the comments key's value
                $arrmixProducts[$i]['comments'] = json_decode($arrobjQuery[$i]['comments']->where('comment_type_id', '=', '2'), TRUE);

                switch ($arrmixProducts[$i]['product_category_id']) {
                
                    case 0:
                        array_push($arrmixProductsE, $arrmixProducts[$i]);
                        $this->view->getEnvironment()->addGlobal('productsE', $arrmixProductsE );
                        break;
                    case 1:
                        array_push($arrmixProductsH, $arrmixProducts[$i]);
                        $this->view->getEnvironment()->addGlobal('productsH', $arrmixProductsH );
                        break;
                    case 2:
                        array_push($arrmixProductsF, $arrmixProducts[$i]);
                        $this->view->getEnvironment()->addGlobal('productsF', $arrmixProductsF );
                        break;
                    case 3:
                        array_push($arrmixProductsC, $arrmixProducts[$i]);
                        $this->view->getEnvironment()->addGlobal('productsC', $arrmixProductsC );
                        break;
                    case 4:
                        array_push($arrmixProductsB, $arrmixProducts[$i]);
                        $this->view->getEnvironment()->addGlobal('productsB', $arrmixProductsB );
                        break;
                    case 5:
                        array_push($arrmixProductsO, $arrmixProducts[$i]);
                        $this->view->getEnvironment()->addGlobal('productsO', $arrmixProductsO );
                        break;
                }
            }
        }

        $_SESSION['products'] = $arrmixProducts;

        $this->addDefaultGlobals();

        return $this->view->render($res, 'products/marketplace.twig');
    }

    public function getSearchResults ( $req, $res ) {

        $strProductS = '';
        
        for ($i=0; $i < count($_SESSION['products']); $i++) {
            
            if ( strpos( strtolower($_SESSION['products'][$i]['title']), strtolower( $req->getParam('search') ) ) !== FALSE || strpos( strtolower($_SESSION['products'][$i]['description']), strtolower( $req->getParam('search') ) ) !== FALSE ) {
                //using '!=='(identical match) instead of just '!=', because strpos returns position when match finds, in the case of first word it returns Zero, so it'll treated as false.(which means match not find)

                $intId = $_SESSION['products'][$i]['id'];
                $strTitle = $_SESSION['products'][$i]['title'];
                $strCategory = $_SESSION['products'][$i]['category']['name'];
                $intPrice = $_SESSION['products'][$i]['price'];
                $strOwner = $_SESSION['products'][$i]['owner']['name'];
                $intLikes = $_SESSION['products'][$i]['likes'];
                $intDislikes = $_SESSION['products'][$i]['dislikes'];
                $resPhoto = $_SESSION['products'][$i]['photos'][0]['file_path'];

                $strProductS = $strProductS . 
                "<a href=" . $_SESSION['BASE_URL'] . "/marketplace/productdetails?id=$intId>
                    <div class="."main".">
                        <h4 class="."title".">$strTitle</h4>
                        <img src=$resPhoto class="."product_img".">
                        <h5 class="."category".">$strCategory</h5>
                        <h5 class="."price".">₹ $intPrice</h5>
                        <div class="."owner".">Owner : $strOwner</div>
                        <div class="."like".">
                            <center class="."count"."> 
                                <span class="."ld-count".">$intLikes</span><img src="."./images/like.png"." alt="."Likes"." class="."ld".">
                                <span class="."ld-count".">$intDislikes</span><img src="."./images/dislike.png"." alt="."Dislikes"." class="."ld".">    
                            </center>
                        </div>
                    </div>
                </a>";              
            }
        }
        
        if ( $strProductS == NULL ) {

            return '<h2>Oops! No such product found !!</h2>';

        } else {
            
            return $strProductS;
        }
    }

}