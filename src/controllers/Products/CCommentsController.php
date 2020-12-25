<?php

namespace Olx\controllers\Products;

use Olx\controllers\CBaseController;

use Olx\models\CAdvertisementComments;

use Olx\classes\CLikeNotifications as like;
use Olx\classes\CDislikeNotifications as dislike;
use Olx\classes\CCommentNotifications as comment;

class CCommentsController extends CBaseController {

    public function postLike ( $req , $res )   {

        $arrobjQuery = CAdvertisementComments::select('id')
                                ->where([
                                    ['created_by', '=', "$_SESSION[user]"],
                                    ['comment_type_id', '=', '0'],
                                    ['advertisement_id', '=', $req->getQueryParam('id')]
                                ])
                                ->get();

        if ( count( $arrobjQuery ) == 0 ) {

            $arrobjQuery = CAdvertisementComments::create([
                'advertisement_id' => $req->getQueryParam('id'),
                'comment_type_id' => '0',
                'updated_by' => $_SESSION['user'],
                'created_by' => $_SESSION['user']
            ]);
    
            like::sendMail( $_SESSION['name'] , $req->getQueryParam('title') , $req->getQueryParam('emailto') );
    
            $this->flash->addMessage('info', 'Liked!');
    
            return $res->withRedirect($this->m_objContainer->router->pathFor('marketplace'));

        }else {
    
            $this->flash->addMessage('info', 'Alredy Liked!');
    
            return $res->withRedirect($this->m_objContainer->router->pathFor('marketplace'));
        }
        
    }

    public function postDislike ( $req , $res )   {

        $arrobjQuery = CAdvertisementComments::select('id')
                                ->where([
                                    ['created_by', '=', "$_SESSION[user]"],
                                    ['comment_type_id', '=', '1'],
                                    ['advertisement_id', '=', $req->getQueryParam('id')]
                                ])
                                ->get();

        if ( count( $arrobjQuery ) == 0 ) {

            $arrobjQuery = CAdvertisementComments::create([
                'advertisement_id' => $req->getQueryParam('id'),
                'comment_type_id' => '1',
                'updated_by' => $_SESSION['user'],
                'created_by' => $_SESSION['user']
            ]);
    
            dislike::sendMail( $_SESSION['name'] , $req->getQueryParam('title') , $req->getQueryParam('emailto') );
    
            $this->flash->addMessage('info', 'Product Disliked!');
    
            return $res->withRedirect($this->m_objContainer->router->pathFor('marketplace'));

        }else {
    
            $this->flash->addMessage('info', 'Product alredy Disliked!');
    
            return $res->withRedirect($this->m_objContainer->router->pathFor('marketplace'));
        }
    
    }

    public function postComment ( $req , $res )   {

        $arrobjQuery = CAdvertisementComments::create([
            'advertisement_id' => $req->getParam('id'),
            'comment_type_id' => '2',
            'message' => $req->getParam('comment'),
            'updated_by' => $_SESSION['user'],
            'created_by' => $_SESSION['user']
        ]);

        comment::sendMail( $_SESSION['name'] , $req->getParam('title') , $req->getParam('comment') , $req->getParam('emailto') );
        comment::sendSms( $_SESSION['name'] , $req->getParam('title') , $req->getParam('comment') , $req->getParam('phone') );

        $this->flash->addMessage('info', 'Comment added');

        return $res->withRedirect($this->m_objContainer->router->pathFor('marketplace'));
    
    }
}