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

    public function guardarJugador($fecha_ingreso, $estado, $tipo_documento, $doc_identidad, $fecha_expedicion, $nombres, $apellidos, $fecha_nacimiento, $codigo_lugar_nacimiento, $tipo_sangre, $direccion, $barrio, $telefono, $celular, $email, $bb_pin, $colegio, $grado, $genero, $seguridad_social, $codigo_categoria, $codigo_suscriptor,$observaciones) {
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $sql = "insert into jugadores (codigo,fecha_ingreso,estado,tipo_documento,doc_identidad,fecha_expedicion,nombres,apellidos,fecha_nacimiento,codigo_lugar_nacimiento,tipo_sangre,direccion,barrio,telefono,celular,email,bb_pin,colegio,grado,genero,seguridad_social,codigo_categoria,codigo_suscriptor, observaciones) values (null,SYSDATE(),'$estado','$tipo_documento','$doc_identidad','$fecha_expedicion','$nombres','$apellidos','$fecha_nacimiento','$codigo_lugar_nacimiento','$tipo_sangre','$direccion','$barrio','$telefono','$celular','$email','$bb_pin','$colegio','$grado','$genero','$seguridad_social','$codigo_categoria','$codigo_suscriptor','$observaciones')";
        //echo $sql;
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

    public function consultarJugadoresJson($start,$end,$categoria=0) {
        $result = null;
        $res = $this->consultarJugadores($start,$end,$categoria);
        
        $result["jugadores"] = utf8Array::Utf8_string_array_encode($res["datos"]);
        $result["totalRows"] = $res["totalRows"];
        echo json_encode($result);
    }

    public function consultarJugadores($start=0,$end=50,$categoria=0) {
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $totalRows = null;
        $datos     =null;
        $sql1 = "SELECT count(1) cantidad FROM jugadores j where codigo_categoria=$categoria";
        $db->SetFetchMode(ADODB_FETCH_ASSOC);

        $rs = $db->Execute($sql1);
        $res = $rs->getrows();
        $totalRows = $res[0]["cantidad"];
        
        $sql2 = "SELECT j.*,CONCAT(j.nombres,' ',j.apellidos) nombre_completo FROM jugadores j where codigo_categoria=$categoria LIMIT $start,$end;";
                
        $db->SetFetchMode(ADODB_FETCH_ASSOC);

        $rs = $db->Execute($sql2);
        $res = $rs->getrows();
        $datos = $res;
        return array("datos"=>$datos,"totalRows"=>$totalRows);
    }

    
      public function actualizarJugador($codigo, $fecha_ingreso, $estado, $tipo_documento, $doc_identidad, $fecha_expedicion, $nombres, $apellidos, $fecha_nacimiento, $codigo_lugar_nacimiento, $tipo_sangre, $direccion, $barrio, $telefono, $celular, $email, $bb_pin, $colegio, $grado, $genero, $seguridad_social, $codigo_categoria, $codigo_suscriptor, $observaciones) {
      $res = null;
      $con = new conexionBD();
      $db = $con->getConexDB();
      $rs = $db->Execute("UPDATE jugadores set estado='$estado', tipo_documento='$tipo_documento', doc_identidad='$doc_identidad', fecha_expedicion='$fecha_expedicion', nombres='$nombres', apellidos='$apellidos', fecha_nacimiento='$fecha_nacimiento', codigo_lugar_nacimiento='$codigo_lugar_nacimiento', tipo_sangre='$tipo_sangre', direccion='$direccion', barrio='$barrio', telefono='$telefono', celular='$celular', email='$email', bb_pin='$bb_pin', colegio='$colegio', grado='$grado', genero='$genero', seguridad_social='$seguridad_social', codigo_categoria=$codigo_categoria, codigo_suscriptor=$codigo_suscriptor, observaciones='$observaciones' where codigo=$codigo");

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
/*
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