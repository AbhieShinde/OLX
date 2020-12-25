<?php
namespace Olx\controllers\Products;

use Olx\controllers\CBaseController;
use Olx\models\CAdvertisements;

class CMarketplaceController extends CBaseController {

    public function getMarketplace( $objRequest, $objResponse ) {

        if ( empty($_SESSION['user']) ) {

            $arrobjAdvertisements = CAdvertisements::with(['photos','owner','category','comments.by'])
                                ->where([
                                    ['advertisement_status_type_id', '=', 1]
                                    ])
                                ->get();
        } else {

            $arrobjAdvertisements = CAdvertisements::with(['photos','owner','category','comments.by'])
                                ->where([
                                    ['advertisement_status_type_id', '=', 1],
                                    ['created_by', '!=', $_SESSION['user']]
                                    ])
                                ->get();
        }

        if ( 0 < count( $arrobjAdvertisements ) ) {

            $arrmixProducts  = [];
            $arrmixProductsE = [];
            $arrmixProductsH = [];
            $arrmixProductsF = [];
            $arrmixProductsC = [];
            $arrmixProductsB = [];
            $arrmixProductsO = [];

            foreach( $arrobjAdvertisements as $objAdvertisement ) {

                $arrmixProduct = $objAdvertisement->getAttributes();

                $arrmixProduct['category']  = $objAdvertisement->category()->getResults()->getAttribute( 'name' );
                $arrmixProduct['owner']     = $objAdvertisement->owner()->getResults()->getAttributes();
                $arrmixProduct['likes']     = count( $objAdvertisement->comments()->getResults()->where( 'comment_type_id', '=', 0 ) );
                $arrmixProduct['dislikes']  = count( $objAdvertisement->comments()->getResults()->where( 'comment_type_id', '=', 1 ) );
                $arrmixProduct['comments']  = [];
                $arrmixProduct['photos']    = [];

                foreach( $objAdvertisement->comments()->getResults()->where('comment_type_id', '=', 2 ) as $objAdvertisementComment ) {
                    $arrmixCommentData              = $objAdvertisementComment->getAttributes();
                    $arrmixComment['updated_at']    = $arrmixCommentData[ 'updated_at' ];
                    $arrmixComment['message']       = $arrmixCommentData[ 'message' ];
                    $arrmixComment['name']          = $objAdvertisementComment->by()->getResults()->getAttribute( 'name' );

                    array_push( $arrmixProduct['comments'], $arrmixComment );
                }

                foreach ( $objAdvertisement->photos()->getResults() as $objMedia ) {
                    array_push( $arrmixProduct['photos'], $objMedia->getAttribute( 'file_path' ) );
                }

                switch( $arrmixProduct[ 'product_category_id' ] ) {
                
                    case 0:
                        array_push( $arrmixProductsE, $arrmixProduct );
                        $this->view->getEnvironment()->addGlobal( 'productsE', $arrmixProductsE );
                        break;
                    case 1:
                        array_push( $arrmixProductsH, $arrmixProduct );
                        $this->view->getEnvironment()->addGlobal( 'productsH', $arrmixProductsH );
                        break;
                    case 2:
                        array_push( $arrmixProductsF, $arrmixProduct );
                        $this->view->getEnvironment()->addGlobal( 'productsF', $arrmixProductsF );
                        break;
                    case 3:
                        array_push( $arrmixProductsC, $arrmixProduct );
                        $this->view->getEnvironment()->addGlobal( 'productsC', $arrmixProductsC );
                        break;
                    case 4:
                        array_push( $arrmixProductsB, $arrmixProduct );
                        $this->view->getEnvironment()->addGlobal( 'productsB', $arrmixProductsB );
                        break;
                    case 5:
                        array_push( $arrmixProductsO, $arrmixProduct );
                        $this->view->getEnvironment()->addGlobal( 'productsO', $arrmixProductsO );
                        break;
                }

                $arrmixProducts[] = $arrmixProduct;
            }
        }

        $_SESSION['products'] = rekeyArray( 'id', $arrmixProducts );

        return $this->view->render( $objResponse, 'products/marketplace.twig' );
    }

    public function getSearchResults( $objRequest, $objResponse ) {

        $strProductS = '';
        $strSearchQuery = $objRequest->getQueryParams()['search'];
        
        foreach ( $_SESSION['products'] as $intProductId => $arrmixProduct ) {

            if ( strpos( strtolower( $arrmixProduct['title'] ), strtolower( $strSearchQuery ) ) !== FALSE || strpos( strtolower($arrmixProduct['description']), strtolower( $strSearchQuery ) ) !== FALSE ) {
                //using '!=='(identical match) instead of just '!=', because strpos returns position when match finds, in the case of first word it returns Zero, so it'll treated as false.(which means match not find)

                $strTitle = $arrmixProduct['title'];
                $strCategory = $arrmixProduct['category'];
                $intPrice = $arrmixProduct['price'];
                $strOwner = $arrmixProduct['owner']['name'];
                $intLikes = $arrmixProduct['likes'];
                $intDislikes = $arrmixProduct['dislikes'];
                $resPhoto = $arrmixProduct['photos'][0];

                $strProductS = $strProductS . 
                "<a href=" . $_SESSION['BASE_URL'] . "/marketplace/productdetails?id=$intProductId>
                    <div class="."main".">
                        <h4 class="."title".">$strTitle</h4>
                        <img src=$resPhoto class="."product_img".">
                        <h5 class="."category".">$strCategory</h5>
                        <h5 class="."price".">₹ $intPrice</h5>
                        <div class="."owner".">Owner : $strOwner</div>
                        <div class="."like".">
                            <center class="."count"."> 
                                <span class="."ld-count".">$intLikes</span><img src="."./public/images/like.png"." alt="."Likes"." class="."ld".">
                                <span class="."ld-count".">$intDislikes</span><img src="."./public/images/dislike.png"." alt="."Dislikes"." class="."ld".">    
                            </center>
                        </div>
                    </div>
                </a>";              
            }
        }
        
        $objResponse->getBody()->write( ( $strProductS == NULL ) ? '<h2>Oops! No such product found. Try searching for Product\'s Title or Description.</h2>' : $strProductS );
        return $objResponse;
    }

}