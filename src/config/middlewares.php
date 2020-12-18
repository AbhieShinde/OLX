<?php
declare(strict_types=1);

use Slim\App;
use Slim\Psr7\Response;
use Slim\Exception\HttpNotFoundException;

return static function ( App $objApp ) {

    $objApp->addRoutingMiddleware();
    $objApp->addBodyParsingMiddleware();
    $objErrorMiddleware = $objApp->addErrorMiddleware( true, true, true );
    $objContainer = $objApp->getContainer();

    if( 'LCL' != APP_ENV ) {

        /**
        * Slim Application Errors
        */
        $objErrorMiddleware->setDefaultErrorHandler( function ( $request, Throwable $exception, bool $displayErrorDetails ) use ( $objContainer ) {
            $objResponse = new Response();
            $objContainer->get( 'errorNotifier' )( $exception );
            $objContainer->get( 'errorLogger')( $exception );
            $objContainer->get( 'view' )->render( $objResponse, 'errors/errorHandler.twig' );

            return $objResponse->withStatus( 500 );
        });

        /**
        * Custom 404 handler
        */
        $objErrorMiddleware->setErrorHandler(
            HttpNotFoundException::class,
            function ( $request, Throwable $exception, bool $displayErrorDetails ) use ( $objContainer ) {
                $objResponse = new Response();
                // email content from $exception
                $objContainer->get( 'view' )->render( $objResponse, 'errors/notFound.twig' );

                return $objResponse->withStatus( 404 );
            }
        );
    }
};
