<?php

//lIBRERIA PHP DE CONEXION A LA BASE DE DATOS
require_once(dirname(dirname(__FILE__)) . "/libs/conexionBD.php");
//LIBRERIA PARA VALIDACION DE PERMISOS
require_once(dirname(__FILE__) . "/permisos.php");
define('ADODB_FETCH_ASSOC', 2);

//librerias model
require_once(dirname(__FILE__) . "/Jugadores.php");

require_once(dirname(dirname(__FILE__)) . "/libs/utf8Array.php");

class Agendamiento {
    /* En todas las clases PHP de maestros vamos a dejar el siguiente codigo */

    public function __construct($usuario, $accion, $opcion) {
        $res = null;
        //permiso
        $permisos = new PermisosApp();

        $usuarioData = $permisos->usuarioExiste($usuario);

        //valido si existe el usuario
        if (!isset($usuarioData[0]["NOMBRE"])) {
            $res["success"] = true;
            $res["mensaje_error"] = "Usuario invalido o no existe";
            echo json_encode($res);
            exit();
        }

        $permiso = $permisos->validarPermisoSistema($usuario, $accion, $opcion);
        if ($permiso == false) {
            $res["success"] = true;
            $res["permiso"] = false;
            $res["mensaje_error"] = "No tiene permiso para ejecutar la opcion o acceso por favor comunicar con su administrador";
            echo json_encode($res);
            exit();
        }
    }
    
    public function agendar($evento,$codigoAgregar,$tipoAsgenda){
        if($tipoAgenda =="J"){
            $sql = "insert into agendados_eventos(evento,jugador,suscriptor) values($evento,$codigoAgregar,null)";
        }
        
        if($tipoAgenda =="S"){
            $sql = "insert into agendados_eventos(evento,jugador,suscriptor) values($evento,null,$codigoAgregar)";
        }
        
        $rs = $db->Execute($sql);
        if ($rs == false) {
            $res["success"] = true;
            $res["msg"] = "Error almacenando el registro " . $db->ErrorMsg();
            return $res;
        }
    }
}
