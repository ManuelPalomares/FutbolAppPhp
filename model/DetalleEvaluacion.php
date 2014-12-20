<?php

//lIBRERIA PHP DE CONEXION A LA BASE DE DATOS
require_once(dirname(dirname(__FILE__)) . "/libs/conexionBD.php");
//LIBRERIA PARA VALIDACION DE PERMISOS
require_once(dirname(__FILE__) . "/permisos.php");

require_once(dirname(dirname(__FILE__)) . "/libs/utf8Array.php");
require_once(dirname(dirname(__FILE__)) . "/libs/fileUpload.php");

define('ADODB_FETCH_ASSOC', 2);

class DetalleEvaluacion {
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

    public function guardarDetalleEvaluacion($codigo_evaluacion, $codigo_detalle_aspecto, $calificacion, $respuesta_si_no, $respuesta_texto) {
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $sql = "insert into detalle_evaluacion(codigo_evaluacion, codigo_detalle_aspecto, calificacion, respuesta_si_no, respuesta_texto) "
                . "values ($codigo_evaluacion, $codigo_detalle_aspecto, $calificacion, $respuesta_si_no, $respuesta_texto)";
        
        $rs = $db->Execute($sql);
        $id = $db->Insert_ID();


        if ($rs == false) {
            $res["success"] = true;
            $res["msg"] = "Error almacenando el registro " . $db->ErrorMsg();
            return $res;
        }
        $res["success"] = true;
        $res["msg"] = "Se guardo el registro correctamente";
        $res["newId"] = $id;
        return($res);
    }

    public function consultarDetalleEvaluacionJson($codigo_evaluacion) {
        $result = null;
        $res = $this->consultarDetalleEvaluacion($codigo_evaluacion);

        $result["detalleevaluacion"] = utf8Array::Utf8_string_array_encode($res["datos"]);
        $result["totalRows"] = $res["totalRows"];
        //print_r($result);

        echo json_encode($result);
    }

    public function consultarDetalleEvaluacion($codigo_evaluacion) {

        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $totalRows = null;
        $datos = null;

        $db->SetFetchMode(ADODB_FETCH_ASSOC);

        $sql = "SELECT codigo_evaluacion, "
                      . "codigo_detalle_aspecto, "
                      . "calificacion, "
                      . "respuesta_si_no, "
                      . "respuesta_texto "
                . "FROM detalle_evaluacion "
                . "WHERE codigo_evaluacion = $codigo_evaluacion ;";
        //echo $sql2;
        $db->SetFetchMode(ADODB_FETCH_ASSOC);

        $rs = $db->Execute($sql);
        $res = $rs->getrows();
        $datos = $res;
        return array("datos" => $datos, "totalRows" => $totalRows);
    }
    
    
    public function actualizarDetalleEvaluacion($codigo_evaluacion, $codigo_detalle_aspecto, $calificacion, $respuesta_si_no, $respuesta_texto) {
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $sql = "UPDATE detalle_evaluacion "
                . "set codigo_detalle_aspecto='$codigo_detalle_aspecto', "
                . "calificacion='$calificacion', "
                . "respuesta_si_no='$respuesta_si_no',"
                . "respuesta_texto='$respuesta_texto'"
                . " where codigo_evaluacion = '$codigo_evaluacion' and"
                . " codigo_detalle_aspecto = $codigo_detalle_aspecto";
        //echo $sql;
        $rs = $db->Execute($sql);

        if ($rs == false) {
            $res["success"] = true;
            $res["msg"] = "Error actualizando el registro " . $db->ErrorMsg();
            $res["newId"] = $codigo_evaluacion;
            $res["error"] = true;
            return $res;
        }
        $res["success"] = true;
        $res["msg"] = "Se actualizo el registro correctamente";
        //$res["newId"] = $codigo;
        return($res);
    }


}

?>