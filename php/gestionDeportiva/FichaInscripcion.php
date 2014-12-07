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
$seguridad_social=$datos["seguridad_social"];
$codigo_categoria=$datos["codigo_categoria"];
$codigo_suscriptor=$datos["codigo_suscriptor"];
$observaciones=$datos["observaciones"];
$foto = $datos["foto"];
$inscripcion = $datos["inscripcion"];
$mensualidad = $datos["mensualidad"];
$transporte = $datos["transporte"];
$exp_deportiva = $datos["exp_deportiva"];
$jornada_colegio = $datos["jornada_colegio"];
$referido = $datos["referido"];
$responsable = $datos["responsable"];
$nombre_madre = $datos["nombre_madre"];
$celular_madre = $datos["celular_madre"];
$email_madre = $datos["email_madre"];
$ocupacion_madre = $datos["ocupacion_madre"];
$empresa_madre = $datos["empresa_madre"];
$nombre_padre = $datos["nombre_padre"];
$celular_padre = $datos["celular_padre"];
$email_padre = $datos["email_padre"];
$ocupacion_padre = $datos["ocupacion_padre"];
$empresa_padre = $datos["empresa_padre"];
$imagen_jugador = $_FILES["imagenJugador"];

//para el paginador Extjs
$start       = $datos["start"];
$end         = $datos["limit"];

//parametros de la grilla
$categoria = $datos["categoria"];

$query  = $datos["query"];

/*TODO Operaciones con las variables POST O GET*/
//crear clase Roles
$jugador = new Jugadores($usuario, $accion, $opcion_actual);
if($accion == "GUARDAR"){
    $rs = $jugador->guardarJugador($fecha_ingreso,$estado,$tipo_documento,$doc_identidad,$fecha_expedicion,$nombres,$apellidos,$fecha_nacimiento,$codigo_lugar_nacimiento,$tipo_sangre,$direccion,$barrio,$telefono,$celular,$email,$bb_pin,$colegio,$grado,$genero,$seguridad_social,$codigo_categoria,$codigo_suscriptor,$observaciones,$foto,$inscripcion, $mensualidad,$transporte, $exp_deportiva,$jornada_colegio,$referido,$responsable,$nombre_madre,$celular_madre,$email_madre,$ocupacion_madre,$empresa_madre,$nombre_padre,$celular_padre,$email_padre,$ocupacion_padre,$empresa_padre);    
    echo json_encode($rs);
    exit();
}

if($accion =="ACTUALIZAR"){
                    
    $rs = $jugador->actualizarJugador($codigo,$fecha_ingreso,$estado,$tipo_documento,$doc_identidad,$fecha_expedicion,$nombres,$apellidos,$fecha_nacimiento,$codigo_lugar_nacimiento,$tipo_sangre,$direccion,$barrio,$telefono,$celular,$email,$bb_pin,$colegio,$grado,$genero,$seguridad_social,$codigo_categoria,$codigo_suscriptor,$observaciones,$foto,$inscripcion, $mensualidad,$transporte, $exp_deportiva,$jornada_colegio,$referido,$responsable,$nombre_madre,$celular_madre,$email_madre,$ocupacion_madre,$empresa_madre,$nombre_padre,$celular_padre,$email_padre,$ocupacion_padre,$empresa_padre);
    echo json_encode($rs);
    exit(); 
}

if($accion == 'CONSULTAR'){
       $jugador->consultarJugadoresJson($start,$end,$categoria,$query);
}

if($accion=='CARGARFOTO'){
    $jugador->cargarFoto($imagen_jugador);
    
}


?>

