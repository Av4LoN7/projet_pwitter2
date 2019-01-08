<?php
// mise en place de l'autoloader

function autoLoadClass( $className)
{
    //echo $className . '<br>';
    $file = ( defined( 'COREPATH') ? COREPATH : '') . $className . '.php';
    if( file_exists( $file) ) require_once( $file );

    $file = ( defined( 'CONTROLLERSPATH') ? CONTROLLERSPATH : '') . $className . '.php';
    if( file_exists( $file) ) require_once( $file );

    $file = ( defined( 'MODELSPATH') ? MODELSPATH : '') . $className . '.php';
    if( file_exists( $file) ) require_once( $file );

    $file = ( defined( 'MODULEPATH') ? MODULEPATH : '') . $className . '.php';
    if( file_exists( $file) ) require_once( $file );
    /*print_r($className);
    print_r($file .'<br>');*/
}

spl_autoload_register( 'autoLoadClass');