<?php

namespace Olx\controllers\Products;

use Illuminate\Database\Capsule\Manager as DB;
use Olx\controllers\CBaseController;
use Respect\Validation\Validator as v;

use Olx\models\CAdvertisements;
use Olx\models\CMedia;
use Olx\models\CProductCategories;

class CAdvertisementController extends CBaseController {

    public function getAddAdvertisement ( $req , $res )   {

        $arrobjQuery = CProductCategories::select('id','name')->get();

        $arrmixCategories = json_decode( $arrobjQuery , TRUE );

        $this->view->getEnvironment()->addGlobal('categories', $arrmixCategories);

        return $this->view->render($res, 'products/addAdvertisement.twig');
    }

    public function postAddAdvertisement ( $objRequest, $objResponse )  {

        $objValidation = $this->validator->validate( $objRequest , [
            "title"             => v::notEmpty(),
            "category"          => v::numeric(),
            "price"             => v::noWhitespace()->notEmpty(),
            "description"       => v::notEmpty(),
            "amount"            => v::noWhitespace(),
            "purchased_date"    => v::date()
        ]);

        if ( $objValidation->failed() ) {

            return $objResponse->withRedirect( $this->m_objContainer->router->pathFor( 'addadvertisement' ) );

        } else {

            DB::beginTransaction();

            $arrmixData = $objRequest->getParsedBody();
            
            $objAdvertisement = CAdvertisements::create( [
                'title'                 => $arrmixData['title'],
                'description'           => $arrmixData['description'],
                'price'                 => $arrmixData['price'],
                'product_category_id'   => $arrmixData['category'],
                'amount'                => $arrmixData['amount'],
                'purchased_date'        => $arrmixData['purchased_date'],
                'updated_by'            => $_SESSION['user'],
                'created_by'            => $_SESSION['user'],
            ] );

            $arrmixUploadedFiles = $objRequest->getUploadedFiles();
            
            foreach ( $arrmixUploadedFiles['files'] as $resUploadedFile ) {

                $strPath = $this->AwsS3Client->uploadFile( $resUploadedFile );

                if ( 'ERROR' == $strPath ) {

                    DB::rollBack();
                    
                    $this->flash->addMessage( 'error', 'Oops! There was some error uploading your images. Please try again.' );
                    return $objResponse->withRedirect( $this->m_objContainer->router->pathFor( 'addadvertisement' ) );
                }

                CMedia::create( [
                    'file_size'             => $resUploadedFile->getSize(),
                    'file_name'             => $resUploadedFile->getClientFilename(),
                    'file_path'             => $strPath,
                    'advertisement_id'      => $objAdvertisement->id,
                    'product_category_id'   => $arrmixData['category'],
                    'created_by'            => $_SESSION['user']
                ] );

            }

            DB::commit();

            $this->flash->addMessage( 'info', 'Product added Successfully!' );

            return $objResponse->withHeader( 'Location', $this->urlFor( 'home' ) );
        } 
    }
}