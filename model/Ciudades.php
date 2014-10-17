<?php


//lIBRERIA PHP DE CONEXION A LA BASE DE DATOS
require_once(dirname(dirname(__FILE__)) . "/libs/conexionBD.php");
//LIBRERIA PARA VALIDACION DE PERMISOS
require_once(dirname(__FILE__) . "/permisos.php");

require_once(dirname(dirname(__FILE__)) . "/libs/utf8Array.php");

define('ADODB_FETCH_ASSOC',2);

class Ciudades {
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

   
    public function consultarCiudades() {
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql = "SELECT codigo,CONCAT(descripcion,' (',departamento,')') descripcion FROM ciudades ORDER BY DESCRIPCION ASC;";
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        
        $rs = $db->Execute($sql);
        $res = $rs->getrows();
        return $res;
    }

    public function consultarCiudadesJson() {
        $result = null;
        $result["ciudades"] = utf8Array::Utf8_string_array_encode($this->consultarCiudades());
        $result["totalRows"] = sizeof($result["ciudades"]);
        echo json_encode($result);
    }
    
    

}

?>