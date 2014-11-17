<?php

//lIBRERIA PHP DE CONEXION A LA BASE DE DATOS
require_once(dirname(dirname(__FILE__)) . "/libs/conexionBD.php");
//LIBRERIA PARA VALIDACION DE PERMISOS
require_once(dirname(__FILE__) . "/permisos.php");

require_once(dirname(dirname(__FILE__)) . "/libs/utf8Array.php");

define('ADODB_FETCH_ASSOC', 2);

class Entrenadores {
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
    
    public function consultarEntrenadoresListaValores($query="",$cod_entrenador="") {
        
        if($query !=""){
            $query = "and CONCAT(nombres,' ',apellidos,' (',documento_identidad,')') like '%$query%'";
        }
        
        if($cod_entrenador !=""){
             $query2 = "and codigo = '$cod_entrenador'";
        }
        
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql = "SELECT codigo,CONCAT(nombres,' ',apellidos,' (',documento_identidad,')') nombrescompletos FROM entrenadores where 1=1 $query $query2 ORDER BY NOMBRES ASC;";
        //echo $sql;
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        
        $rs = $db->Execute($sql);
        $res = $rs->getrows();
        return $res;
    }

    public function consultarEntrenadoresListaValoresJson($query="") {
        $result = null;
        $result["entrenadores"] = utf8Array::Utf8_string_array_encode($this->consultarEntrenadoresListaValores($query));
        $result["totalRows"] = sizeof($result["entrenadores"]);
        echo json_encode($result);
    }
    
    
/*
    public function guardarSuscriptor($fecha_ingreso, $estado, $parentesco, $tipo_documento, $numero_documento, $nombres, $apellidos, $telefono, $celular, $email, $parentesco2, $tipo_documento2, $numero_documento2, $nombres2, $apellidos2, $celular2, $email2) {
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $sql = "insert into suscriptores (codigo,fecha_ingreso,estado,parentesco,tipo_documento,numero_documento,nombres,apellidos,telefono,celular,email,parentesco2,tipo_documento2,numero_documento2,nombres2,apellidos2,celular2,email2) values (null,SYSDATE(),'$estado','$parentesco','$tipo_documento','$numero_documento','$nombres','$apellidos','$telefono','$celular','$email','$parentesco2','$tipo_documento2','$numero_documento2','$nombres2','$apellidos2','$celular2','$email2')";
        
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

    public function actualizarSuscriptor($codigo, $fecha_ingreso, $suscriptor, $estado, $parentesco, $tipo_documento, $numero_documento, $nombres, $apellidos, $telefono, $celular, $email, $parentesco2, $tipo_documento2, $numero_documento2, $nombres2, $apellidos2, $celular2, $email2) {
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $rs = $db->Execute("UPDATE suscriptor set estado = '$estado', parentesco = '$parentesco', tipo_documento = '$tipo_documento', numero_documento = '$numero_documento', nombres = '$nombres', apellidos = '$apellidos', apellidos = '$telefono', celular = '$celular', email = '$email',  parentesco2 = '$parentesco2', tipo_documento2 = '$tipo_documento2', numero_documento2 = '$numero_documento2', nombres2 = '$nombres2', apellidos2 = '$apellidos2', celular2 = '$celular2', email = '$email2' where codigo=$codigo");

        if ($rs == false) {
            $res["success"] = true;
            $res["msg"] = "Error actualizando el registro " . $db->ErrorMsg();
            $res["newId"] = $codigo;
            return $res;
        }
        $res["success"] = true;
        $res["msg"] = "Se actualizo el registro correctamente";
//        $res["newId"] = $codigo;
        return($res);
    }

    public function consultarSuscriptoresListaValores($query="") {
        
        if($query !=""){
            $query = "and CONCAT(nombres,' ',apellidos,' (',numero_documento,')') like '%$query%'";
        }
        
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql = "SELECT codigo,CONCAT(nombres,' ',apellidos,' (',numero_documento,')') nombrescompletos FROM suscriptores where 1=1 $query ORDER BY NOMBRES ASC;";
        //echo $sql;
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        
        $rs = $db->Execute($sql);
        $res = $rs->getrows();
        return $res;
    }

    public function consultarSuscriptoresListaValoresJson($query="") {
        $result = null;
        $result["suscriptores"] = utf8Array::Utf8_string_array_encode($this->consultarSuscriptoresListaValores($query));
        $result["totalRows"] = sizeof($result["suscriptores"]);
        echo json_encode($result);
    }

    
    
    public function eliminarRol($codigo) {
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $sql = "update suscriptores set estado = 'I' where codigo='$codigo';";
        //echo $sql;
        $rs = $db->Execute($sql);

        if ($rs == false) {
            $res["success"] = true;
            $res["msg"] = "Inactivando el sus eliminando el registro " . $db->ErrorMsg();
            $res["newId"] = $codigo;
            return $res;
        }

        $res["success"] = true;
        $res["msg"] = "Se elimino el registro correctamente ";
        $res["newId"] = $codigo;
        return($res);
    }

    public function consultarRoles() {
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql = "SELECT codigo,descripcion FROM roles;";
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
*/
}

?>
