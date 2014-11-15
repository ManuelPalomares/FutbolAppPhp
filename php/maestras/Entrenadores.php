<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once("../include/index.php"); 
require_once ("../../model/session.php");
require_once ("../../model/Entrenadores.php");

/* Controla el acceso a usuarios externos no logueados */
$session = new SessionApp();
$usuario = $session->isRegisterUserJson(false);

$datos = $_REQUEST;

$accion= $datos["accion"];
$codigo = $datos["codigo"];
$descripcion = $datos["nombrescompletos"];
$query       = $datos["query"];
$opcion_actual = $session->getOpcionActual();

/*TODO Operaciones con las variables POST O GET*/
//crear clase Roles
$entrenadores = new Entrenadores($usuario, $accion, $opcion_actual);

if($accion=="CONSULTARENTRENADORES"){
    $entrenadores->consultarEntrenadoresListaValoresJson($query);
}

?>
