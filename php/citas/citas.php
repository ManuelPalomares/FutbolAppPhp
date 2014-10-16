<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once("../include/index.php"); 
require_once ("../../model/session.php");
require_once ("../../model/Citas.php");

/* Controla el acceso a usuarios externos no logueados */
$session = new SessionApp();
$usuario = $session->isRegisterUserJson(false);

$datos = $_REQUEST;

$accion= $datos["accion"];
$codigo = $datos["codigo"];
$titulo_evento = $datos["titulo_evento"];
$fecha_inicio = $datos["fecha_inicio"]." ".$datos["hora1"];
$fecha_fin = $datos["fecha_fin"]." ".$datos["hora2"];
$estado_evento = $datos["estado_evento"];
$descripcion_evento = isset($datos["descripcion_evento"]) ? html_entity_decode(htmlentities($datos["descripcion_evento"], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'ISO-8859-1') : "";



/*TODO Operaciones con las variables POST O GET*/
//crear clase Roles
$roles = new CitasDeportivas($usuario, $accion, 6);
if($accion == "GUARDAR"){
    $rs = $roles->guardarCita($titulo_evento,$fecha_inicio,$fecha_fin,$estado_evento,$descripcion_evento);
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

if($accion=="CONSULTAR"){
    $roles->consultarRolesJson();
}

?>