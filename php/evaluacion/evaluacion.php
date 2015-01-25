<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once("../include/index.php"); 
require_once ("../../model/session.php");

require_once ("../../model/Evaluacion.php");


/* Controla el acceso a usuarios externos no logueados */
$session = new SessionApp();
$usuario = $session->isRegisterUserJson(false);
$opcion_actual = $session->getOpcionActual();
$datos = $_REQUEST;

$accion= $datos["accion"];
$idPadre = $datos["node"];
$evaluacionID =$datos["evaluacionID"];
$evaluacionID = 0;

//para el paginador Extjs
$start       = $datos["start"];
$end         = $datos["limit"];



/*TODO Operaciones con las variables POST O GET*/
//crear clase Roles
$evaluacion = new Evaluacion($usuario, $accion, $opcion_actual);
if($accion == "CONSULTAREVALUACION"){
    $evaluacion->consultarEvaluacionPreguntasJson($evaluacionID,$idPadre);
}


if($accion == "GUARDAREVALUACION"){
    $evaluacion->guardarEvaluacion($evaluacionID,$idPadre);
}
?>

