
<?php
error_reporting( E_ALL & ~E_NOTICE );
require_once('ini.php');
require_once( COREPATH . 'common.php');
require_once (ABSPATH. 'localization.php');
//session_start();
$reqStatic = SRequest::getInstance();
//var_dump($_SESSION);
//var_dump($reqStatic);
$StartController = new routerController($reqStatic);
$StartController->startAction($reqStatic);

?>

