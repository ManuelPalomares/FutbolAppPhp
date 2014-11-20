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
$documento_identidad=$datos["documento_identidad"];
$tipo_documento=$datos["tipo_documento"];
$nombres=$datos["nombres"];
$apellidos=$datos["apellidos"];
$telefono=$datos["telefono"];
$celular=$datos["celular"];
$direccion=$datos["direccion"];
$barrio=$datos["barrio"];
$fecha_nacimiento=$datos["fecha_nacimiento"];
$estado=$datos["estado"];
$genero=$datos["genero"];
$fecha_ingreso=$datos["fecha_ingreso"];
$observaciones=$datos["observaciones"];
$foto = $datos["foto"];
$email=$datos["email"];
//$fecha_expedicion=$datos["fecha_expedicion"];
//$codigo_lugar_nacimiento=$datos["codigo_lugar_nacimiento"];
//$tipo_sangre=$datos["tipo_sangre"];
//$bb_pin=$datos["bb_pin"];
//$colegio=$datos["colegio"];
//$grado=$datos["grado"];
//$seguridad_social=$datos["email"];
//$codigo_categoria=$datos["codigo_categoria"];
//$codigo_suscriptor=$datos["codigo_suscriptor"];
$imagen_entrenador = $_FILES["imagenEntrenador"];

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
    $rs = $entrenador->guardarEntrenador($fecha_ingreso,$estado,$tipo_documento,$documento_identidad,$nombres,$apellidos,$fecha_nacimiento,$direccion,$barrio,$telefono,$celular,$email,$genero,$observaciones,$foto);    
    echo json_encode($rs);
    exit();
}

if($accion =="ACTUALIZAR"){
                    
    $rs = $jugador->actualizarEntrenador($codigo,$fecha_ingreso,$estado,$tipo_documento,$doc_identidad,$fecha_expedicion,$nombres,$apellidos,$fecha_nacimiento,$codigo_lugar_nacimiento,$tipo_sangre,$direccion,$barrio,$telefono,$celular,$email,$bb_pin,$colegio,$grado,$genero,$seguridad_social,$codigo_categoria,$codigo_suscriptor,$observaciones,$foto);
    echo json_encode($rs);
    exit(); 
}

if($accion == 'CONSULTAR'){
       $jugador->consultarEntrenadoresJson($start,$end,$categoria,$query);
}

if($accion=='CARGARFOTO'){
    $jugador->cargarFoto($imagen_jugador);
    
}


?>

