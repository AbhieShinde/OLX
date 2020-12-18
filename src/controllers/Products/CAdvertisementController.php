<?php

namespace Olx\controllers\Products;

use Illuminate\Database\Capsule\Manager as DB;
use Olx\controllers\CBaseController;
use Respect\Validation\Validator as v;

use Olx\models\advertisement;
use Olx\models\media;
use Olx\models\productCategory;

class CAdvertisementController extends CBaseController {

    public function getAddAdvertisement ( $req , $res )   {

        $arrobjQuery = ProductCategory::select('id','name')->get();

        $arrmixCategories = json_decode( $arrobjQuery , TRUE );

        $this->view->getEnvironment()->addGlobal('categories', $arrmixCategories);

        return $this->view->render($res, 'products/addAdvertisement.twig');
    }

    public function postAddAdvertisement ( $req , $res )  {

        $objValidation = $this->validator->validate( $req , [
            "title" => v::notEmpty(),
            "category" => v::numeric(),
            "price" => v::noWhitespace()->notEmpty(),
            "description" => v::notEmpty(),
            "amount" => v::noWhitespace(),
            "purchased_date" => v::date()
        ]);

        if ($objValidation->failed()) {

            return $res->withRedirect($this->m_objContainer->router->pathFor('addadvertisement'));

        } else {

            DB::beginTransaction();
            
            $arrobjQuery = Advertisement::create([
                'title' => $req->getParam('title'),
                'description' => $req->getParam('description'),
                'price' => $req->getParam('price'),
                'product_category_id' => $req->getParam('category'),
                'amount' => $req->getParam('amount'),
                'purchased_date' => $req->getParam('purchased_date'),
                'updated_by' => $_SESSION['user'],
                'created_by' => $_SESSION['user'],
            ]);

            $arrmixUploadedFiles = $req->getUploadedFiles();
            
            foreach ( $arrmixUploadedFiles['files'] as $resUploadedFile ) {

                $strPath = $this->AwsS3Client->uploadFile( $resUploadedFile );

                if ( 'ERROR' == $strPath ) {

                    DB::rollBack();
                    
                    $this->flash->addMessage('error', 'Oops! There was some error uploading your images. Please try again.');
                    return $res->withRedirect($this->m_objContainer->router->pathFor('addadvertisement'));
                }

                $arrobjQuery1 = Media::create([
                    'file_size' => $resUploadedFile->getSize(),
                    'file_name' => $resUploadedFile->getClientFilename(),
                    'file_path' => $strPath,
                    'advertisement_id' => $arrobjQuery->id,
                    'product_category_id' => $req->getParam('category'),
                    'created_by' => $_SESSION['user'],
                ]);

            }

            DB::commit();

            $this->flash->addMessage('info', 'Product added Successfully!');

            return $res->withRedirect($this->m_objContainer->router->pathFor('home'));
        } 
    }
}