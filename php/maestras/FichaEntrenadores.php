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
$opcion_actual = $session->getOpcionActual();
$datos = $_REQUEST;

$accion= $datos["accion"];
$codigo=$datos["codigo"];
$estado=$datos["estado"];
$tipo_documento=$datos["tipo_documento"];
$documento_identidad=$datos["documento_identidad"];
$nombres=$datos["nombres"];
$apellidos=$datos["apellidos"];
$fecha_nacimiento=$datos["fecha_nacimiento"];
$direccion=$datos["direccion"];
$barrio=$datos["barrio"];
$genero=$datos["genero"];
$telefono=$datos["telefono"];
$celular=$datos["celular"];
$email=$datos["email"];
//$fecha_ingreso=$datos["fecha_ingreso"];
//$observaciones=$datos["observaciones"];
//$foto = $datos["foto"];
//$fecha_expedicion=$datos["fecha_expedicion"];
//$codigo_lugar_nacimiento=$datos["codigo_lugar_nacimiento"];
//$tipo_sangre=$datos["tipo_sangre"];
//$bb_pin=$datos["bb_pin"];
//$colegio=$datos["colegio"];
//$grado=$datos["grado"];
//$seguridad_social=$datos["email"];
//$codigo_categoria=$datos["codigo_categoria"];
//$codigo_suscriptor=$datos["codigo_suscriptor"];
//$imagen_entrenador = $_FILES["imagenEntrenador"];

//para el paginador Extjs
$start       = $datos["start"];
$end         = $datos["limit"];

//parametros de la grilla
$categoria = $datos["categoria"];

$query  = $datos["query"];

/*TODO Operaciones con las variables POST O GET*/
//crear clase Roles
$entrenador = new Entrenadores($usuario, $accion, $opcion_actual);
if($accion == "GUARDAR"){
    $rs = $entrenador->guardarEntrenador($estado,$tipo_documento,$documento_identidad,$nombres,$apellidos,$fecha_nacimiento,$direccion,$barrio,$telefono,$celular,$email,$genero);    
    echo json_encode($rs);
    exit();
}

if($accion =="ACTUALIZAR"){
                    
    $rs = $entrenador->actualizarEntrenador($codigo, $estado,$tipo_documento,$documento_identidad,$nombres,$apellidos,$fecha_nacimiento,$direccion,$barrio,$telefono,$celular,$email,$genero);
    echo json_encode($rs);
    exit(); 
}

if($accion == 'CONSULTARENTRENADORESGRILLA'){
       $entrenador->consultarEntrenadoresJson($start,$end,$categoria,$query);
}

?>

