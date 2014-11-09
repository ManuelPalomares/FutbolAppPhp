<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once("../include/index.php"); 
require_once ("../../model/session.php");
require_once ("../../model/Jugadores.php");

/* Controla el acceso a usuarios externos no logueados */
$session = new SessionApp();
$usuario = $session->isRegisterUserJson(false);
$opcion_actual = $session->getOpcionActual();
$datos = $_REQUEST;

$accion= $datos["accion"];
$fecha_ingreso=$datos["fecha_ingreso"];
$codigo=$datos["codigo"];
$estado=$datos["estado"];
$tipo_documento=$datos["tipo_documento"];
$doc_identidad=$datos["doc_identidad"];
$fecha_expedicion=$datos["fecha_expedicion"];
$nombres=$datos["nombres"];
$apellidos=$datos["apellidos"];
$fecha_nacimiento=$datos["fecha_nacimiento"];
$codigo_lugar_nacimiento=$datos["codigo_lugar_nacimiento"];
$tipo_sangre=$datos["tipo_sangre"];
$direccion=$datos["direccion"];
$barrio=$datos["barrio"];
$telefono=$datos["telefono"];
$celular=$datos["celular"];
$email=$datos["email"];
$bb_pin=$datos["bb_pin"];
$colegio=$datos["colegio"];
$grado=$datos["grado"];
$genero=$datos["genero"];
$seguridad_social=$datos["email"];
$codigo_categoria=$datos["codigo_categoria"];
$codigo_suscriptor=$datos["codigo_suscriptor"];
$observaciones=$datos["observaciones"];
$foto = $datos["foto"];
$imagen_jugador = $_FILES["imagenJugador"];

//para el paginador Extjs
$start       = $datos["start"];
$end         = $datos["limit"];

//parametros de la grilla
$categoria = $datos["categoria"];

/*TODO Operaciones con las variables POST O GET*/
//crear clase Roles
$jugador = new Jugadores($usuario, $accion, $opcion_actual);
if($accion == "GUARDAR"){
    $rs = $jugador->guardarJugador($fecha_ingreso,$estado,$tipo_documento,$doc_identidad,$fecha_expedicion,$nombres,$apellidos,$fecha_nacimiento,$codigo_lugar_nacimiento,$tipo_sangre,$direccion,$barrio,$telefono,$celular,$email,$bb_pin,$colegio,$grado,$genero,$seguridad_social,$codigo_categoria,$codigo_suscriptor,$observaciones,$foto);    
    echo json_encode($rs);
    exit();
}

if($accion =="ACTUALIZAR"){
                    
    $rs = $jugador->actualizarJugador($codigo,$fecha_ingreso,$estado,$tipo_documento,$doc_identidad,$fecha_expedicion,$nombres,$apellidos,$fecha_nacimiento,$codigo_lugar_nacimiento,$tipo_sangre,$direccion,$barrio,$telefono,$celular,$email,$bb_pin,$colegio,$grado,$genero,$seguridad_social,$codigo_categoria,$codigo_suscriptor,$observaciones,$foto);
    echo json_encode($rs);
    exit(); 
}

if($accion == 'CONSULTAR'){
       $jugador->consultarJugadoresJson($start,$end,$categoria);
}

if($accion=='CARGARFOTO'){
    $jugador->cargarFoto($imagen_jugador);
    
}


?>

