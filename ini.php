<?php
/**
 * --------------------------------------------------
 * CORE PREDEFINED CONSTANTS
 * http://php.net/manual/fr/reserved.constants.php
 * --------------------------------------------------
**/
if( strtoupper( substr( PHP_OS, 0, 3 ) ) == 'WIN' ) : // If the version of the operating system (provided by the pre-defined constants PHP_OS) corresponds to a Windows kernel,
    if( !defined( 'PHP_EOL') ) : define( 'PHP_EOL', "\r\n" ); endif;
    if( !defined( 'DIRECTORY_SEPARATOR') ) : define( 'DIRECTORY_SEPARATOR', "\\" ); endif;
else :
    if( !defined( 'PHP_EOL') ) : define( 'PHP_EOL', "\n" ); endif;
    if( !defined( 'DIRECTORY_SEPARATOR') ) : define( 'DIRECTORY_SEPARATOR', "/" ); endif;
endif;
/**
 * --------------------------------------------------
 * PATHS
 * --------------------------------------------------
**/
if( !defined( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR ); // Defines the folder separator connected to the system
if( !defined( 'ABSPATH' ) ) define( 'ABSPATH', __DIR__ . DS ); // Defines the root folder
if( !defined( 'APPPATH' ) ) define( 'APPPATH', ABSPATH . 'App' . DS ); // Defines the path to the folder containing the aplication files
if( !defined( 'CONTROLLERSPATH' ) ) define( 'CONTROLLERSPATH', APPPATH . 'Controllers' . DS ); // Defines the path to the folder containing the controllers files
if( !defined( 'MODELSPATH' ) ) define( 'MODELSPATH', APPPATH . 'Models' . DS ); // Defines the path to the folder containing the models files
if( !defined( 'VIEWSPATH' ) ) define( 'VIEWSPATH', APPPATH . 'Views' . DS ); // Defines the path to the folder containing the views files
if( !defined( 'FWPATH' ) ) define( 'FWPATH', ABSPATH . 'FW' . DS ); // Defines the path to the folder containing the framework files
if( !defined( 'COREPATH' ) ) define( 'COREPATH', FWPATH . 'core' . DS ); // Defines the path to the folder containing the kernel files for the framework
if( !defined( 'ASSETSPATH' ) ) define( 'ASSETSPATH', FWPATH . 'assets' . DS ); // Defines the path to the folder containing the assets files
if( !defined( 'LAYOUTPATH') ) define('LAYOUTPATH', APPPATH . 'Layout' . DS ); // define path for layout folder
if( !defined( 'STYLEPATH') ) define('STYLEPATH', ABSPATH . 'Assets' . DS . 'Style'. DS); // define path for style & javascript
if( !defined( 'TAGPATH') ) define('TAGPATH', ABSPATH . 'Assets' . DS . 'max-favilli-tagmanager-b43646e'. DS ); // define path for tag manager jquery
if( !defined( 'ADMINPATH' ) ) define( 'ADMINPATH', APPPATH . 'Views' . DS . 'Admin'. DS); // Defines the path to the folder containing the admin views files
if( !defined( 'MODULEPATH' ) ) define( 'MODULEPATH', APPPATH . 'Module' . DS ); // Defines the path to the folder containing the admin views files
/**
 * --------------------------------------------------
 * DB
 * --------------------------------------------------
**/

if(!defined( 'DB_DSN')) define('DB_DSN', 'mysql:dbname=projet_pwitter;host=localhost');
if( !defined( 'DB_LOGIN' ) ) define( 'DB_LOGIN', 'root' );
if( !defined( 'DB_PWD' ) ) define( 'DB_PWD', '' );
