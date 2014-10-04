<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once("../include/index.php"); 
require_once ("../../model/menu.php");
require_once ("../../model/session.php");


$session = new SessionApp();
$usuario = $session->isRegisterUserJson(false);

$datos = $_REQUEST;
$accion = $datos["accion"];
$opcion = $datos["opcion"];
$menu = new MenuApp();
if($accion === "CONSULTARMODULOS"){
    $menu->menuModulosJson($usuario);
}

if($accion ==="CONSULTAROPCION"){
    $menu->menuSubMenusJson($usuario, $opcion);
}
?>

