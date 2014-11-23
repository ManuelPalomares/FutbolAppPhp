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
    
    public function agendar($evento,$codigoAgregar,$tipoAgenda,$json =false){
        
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        
        if($tipoAgenda =="J"){
            $sql = "insert into agendados_eventos(evento,jugador,suscriptor) values($evento,$codigoAgregar,null)";
        }
        
        $rs = $db->Execute($sql);
        
        if ($rs == false) {
            $res["success"] = true;
            $res["msg"] = "Error almacenando el registro " . $db->ErrorMsg();
            $res["error"] = true;
            
        }else{
            $res["success"] = true;
            $res["msg"] = "Se realizo el agendamiento conrrectamente";
        }
       
        if($json== true){
                echo json_encode($res);
                exit();
                
        }
        
        return $res;
    }
    
    public function consultarAgendados($start= 0,$end=0,$codigo_citaDeportiva){
        $res = null;
        
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        
        $sql1 = "SELECT count(1) cantidad from vw_agendadoseventos where evento ='$codigo_citaDeportiva'";

        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        $rs = $db->Execute($sql1);
        $totalRows = $rs->getrows();
        $totalRows = $totalRows[0]['cantidad'];
               
        
        $sql2 = "SELECT * from vw_agendadoseventos where evento ='$codigo_citaDeportiva' LIMIT $start,$end;";
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        $rs = $db->Execute($sql2);
        $datos = $rs->getrows();
        
        return array("datos" => $datos, "totalRows" => $totalRows);
        
    }
    
    
    public function consultarAgendadosJson($start=0,$end =0,$codigo_cita){
        $result = null;
        $resultSet = $this->consultarAgendados($start, $end,$codigo_cita);
        
        $result["agendados"] = utf8Array::Utf8_string_array_encode($resultSet["datos"]);
        $result["totalRows"] = $resultSet["totalRows"];
        echo json_encode($result);
    }
}
