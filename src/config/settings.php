<?php
declare(strict_types=1);

return static function() {

    /**
    * Getting Environment Variables from .env files
    */
    \Dotenv\Dotenv::create( __DIR__ )->load();

    /**
     * Defining Global Variable for Application Environment
     */
    define( 'APP_ENV', $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'DEV' );
    
    $arrmixSettings =  [
        'app_env' => APP_ENV,
        'di_compilation_path' => __DIR__,
        'display_error_details' => false,
        'db_instance' => parse_ini_file("./src/config/dbconfig.ini"),
        'views' => [
            'cache' => false
        ],
        'log_errors' => true,
        'logger' => [
            'name'  => 'slim-app',
            'files' => [
                'apache-access.log',
                'apache-error.log',
                'php-error.log',
                'slim-app-error.log',
                'slim-app.log',
            ]
        ],
    ];

    if ( APP_ENV === 'LCL' ) {
        // Overrides for development mode
        $arrmixSettings['di_compilation_path'] = '';
        $arrmixSettings['display_error_details'] = true;
        $arrmixSettings['logger']['path'] = __DIR__ . '/logs/slim-app.log';
    }

    return $arrmixSettings;
};
