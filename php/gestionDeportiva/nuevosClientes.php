<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once("../include/index.php"); 
require_once ("../../model/session.php");
require_once ("../../model/Suscriptores.php");

/* Controla el acceso a usuarios externos no logueados */
$session = new SessionApp();
$usuario = $session->isRegisterUserJson(false);

$datos = $_REQUEST;

$accion= $datos["accion"];
$fecha_ingreso=$datos["fecha_ingreso"];
$codigo_suscriptor=$datos["codigo"];
$estado=$datos["estado"];
$parentesco=$datos["parentesco"];
$tipo_documento=$datos["tipo_documento"];
$numero_documento=$datos["numero_documento"];
$nombres=$datos["nombres"];
$apellidos=$datos["apellidos"];
$telefono=$datos["telefono"];
$celular=$datos["celular"];
$email=$datos["email"];
$parentesco2=$datos["parentesco2"];
$tipo_documento2=$datos["tipo_documento2"];
$numero_documento2=$datos["numero_documento2"];
$nombres2=$datos["nombres2"];
$apellidos2=$datos["apellidos2"];
$celular2=$datos["celular2"];
$email2=$datos["email2"];




/*TODO Operaciones con las variables POST O GET*/
//crear clase Roles
$suscriptor = new Suscriptores($usuario, $accion, 13);
if($accion == "GUARDAR"){
    $rs = $suscriptor->guardarSuscriptor($fecha_ingreso,$estado,$parentesco,$tipo_documento,$numero_documento,$nombres,$apellidos,$telefono,$celular,$email,$parentesco2,$tipo_documento2,$numero_documento2,$nombres2,$apellidos2,$celular2,$email2);    
    echo json_encode($rs);
    exit();
}

if($accion =="ACTUALIZAR"){
    $rs = $suscriptor->actualizarSuscriptor($codigo_suscriptor,$fecha_ingreso,$estado,$parentesco,$tipo_documento,
                                         $numero_documento,$nombres,$apellidos,$telefono,$celular,
                                         $email,$parentesco2,$tipo_documento2,$numero_documento2,
                                         $nombres2,$apellidos2,$celular2,$email2);
    echo json_encode($rs);
    exit(); 
}



?>