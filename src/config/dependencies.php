<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use DI\ContainerBuilder;

return static function ( array $arrmixSettings ) {

    /**
    * PHP-DI Container
    */
    $objContainerBuilder = new DI\ContainerBuilder();
    if( $arrmixSettings['di_compilation_path'] ) {
        $objContainerBuilder->enableCompilation( $arrmixSettings['di_compilation_path'] );
    }

    $objContainerBuilder->addDefinitions([
        'settings' => $arrmixSettings
    ]);

    /**
     * Slim v4
     */
    AppFactory::setContainer( $objContainerBuilder->build() );
    $objApp = AppFactory::create();
    $objContainer = $objApp->getContainer();

    // PHP Custom Funtions
    require __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'custom_functions.php';

    $strLogsDirectory = 'src\logs';
    if( is_dir_empty( $strLogsDirectory ) ) {
        foreach( $arrmixSettings['logger']['files'] as $strLogFileName ) {
            $resLogFile = fopen( $strLogsDirectory . DIRECTORY_SEPARATOR . $strLogFileName, 'w' );
            fclose( $resLogFile );
        }
    }

    return [
        $objApp,
        $objContainer
    ];
};
