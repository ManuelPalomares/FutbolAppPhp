<?php

//lIBRERIA PHP DE CONEXION A LA BASE DE DATOS
require_once(dirname(dirname(__FILE__)) . "/libs/conexionBD.php");
//LIBRERIA PARA VALIDACION DE PERMISOS
require_once(dirname(__FILE__) . "/permisos.php");

require_once(dirname(dirname(__FILE__)) . "/libs/utf8Array.php");

define('ADODB_FETCH_ASSOC', 2);

class Evaluacion {
    //put your code here
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
    
    public function consultarEvaluacionPreguntas($codigoEvaluacion){
        
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        
        $sql = "select a.codigo,".
            " a.codigo_padre,"
            . " a.descripcion_detalle,"
            . " a.tipo,"
            . " b.respuesta_si_no,"
            . " b.respuesta_texto"
            . " from aspectos_evaluacion a"
            . " LEFT JOIN detalle_evaluacion b on b.codigo_detalle_aspecto = a.codigo"
            ." LEFT JOIN evaluacion c on c.codigo = b.codigo_evaluacion and c.codigo = $codigoEvaluacion";
        
         //echo $sql;
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        
        $rs = $db->Execute($sql);
        $res = $rs->getrows();
        return $res;
    }
    
    
}
