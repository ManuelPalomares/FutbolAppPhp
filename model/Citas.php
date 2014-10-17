<?php


//lIBRERIA PHP DE CONEXION A LA BASE DE DATOS
require_once(dirname(dirname(__FILE__)) . "/libs/conexionBD.php");
//LIBRERIA PARA VALIDACION DE PERMISOS
require_once(dirname(__FILE__) . "/permisos.php");

require_once(dirname(dirname(__FILE__)) . "/libs/utf8Array.php");

define('ADODB_FETCH_ASSOC',2);

class CitasDeportivas{
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

    public function guardarCita($titulo_evento,$fecha_inicio,$fecha_fin,$estado_evento,$descripcion_evento) {
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $rs = $db->Execute("insert into eventos_deportivos values(null,'$titulo_evento','$fecha_inicio','$fecha_fin','$descripcion_evento','$estado_evento')");
        $id = $db->Insert_ID();
        
        if($rs ==false){
            $res["success"] = true;
            $res["msg"] = "Error almacenando el registro ".$db->ErrorMsg();
            return $res;
        }
        $res["success"] = true;
        $res["msg"] = "Se guardo el registro correctamente";
        $res["newId"] = $id;
        return($res);
    }
   
    public function updateCita($codigo,$titulo_evento,$fecha_inicio,$fecha_fin,$estado_evento,$descripcion_evento){
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $rs = $db->Execute("UPDATE eventos_deportivos set titulo_evento = '$titulo_evento',"
                . "fecha_inicio ='$fecha_inicio',"
                . "fecha_fin = '$fecha_fin',"
                . "descripcion_evento = '$descripcion_evento'"
                . "estado_evento    = '$estado_evento'"
                . "where codigo=$codigo");
        
        if($rs ==false){
            $res["success"] = true;
            $res["msg"] = "Error actualizando el registro ".$db->ErrorMsg();
            $res["newId"] = $codigo;
            return $res;
        }
        $res["success"] = true;
        $res["msg"] = "Se actualizo el registro correctamente";
        $res["newId"] = $codigo;
        return($res);
    }
    
    public function eliminarRol($codigo){
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $sql = "delete from roles where codigo='$codigo';";
        //echo $sql;
        $rs = $db->Execute($sql);
        
        if($rs ==false){
            $res["success"] = true;
            $res["msg"] = "Error eliminando el registro ".$db->ErrorMsg();
            $res["newId"] = $codigo;
            return $res;
        }
         
        $res["success"] = true;
        $res["msg"] = "Se elimino el registro correctamente ";
        $res["newId"] = $codigo;
        return($res);
    }
    
    public function consultarCitasDeportivas($start=0,$end=50) {
        
        
        
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql = "SELECT codigo,titulo_evento,fecha_inicio,fecha_fin,descripcion_evento,estado FROM eventos_deportivos LIMIT $start, $end ;";
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        
        $rs = $db->Execute($sql);
        $res = $rs->getrows();
        return $res;
    }

    public function consultarRolesJson() {
        $result = null;
        $result["roles"] = utf8Array::Utf8_string_array_encode($this->consultarRoles());
        $result["totalRows"] = sizeof($result["roles"]);
        echo json_encode($result);
    }
    
    

}

?>