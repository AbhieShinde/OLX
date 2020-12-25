<?php
use Slim\Factory\AppFactory;

use Olx\middleware\CUserMiddleware;
use Olx\middleware\CAdminMiddleware;
use Olx\middleware\CGuestMiddleware;

$objContainer = new \DI\Container();
AppFactory::setContainer( $objContainer );
$objApp = AppFactory::create();
$objContainer = $objApp->getContainer();

/**
 * Landing Page
 */
$objApp->get('/', 'HomeController:index')->setName('home');

/**
 * Public Marketplace
 */
$objApp->get('/marketplace', 'MarketplaceController:getMarketplace')->setName('marketplace');
$objApp->get('/marketplace/productdetails', 'ProductDetailsController:getProductDetails');
$objApp->get('/marketplace/searchproduct', 'MarketplaceController:getSearchResults')->setName('searchproduct');

/**
 * Routes Accessible for logged in Users only
 */
$objApp->group('', function ( $objGroup ) {

    $objGroup->get('/signout', 'AuthController:getSignOut')->setName('signout');

    $objGroup->get('/changepassword', 'PasswordController:getChangePassword')->setName('changepassword');
    $objGroup->post('/changepassword', 'PasswordController:postChangePassword');

    $objGroup->get('/addadvertisement', 'AdvertisementController:getAddAdvertisement')->setName('addadvertisement');
    $objGroup->post('/addadvertisement', 'AdvertisementController:postAddAdvertisement');

    $objGroup->get('/myaccount', 'UserController:getUserAc')->setName('myaccount');
    $objGroup->get('/myaccount/deleteAd', 'UserController:deleteAdvertisement');
    $objGroup->get('/myaccount/productdetails', 'AcProductDetailsController:getProductDetails');

    $objGroup->get('/marketplace/productdetails/like', 'CommentsController:postLike');
    $objGroup->get('/marketplace/productdetails/dislike', 'CommentsController:postDislike');
    $objGroup->post('/marketplace/productdetails/comment', 'CommentsController:postComment')->setName('comment');

})->add( new CUserMiddleware( $objContainer ) );

/**
 * Routes Accessible for Administrators only
 */
$objApp->group('', function ( $objGroup ) {

    $objGroup->get('/signoutadmin', 'AuthController:getSignOutAdmin')->setName('signoutadmin');

    $objGroup->get('/changepasswordadmin', 'PasswordController:getChangePasswordAdmin')->setName('changepasswordadmin');
    $objGroup->post('/changepasswordadmin', 'PasswordController:postChangePasswordAdmin');

    $objGroup->get('/myadminaccount', 'AdminController:getAdminAc')->setName('myadminaccount');

    $objGroup->get('/adminpanel', 'AdminPanelController:getAdminPanel')->setName('adminpanel');
    $objGroup->get('/adminpanel/productdetails', 'AdminProductDetailsController:getProductDetails');
    $objGroup->get('/adminpanel/approve', 'AdminPanelController:approveAdvertisement');
    $objGroup->get('/adminpanel/reject', 'AdminPanelController:rejectAdvertisement');

    $objGroup->get('/adminpanel/addcategory', 'CategoryController:getAddCategory')->setName('addcategory');
    $objGroup->post('/adminpanel/addcategory', 'CategoryController:postAddCategory');

    $objGroup->get('/signupadmin', 'AuthController:getSignUpAdmin')->setName('signupadmin');
    $objGroup->post('/signupadmin', 'AuthController:postSignUpAdmin');

})->add( new CAdminMiddleware( $objContainer ) );

/**
 * Routes Accessible Guests only
 */
$objApp->group('', function ( $objGroup ) {

    $objGroup->get('/signup', 'AuthController:getSignUp')->setName('signup');
    $objGroup->post('/signup', 'AuthController:postSignUp');

    $objGroup->get('/signin', 'AuthController:getSignIn')->setName('signin');
    $objGroup->post('/signin', 'AuthController:postSignIn');

    $objGroup->post('/adminsignin', 'AuthController:postAdminSignIn')->setName('admin_signin');

    $objGroup->get('/recoverpassword', 'PasswordController:getRecoverPassword')->setName('recoverpassword');
    $objGroup->post('/recoverpassword', 'PasswordController:postRecoverPassword');
    $objGroup->get('/resetpassword', 'PasswordController:getResetPassword')->setName('resetpassword');
    $objGroup->post('/resetpassword', 'PasswordController:postResetPassword');
    
})->add( new CGuestMiddleware( $objContainer ) );