<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once("../include/index.php"); 
require_once ("../../model/session.php");
require_once ("../../model/Agendamiento.php");
require_once ("../../model/Jugadores.php");
require_once ("../../model/Citas.php");

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
$jugadorEliminar = $datos["jugadorEliminar"];





/*TODO Operaciones con las variables POST O GET*/

$agendamiento = new Agendamiento($usuario, $accion, $opcion_actual);

$Jugadores = new Jugadores($usuario, $accion, $opcion_actual);

$citasDeportivas = new CitasDeportivas($usuario, $accion, $opcion_actual);


if($accion =="AGENDARCITA"){
    //Se agenda por jugador
    if($tipoAgenda == 'J'){
        $agendamiento->agendar($codigo_cita,$codigoAgregar,$tipoAgenda,true);
    }
    
    if($tipoAgenda =='C'){
        $categoria = $datos["codigoAgregar"];
        
        $jugadoresDatos = $Jugadores->consultarTodosJugadoresxCategoria($categoria);
        
        for($i =0 ; $i < sizeof($jugadoresDatos); $i++ ){
            $codigoJugador  = $jugadoresDatos[$i]["codigo"];
            if($jugadoresDatos[$i]["email"] !== ""){
                $res = $agendamiento->agendar($codigo_cita, $codigoJugador,'J',false);
                if($res["error"]== true){
                    echo json_encode($res);
                    exit();
                }
            }
        }
        
        $res["success"] = true;
        $res["msg"] = "Se realizo el agendamiento por categoria por favor validar";
        echo json_encode($res);
        exit();
        
    }
    
}

if($accion == "CONSULTARAGENDADOS"){
    $agendamiento->consultarAgendadosJson($start,$limit,$codigo_cita);
}

if($accion == "ELIMINARAGENDA"){
    $agendamiento->eliminardeAgenda($codigo_cita,$jugadorEliminar,true);
    
}

if($accion == "ENVIARMAILCITA"){
    $citasDeportivas->enviarEmailCita($codigo_cita);
}

?>