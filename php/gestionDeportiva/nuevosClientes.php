<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once("../include/index.php");
require_once ("../../model/session.php");
require_once ("../../model/Jugadores.php");
require_once ("../../model/Citas.php");
require_once ("../../model/Categorias.php");
require_once ("../../model/Entrenadores.php");
require_once ("../../model/usuarios.php");
require_once ("../../model/Horarios.php");
require_once ("../../model/Agendamiento.php");


/* Controla el acceso a usuarios externos no logueados */
$session = new SessionApp();
$usuario = $session->isRegisterUserJson(false);
$opcion_actual = $session->getOpcionActual();

$datos = $_REQUEST;
$accion = $datos["accion"];
$codigo_jugador = $datos["codigo_jugador"];
$nombres = $datos["nombres"];
$apellidos = $datos["apellidos"];
$fecha_nacimiento = $datos["fecha_nacimiento"];
$email = $datos["email"];
$cod_categoria = $datos["categoria"];
$fecha_evaluacion = $datos["fecha_evaluacion"];
$cod_entrenador = $datos["cod_entrenador"];
$hora = $datos["hora"];

$v_inscripcion = $datos["inscripcion"];
$v_mensualidad = $datos["mensualidad"];
$v_transporte = $datos["transporte"];
$usuario_atencion = $datos["usuario_atencion"];
$estado = "P";

$fecha_inicio = $datos["fecha_evaluacion"] . " " . $hora;
$fecha_fin = $datos["fecha_evaluacion"] . " " . '00:00';

//codigo de cita
$codigo_cita = $datos["codigo_cita"];

/* TODO Operaciones con las variables POST O GET */
//crear clase Roles
$jugador = new Jugadores($usuario, $accion, $opcion_actual);
if ($accion == "GUARDAR") {
    $res1 = $jugador->guardarJugador("", $estado, "", "", "", $nombres, $apellidos, $fecha_nacimiento, "-1", "", "", "", "", "", $email, "", "", "", "", "", $cod_categoria, "", "", "", $usuario_atencion, $v_inscripcion, $v_mensualidad, $v_transporte);
    $newIDJugador = $res1["newId"];

    if (isset($res1["error"])) {
        echo json_encode($res1);
        exit();
    }

    //crear cita deportiva
    $cita = new CitasDeportivas($usuario, $accion, $opcion_actual);
    $html_informacion = file_get_contents("../../plantillas/plantilla_correo_fotmato_informacion.html");
    $html_informacion = str_replace("{fecha}", Date('Y/m/d'), $html_informacion);
    $html_informacion = str_replace("{fechanac}", $fecha_nacimiento, $html_informacion);
    $html_informacion = str_replace("{nombres}", $nombres . " " . $apellidos, $html_informacion);

    //consulta descripcion de la categoria
    $categoria = new Categorias($usuario, $accion, $opcion_actual);
    $des_categoria = $categoria->consultarCategorias($cod_categoria);

    $html_informacion = str_replace("{categoria}", $des_categoria[0]["descripcion"], $html_informacion);
    $html_informacion = str_replace("{fechaeva}", $fecha_evaluacion, $html_informacion);

    //consulta entrenador
    $entrenador = new Entrenadores($usuario, $accion, $opcion_actual);
    $des_entrenador = $entrenador->consultarEntrenadoresListaValores("", $cod_entrenador);

    $html_informacion = str_replace("{entrenador}", $des_entrenador[0]["nombrescompletos"], $html_informacion);

    $html_informacion = str_replace("{inscripcion}", $v_inscripcion, $html_informacion);
    $html_informacion = str_replace("{mensualidad}", $v_mensualidad, $html_informacion);
    $html_informacion = str_replace("{transporte}", $v_transporte, $html_informacion);

    // consulta usuario
    $usuarios = new Usuarios($usuario, $accion, $opcion_actual);
    $des_usuario = $usuarios->consultarUsuarioPorCodigo($usuario_atencion);

    $html_informacion = str_replace("{usuario}", $des_usuario[0]["nombre"], $html_informacion);
    
    //consultaHorariosCategorias
    $horarios = new Horarios($usuario, $accion, $opcion_actual);
    $dias = $horarios->consultarHorariosPorCategoria($cod_categoria);
    
    
    for($i= 0 ;    $i < sizeof($dias); $i++){
        $html_informacion = str_replace("{".$dias[$i]["dia"]."}", "X", $html_informacion);
    }
    $html_informacion = str_replace("{LUNES}", "", $html_informacion);
    $html_informacion = str_replace("{MARTES}", "", $html_informacion);
    $html_informacion = str_replace("{MIERCOLES}", "", $html_informacion);
    $html_informacion = str_replace("{JUEVES}", "", $html_informacion);
    $html_informacion = str_replace("{VIERNES}", "", $html_informacion);
    $html_informacion = str_replace("{SABADO}", "", $html_informacion);
    $html_informacion = str_replace("{DOMINGO}", "", $html_informacion);
    
    
    $rs = $cita->guardarCita("Cita deportiva para jugadores nuevos", $fecha_inicio, $fecha_fin, "A", $html_informacion);
    $id_NewCita = $rs["newId"];
    
    if (isset($rs["error"])) {
        echo json_encode($rs);
        exit();
    }
      
    //Agendar Nuevo jugador
    $agendamiento = new Agendamiento($usuario, $accion, $opcion_actual);
    $rs = $agendamiento->agendar($id_NewCita, $newIDJugador,'J');
    
    if (isset($rs["error"])) {
        echo json_encode($rs);
        exit();
    }
    
    
    //Enviar Correo de informacion
    $resd = $cita->enviarEmailCita($id_NewCita,false);
    
    if (isset($resd["error"])) {
        echo json_encode($resd);
        exit();
    }
    
    
    $result["sucess"] = true;
    $result["msg"] = "Se envia la informacion del nuevo jugador y se ingresa nuevo cliente en el sitema para prueba deportiva";
    $result["newId"]= $newIDJugador;
    $result["codigoCita"] = $id_NewCita;
    echo json_encode($res);
    exit();
    
}

