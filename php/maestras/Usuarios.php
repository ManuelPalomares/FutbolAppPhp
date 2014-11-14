<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once("../include/index.php"); 
require_once ("../../model/session.php");
require_once ("../../model/Usuarios.php");

/* Controla el acceso a usuarios externos no logueados */
$session = new SessionApp();
$usuario = $session->isRegisterUserJson(false);
$opcion_actual = $session->getOpcionActual();

$datos = $_REQUEST;

$accion= $datos["accion"];
$codigo = $datos["codigo"];
$descripcion = $datos["nombres"];


/*TODO Operaciones con las variables POST O GET*/
//crear clase Roles
$usuarios = new Usuarios($usuario, $accion, $opcion_actual);

if($accion=="CONSULTARUSUARIOS"){
    $usuarios->consultarUsuariosJson();
}

?>
