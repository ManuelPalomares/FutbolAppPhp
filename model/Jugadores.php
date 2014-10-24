<?php

//lIBRERIA PHP DE CONEXION A LA BASE DE DATOS
require_once(dirname(dirname(__FILE__)) . "/libs/conexionBD.php");
//LIBRERIA PARA VALIDACION DE PERMISOS
require_once(dirname(__FILE__) . "/permisos.php");

require_once(dirname(dirname(__FILE__)) . "/libs/utf8Array.php");

define('ADODB_FETCH_ASSOC', 2);

class Jugadores {
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

    public function guardarJugador($fecha_ingreso, $estado, $tipo_documento, $doc_identidad, $fecha_expedicion, $nombres, $apellidos, $fecha_nacimiento, $codigo_lugar_nacimiento, $tipo_sangre, $direccion, $barrio, $telefono, $celular, $email, $bb_pin, $colegio, $grado, $genero, $seguridad_social, $codigo_categoria, $codigo_suscriptor) {
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $sql = "insert into jugadores (codigo,fecha_ingreso,estado,tipo_documento,doc_identidad,fecha_expedicion,nombres,apellidos,fecha_nacimiento,codigo_lugar_nacimiento,tipo_sangre,direccion,barrio,telefono,celular,email,bb_pin,colegio,grado,genero,seguridad_social,codigo_categoria,codigo_suscriptor) values (null,SYSDATE(),'$estado','$tipo_documento','$doc_identidad','$fecha_expedicion','$nombres','$apellidos','$fecha_nacimiento','$codigo_lugar_nacimiento','$tipo_sangre','$direccion','$barrio','$telefono','$celular','$email','$bb_pin','$colegio','$grado','$genero','$seguridad_social','$codigo_categoria','$codigo_suscriptor')";
        echo $sql;
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

    public function consultarJugadoresJson($start,$end) {
        $result = null;
        $result["jugadores"] = utf8Array::Utf8_string_array_encode($this->consultarJugadores($start,$end));
        $result["totalRows"] = sizeof($result["jugadores"]);
        echo json_encode($result);
    }

    public function consultarJugadores($start=0,$end=50) {
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql = "SELECT j.*,CONCAT(j.nombres,' ',j.apellidos) nombre_completo FROM jugadores j LIMIT $start,$end;";
        //echo $sql;
        
        $db->SetFetchMode(ADODB_FETCH_ASSOC);

        $rs = $db->Execute($sql);
        $res = $rs->getrows();
        return $res;
    }

    /*
      public function actualizarSuscriptor($codigo, $fecha_ingreso, $suscriptor, $estado, $parentesco, $tipo_documento, $numero_documento, $nombres, $apellidos, $telefono, $celular, $email, $parentesco2, $tipo_documento2, $numero_documento2, $nombres2, $apellidos2, $celular2, $email2) {
      $res = null;
      $con = new conexionBD();
      $db = $con->getConexDB();
      $rs = $db->Execute("UPDATE suscriptor set estado = '$estado', parentesco = '$parentesco', tipo_documento = '$tipo_documento', numero_documento = '$numero_documento', nombres = '$nombres', apellidos = '$apellidos', apellidos = '$telefono', celular = '$celular', email = '$email',  parentesco = '$parentesco2', tipo_documento2 = '$tipo_documento2', numero_documento2 = '$numero_documento2', nombres2 = '$nombres2', apellidos2 = '$apellidos2', celular2 = '$celular2', email = '$email2' where codigo=$codigo");

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