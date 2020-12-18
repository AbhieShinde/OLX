<?php
declare(strict_types=1);

session_start();
require __DIR__ . '/../vendor/autoload.php';

/**
 * Application Environment Settings
 */
$arrmixSettings = ( require __DIR__ . '/../src/config/settings.php' )();

/**
 * Dependency Configurations
 */
list( $objApp, $objContainer ) = ( require __DIR__ . '/../src/config/dependencies.php' )( $arrmixSettings );

/**
 * Registering Routes
 */
require __DIR__ . '/../src/routes/routes.php';

/**
 * Registering Middlewares
 */
( require __DIR__ . '/../src/config/middlewares.php' )( $objApp );

/**
 * Views
 * Twig Template Engine v3
 */
$objContainer->set( 'view', function() use ( $arrmixSettings ) {
    return Slim\Views\Twig::create( __DIR__ . '/../src/views',
        [ 'cache' => $arrmixSettings['views']['cache'] ]);
});

/**
 * Database Connection
 */
var_dump( $_ENV['DB_USER'] );
var_dump( getenv('DB_USER') );
exit;
$arrstrDatabaseConnection = array( 'settings' => array(
    'driver' => 'pgsql',
    'host' => ( 'LCL' === APP_ENV ) ? 'localhost' : $arrmixSettings['db_instance']['host'],
    'database' => $arrmixSettings['db_instance']['dbname'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['PASS'],
    'charset' => 'utf-8'
    )
);

// Eloquent Illuminate ORM for Slim
$objCapsule = new \Illuminate\Database\Capsule\Manager;
$objCapsule->addConnection( $arrstrDatabaseConnection['settings'] );
$objCapsule->setAsGlobal();
$objCapsule->bootEloquent();

/**
 * Controllers
 */
$objContainer->set( 'HomeController', function( $objContainer ) {
    return new Olx\controllers\CHomeController( $objContainer );
});

$objContainer->set( 'AuthController', function( $objContainer ) {
    return new \Olx\controllers\Auth\CAuthController( $objContainer );
});

$objContainer->set( 'PasswordController', function( $objContainer ) {
    return new \Olx\controllers\Auth\CPasswordController( $objContainer );
});

$objContainer->set( 'flash', function() {
    return new \Slim\Flash\Messages;
});

$objContainer->get( 'view' )->getEnvironment()->addGlobal( 'auth', [
    'check' => $objContainer->get( 'AuthController' )->check(),
    'checkAdmin' => $objContainer->get( 'AuthController' )->checkAdmin()
]);

$objContainer->set( 'view-extensions', function( $objContainer ) {
    return new Olx\controllers\Extensions\CTwigExtensionsController( $objContainer );
});
// adding twig extensions
$objContainer->get( 'view-extensions' )->setTwigFunctions( $objApp );
$objContainer->get( 'view-extensions' )->setTwigFilters( $objApp );
$objContainer->get( 'view-extensions' )->setRouteParser( $objApp );

/**
 * Adding Global before Twig instance will be instantiated
 * Add any Twig Global before any twig view load() or render() call
 * @see https://discourse.slimframework.com/t/slim-4-twig-globals/3464/10?u=abhie
 **/

$objContainer->get( 'view' )->getEnvironment()->addGlobal( 'flash', $objContainer->get( 'flash' ) );

$objContainer->set( 'validator', function() {
    return new Olx\validation\CValidator;
});

//Custom Validation rules for Respect Validation Engine
Respect\Validation\Validator::with('Olx\\validation\\Rules\\');

$objResponseFactory = $objApp->getResponseFactory();

$objContainer->set( 'csrf', function() use ( $objResponseFactory ) {
    return new \Slim\Csrf\Guard( $objResponseFactory );
});

// activating cross-site request forgery protection
$objContainer->get( 'csrf' )->generateToken();

$objContainer->get( 'view' )->getEnvironment()->addGlobal('csrf', [
    'field' => '
        <input type="hidden" name="' . $objContainer->get( 'csrf' )->getTokenNameKey() . '" value="' . $objContainer->get( 'csrf' )->getTokenName() . '">
        <input type="hidden" name="' . $objContainer->get( 'csrf' )->getTokenValueKey() . '" value="' . $objContainer->get( 'csrf' )->getTokenValue() . '">
    ',
]);

$objContainer->set( 'UserController', function( $objContainer ) {
    return new \Olx\controllers\Account\CUserController( $objContainer );
});

$objContainer->set( 'AdminController', function( $objContainer ) {
    return new \Olx\controllers\Account\CAdminController( $objContainer );
});

$objContainer->set( 'AdvertisementController', function( $objContainer ) {
    return new \Olx\controllers\Products\CAdvertisementController( $objContainer );
});

$objContainer->set( 'MarketplaceController', function( $objContainer ) {
    return new \Olx\controllers\Products\CMarketplaceController( $objContainer );
});

$objContainer->set( 'ProductDetailsController', function( $objContainer ) {
    return new \Olx\controllers\Products\CProductDetailsController( $objContainer );
});

$objContainer->set( 'AdminPanelController', function( $objContainer ) {
    return new \Olx\controllers\Products\CAdminPanelController( $objContainer );
});

$objContainer->set( 'CommentsController', function( $objContainer ) {
    return new \Olx\controllers\Products\CCommentsController( $objContainer );
});

$objContainer->set( 'CategoryController', function( $objContainer ) {
    return new \Olx\controllers\Products\CCategoryController( $objContainer );
});

$objContainer->set( 'AcProductDetailsController', function( $objContainer ) {
    return new \Olx\controllers\Account\CProductDetailsController( $objContainer );
});

$objContainer->set( 'AdminProductDetailsController', function( $objContainer ) {
    return new \Olx\controllers\Products\CAdminPanelProductDetailsController( $objContainer );
});

$objContainer->set( 'Notifications', function( $objContainer ) {
    return new \Olx\controllers\Notifications\CNotificationsController( $objContainer );
});

/**
 * External Tools
 */

$objContainer->set( 'AwsS3Client', function( $objContainer ) {
    return new \Olx\controllers\Tools\CAwsS3ClientController( $objContainer );
});

$objContainer->set( 'EmailConsumer', function( $objContainer ) {
    return new \Olx\controllers\Tools\CEmailConsumerController( $objContainer );
});

/**
 * Custom Error Handler Supporting Middlewares
 */

$objContainer->set( 'errorNotifier', function( $objContainer ) {
    return new \Olx\middleware\CErrorNotifier( $objContainer );
});

$objContainer->set( 'errorLogger', function( $objContainer ) {
    return new \Olx\middleware\CErrorLogger( $objContainer );
});