if ($accion == "ACTUALIZAR") {
    $rs = $jugador->actualizarJugador($codigo_jugador, $fecha_ingreso, "P", "", "", "", $nombres, $apellidos, $fecha_nacimiento, "-1", "", "", "", "", "", $email, "", "", "", "", "", $cod_categoria, "", $observaciones, "", $usuario_atencion, $v_inscripcion, $v_mensualidad, $v_transporte);
    
    if($rs["error"]){
        echo json_encode($rs);
        exit();
    }
    $newIDJugador= $rs["newId"];
    
    //crear cita deportiva
    $cita = new CitasDeportivas($usuario, $accion, $opcion_actual);
    //Actualiza plantilla
    $html_informacion = file_get_contents("../../plantillas/plantilla_correo_fotmato_informacion.html");
    $html_informacion = str_replace("{fecha}", Date('Y/m/d'), $html_informacion);
    $html_informacion = str_replace("{fechanac}", $fecha_nacimiento, $html_informacion);
    $html_informacion = str_replace("{nombres}", $nombres . " " . $apellidos, $html_informacion);

    //consulta descripcion de la categoria
    $categoria = new Categorias($usuario, $accion, $opcion_actual);
    $des_categoria = $categoria->consultarCategorias($cod_categoria);

    $html_informacion = str_replace("{categoria}", $des_categoria[0]["descripcion"], $html_informacion);
    $html_informacion = str_replace("{fechaeva}", $fecha_evaluacion, $html_informacion);

    //consulta entrenador
    $entrenador = new Entrenadores($usuario, $accion, $opcion_actual);
    $des_entrenador = $entrenador->consultarEntrenadoresListaValores("", $cod_entrenador);

    $html_informacion = str_replace("{entrenador}", $des_entrenador[0]["nombrescompletos"], $html_informacion);

    $html_informacion = str_replace("{inscripcion}", $v_inscripcion, $html_informacion);
    $html_informacion = str_replace("{mensualidad}", $v_mensualidad, $html_informacion);
    $html_informacion = str_replace("{transporte}", $v_transporte, $html_informacion);

    // consulta usuario
    $usuarios = new Usuarios($usuario, $accion, $opcion_actual);
    $des_usuario = $usuarios->consultarUsuarioPorCodigo($usuario_atencion);

    $html_informacion = str_replace("{usuario}", $des_usuario[0]["nombre"], $html_informacion);
    
    //consultaHorariosCategorias
    $horarios = new Horarios($usuario, $accion, $opcion_actual);
    $dias = $horarios->consultarHorariosPorCategoria($cod_categoria);
    
    
    for($i= 0 ;    $i < sizeof($dias); $i++){
        $html_informacion = str_replace("{".$dias[$i]["dia"]."}", "X", $html_informacion);
    }
    $html_informacion = str_replace("{LUNES}", "", $html_informacion);
    $html_informacion = str_replace("{MARTES}", "", $html_informacion);
    $html_informacion = str_replace("{MIERCOLES}", "", $html_informacion);
    $html_informacion = str_replace("{JUEVES}", "", $html_informacion);
    $html_informacion = str_replace("{VIERNES}", "", $html_informacion);
    $html_informacion = str_replace("{SABADO}", "", $html_informacion);
    $html_informacion = str_replace("{DOMINGO}", "", $html_informacion);
    
    //Actualiza la  plantilla
    $rs = $cita->updateCita($codigo_cita,"Cita deportiva para jugadores nuevos", $fecha_inicio, $fecha_fin, "A", $html_informacion);
    
    
    if (isset($rs["error"])) {
        echo json_encode($rs);
        exit();
    }
    
    //Enviar Correo de informacion
    $resd = $cita->enviarEmailCita($rs["newId"],false);
    if (isset($resd["error"])) {
        echo json_encode($resd);
        exit();
    }
    

    $result["sucess"] = true;
    $result["msg"] = "Se envia la informacion del nuevo jugador y se ingresa nuevo cliente en el sitema para prueba deportiva";
    $result["newId"]= $newIDJugador;
    echo json_encode($result);
    exit();
}
?>