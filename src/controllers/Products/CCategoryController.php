<?php

namespace Olx\controllers\Products;

use Olx\controllers\CBaseController;
use Respect\Validation\Validator as v;

use Olx\models\productCategory;

class CCategoryController extends CBaseController {

    public function getAddCategory ( $req , $res )   {     
        
        return $this->view->render( $res , 'products/addCategory.twig');
    }

    public function postAddCategory ( $req , $res )  {

        $objValidation = $this->validator->validate($req, [    //rules array
            "name" => v::notEmpty()->alpha(),
            ]);


            if ($objValidation->failed()) {
    
                 return $res->withRedirect($this->m_objContainer->router->pathFor('addcategory'));
    
            }else {
                    
                    $strName = $req->getParam('name');      
                
                   $arrobjQuery= ProductCategory::create([                        
                        'name' => $strName,
                        'updated_by' => $_SESSION['admin']
                    ]);                 
                    
            $this->flash->addMessage('info', 'Category added Successfully!');

            return $res->withRedirect($this->m_objContainer->router->pathFor('home'));
        }
    }
}