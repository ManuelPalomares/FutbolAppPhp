<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once("../include/index.php"); 
require_once ("../../model/session.php");
require_once ("../../model/Agendamiento.php");
require_once ("../../model/Jugadores.php");

/* Controla el acceso a usuarios externos no logueados */
$session = new SessionApp();
$usuario = $session->isRegisterUserJson(false);
$opcion_actual = $session->getOpcionActual();

$datos = $_REQUEST;

$accion = $datos["accion"];
$tipoAgenda = $datos["tipoAgenda"];
$codigoAgregar = $datos["codigoAgregar"];

$start = $datos["start"];
$limit = $datos["limit"];
$codigo_cita = $datos["codigo_cita"];




/*TODO Operaciones con las variables POST O GET*/

$agendamiento = new Agendamiento($usuario, $accion, $opcion_actual);

$Jugadores = new Jugadores($usuario, $accion, $opcion_actual);

if($accion =="AGENDARCITA"){
    //Se agenda por jugador
    if($tipoAgenda == 'J'){
        $agendamiento->agendar($codigo_cita,$codigoAgregar,$tipoAgenda,true);
    }
    
    if($tipoAgenda =='C'){
        $categoria = $datos["codigoAgregar"];
        $jugadoresDatos = $Jugadores->consultarTodosJugadoresxCategoria($categoria);
        
        for($i =0 ; $jugadoresDatos < sizeof($jugadoresDatos); $i++ ){
            $agendamiento->agendar($codigo_cita,$codigoAgregar,$tipoAgenda,true);
            $codigoJugadores  = $jugadoresDatos[$i]["codigo"];
            $res = $agendamiento->agendar($codigo_cita, $codigoJugadores,'J',false);
        }
    }
    
}

if($accion == "CONSULTARAGENDADOS"){
    $agendamiento->consultarAgendadosJson($start,$limit,$codigo_cita);
}

?>