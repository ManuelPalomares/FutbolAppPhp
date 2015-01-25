<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once("../include/index.php"); 
require_once ("../../model/session.php");
require_once ("../../model/Cobros.php");

/* Controla el acceso a usuarios externos no logueados */
$session = new SessionApp();
$usuario = $session->isRegisterUserJson(false);
$opcion_actual = $session->getOpcionActual();
$datos = $_REQUEST;

$accion= $datos["accion"];
//$codigo = $datos["codigo"];
//$descripcion = $datos["descripcion"];


/*TODO Operaciones con las variables POST O GET*/
//crear clase Roles
$cobros = new Cobros($usuario, $accion, $opcion_actual);
/*
if($accion == "GUARDAR"){
    $rs = $roles->guardarRol($descripcion);
    
    echo json_encode($rs);
    exit();
}

if($accion =="ACTUALIZAR"){
    $rs = $roles->updateRol($codigo, $descripcion);
    echo json_encode($rs);
    exit(); 
}

if($accion =="ELIMINAR"){
    $rs = $roles->eliminarRol($codigo);
    echo json_encode($rs);
    exit(); 
}
*/
if($accion=="CONSULTARCOBROS"){
    $cobros->consultarCobrosJson();
}

?>