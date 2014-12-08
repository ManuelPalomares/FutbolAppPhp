<?php

//lIBRERIA PHP DE CONEXION A LA BASE DE DATOS
require_once(dirname(dirname(__FILE__)) . "/libs/conexionBD.php");
//LIBRERIA PARA VALIDACION DE PERMISOS
require_once(dirname(__FILE__) . "/permisos.php");

require_once(dirname(dirname(__FILE__)) . "/libs/utf8Array.php");
require_once(dirname(dirname(__FILE__)) . "/libs/fileUpload.php");

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

    public function guardarJugador($fecha_ingreso, $estado, $tipo_documento, $doc_identidad, $fecha_expedicion, $nombres, $apellidos, $fecha_nacimiento, $codigo_lugar_nacimiento, $tipo_sangre, $direccion, $barrio, $telefono, $celular, $email, $bb_pin, $colegio, $grado, $genero, $seguridad_social, $codigo_categoria, $codigo_suscriptor, $observaciones, $foto, $inscripcion, $mensualidad, $transporte, $exp_deportiva,$jornada_colegio,$referido,$responsable,$nombre_madre,$celular_madre,$email_madre,$ocupacion_madre,$empresa_madre,$nombre_padre,$celular_padre,$email_padre,$ocupacion_padre,$empresa_padre/*$usuario_atencion=""*/) {
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $sql = "insert into jugadores(codigo,"
                . "fecha_ingreso,estado,tipo_documento,"
                . "doc_identidad,fecha_expedicion,nombres,"
                . "apellidos,fecha_nacimiento,codigo_lugar_nacimiento,"
                . "tipo_sangre,direccion,barrio,telefono,"
                . "celular,email,bb_pin,colegio,"
                . "grado,genero,seguridad_social,"
                . "codigo_categoria,codigo_suscriptor,"
                . "observaciones,foto,"
                //. "usuario_atencion,"
                . "inscripcion,mensualidad,transporte,"
                . "exp_deportiva,jornada_colegio,referido, responsable,nombre_madre,"
                . "celular_madre,email_madre,ocupacion_madre,empresa_madre,nombre_padre,"
                . "celular_padre,email_padre,ocupacion_padre,empresa_padre) "
                . "values (null,SYSDATE(),'$estado','$tipo_documento',"
                . "'$doc_identidad','$fecha_expedicion','$nombres',"
                . "'$apellidos','$fecha_nacimiento','$codigo_lugar_nacimiento',"
                . "'$tipo_sangre','$direccion','$barrio','$telefono','$celular',"
                . "'$email','$bb_pin','$colegio','$grado','$genero','$seguridad_social',"
                . "'$codigo_categoria','$codigo_suscriptor','$observaciones',"
                . "'$foto',"
                //. "'$usuario_atencion',"
                . "'$inscripcion','$mensualidad','$transporte',"
                . "'$exp_deportiva','$jornada_colegio','$referido','$responsable','$nombre_madre',"
                . "'$celular_madre','$email_madre','$ocupacion_madre','$empresa_madre','$nombre_padre',"
                . "'$celular_padre','$email_padre','$ocupacion_padre','$empresa_padre')";
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

    public function consultarJugadoresJson($start, $end, $categoria = 0, $queryNombre = "") {
        $result = null;
        $res = $this->consultarJugadores($start, $end, $categoria, $queryNombre);

        $result["jugadores"] = utf8Array::Utf8_string_array_encode($res["datos"]);
        $result["totalRows"] = $res["totalRows"];
        //print_r($result);

        echo json_encode($result);
    }

    public function consultarJugadores($start = 0, $end = 50, $categoria = 0, $queryNombre = "") {

        if ($categoria != "")
            $QueryCategoria = "and codigo_categoria=$categoria";

        if ($queryNombre != "")
            $queryNombre = "and CONCAT(nombres,' ',apellidos) like '%$queryNombre%'";

        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $totalRows = null;
        $datos = null;
        $sql1 = "SELECT count(1) cantidad FROM jugadores j where 1=1 $QueryCategoria $queryNombre";

        $db->SetFetchMode(ADODB_FETCH_ASSOC);


        $rs = $db->Execute($sql1);
        $res = $rs->getrows();
        $totalRows = $res[0]["cantidad"];

        $sql2 = "SELECT codigo,"
                . "DATE_FORMAT(fecha_ingreso,'%Y/%m/%d') fecha_ingreso,"
                . "estado,tipo_documento,"
                . "doc_identidad,"
                . "DATE_FORMAT(fecha_expedicion,'%Y/%m/%d') fecha_expedicion,"
                . "nombres,"
                . "apellidos,"
                . "DATE_FORMAT(j.fecha_nacimiento,'%Y/%m/%d') fecha_nacimiento,"
                . "codigo_lugar_nacimiento,"
                . "tipo_sangre,"
                . "direccion,"
                . "barrio,"
                . "telefono,"
                . "celular,"
                . "email,"
                . "bb_pin,"
                . "colegio,"
                . "grado,"
                . "genero"
                . ",seguridad_"
                . "social,"
                . "codigo_categoria,"
                . "codigo_suscriptor,"
                . " observaciones,"
                . "foto,"
                . "CONCAT(j.nombres,' ',j.apellidos) nombre_completo, "
                . "inscripcion,"
                . "mensualidad,"
                . "transporte,"
                . "exp_deportiva,"
                . "jornada_colegio,"
                . "referido,"
                . "responsable,"
                . "nombre_madre,"
                . "celular_madre,"
                . "email_madre,"
                . "ocupacion_madre,"
                . "empresa_madre,"
                . "nombre_padre,"
                . "celular_padre,"
                . "email_padre,"
                . "ocupacion_padre,"
                . "empresa_padre"
                . " FROM jugadores j where 1=1 $QueryCategoria $queryNombre LIMIT $start,$end;";
        //echo $sql2;
        $db->SetFetchMode(ADODB_FETCH_ASSOC);

        $rs = $db->Execute($sql2);
        $res = $rs->getrows();
        $datos = $res;
        return array("datos" => $datos, "totalRows" => $totalRows);
    }
    
    
    public function consultarTodosJugadoresxCategoria($categoria){
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql1  = "select * from jugadores where codigo_categoria =$categoria;";
        $rs = $db->Execute($sql1);
        
        if ($rs == false) {
            $res["success"] = true;
            $res["msg"] = "Error almacenando el registro " . $db->ErrorMsg();
            $res["error"] = true;
            return $res;
        }
        $res = $rs->getrows();
        
        return $res; 
    }

    public function actualizarJugador($codigo, $fecha_ingreso, $estado, $tipo_documento, $doc_identidad, $fecha_expedicion, $nombres, $apellidos, $fecha_nacimiento, $codigo_lugar_nacimiento, $tipo_sangre, $direccion, $barrio, $telefono, $celular, $email, $bb_pin, $colegio, $grado, $genero, $seguridad_social, $codigo_categoria, $codigo_suscriptor, $observaciones, $foto, $inscripcion, $mensualidad, $transporte, $exp_deportiva,$jornada_colegio,$referido,$responsable,$nombre_madre,$celular_madre,$email_madre,$ocupacion_madre,$empresa_madre,$nombre_padre,$celular_padre,$email_padre,$ocupacion_padre,$empresa_padre /*, $usuario_atencion = ""*/) {
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $sql = "UPDATE jugadores "
                . "set estado='$estado', "
                . "tipo_documento='$tipo_documento', "
                . "doc_identidad='$doc_identidad',"
                . "fecha_expedicion='$fecha_expedicion', "
                . "fecha_nacimiento='$fecha_nacimiento',"
                . "nombres='$nombres', "
                . "apellidos='$apellidos', "
                . "codigo_lugar_nacimiento='$codigo_lugar_nacimiento', "
                . "tipo_sangre='$tipo_sangre', "
                . "direccion='$direccion', "
                . "barrio='$barrio', "
                . "telefono='$telefono', "
                . "celular='$celular', "
                . "email='$email', "
                . "bb_pin='$bb_pin', "
                . "colegio='$colegio', "
                . "grado='$grado', "
                . "genero='$genero', "
                . "seguridad_social='$seguridad_social', "
                . "codigo_categoria=$codigo_categoria, "
                . "codigo_suscriptor='$codigo_suscriptor',"
                . "observaciones='$observaciones',"
                . "foto='$foto',"
                //. "usuario_atencion='$usuario_atencion',"
                . "inscripcion='$inscripcion',"
                . "mensualidad='$mensualidad',"
                . "transporte='$transporte',"
                . "exp_deportiva='$exp_deportiva',"
                . "jornada_colegio='$jornada_colegio',"
                . "referido='$referido',"
                . "responsable='$responsable',"
                . "nombre_madre='$nombre_madre',"
                . "celular_madre='$celular_madre',"
                . "email_madre='$email_madre',"
                . "ocupacion_madre='$ocupacion_madre',"
                . "empresa_madre='$empresa_madre',"
                . "nombre_padre='$nombre_padre',"
                . "celular_padre='$celular_padre',"
                . "email_padre='$email_padre',"
                . "ocupacion_padre='$ocupacion_padre',"
                . "empresa_padre='$empresa_padre'"
                . " where codigo = '$codigo'";
        //echo $sql;
        $rs = $db->Execute($sql);

        if ($rs == false) {
            $res["success"] = true;
            $res["msg"] = "Error actualizando el registro " . $db->ErrorMsg();
            $res["newId"] = $codigo;
            $res["error"] = true;
            return $res;
        }
        $res["success"] = true;
        $res["msg"] = "Se actualizo el registro correctamente";
        $res["newId"] = $codigo;
        return($res);
    }

    public function cargarFoto($foto) {
        $resFile = null;
        $res = "";
        $fileUp = new fileUpload();
        $n = rand(1, 100000);
        $nameLoaded = "foto_" + $n;
        $resFile = $fileUp->cargarArchivo($foto, "fotosjugadores", $nameLoaded);

        if ($resFile == false) {
            $res["success"] = true;
            $res["permiso"] = false;
            $res["mensaje_error"] = "Error cargando el archivo de imagen vuelva intentar de nuevo";
            echo json_encode($res);
            exit();
        }

        $res["success"] = true;
        $res["foto"] = $resFile;
        $res["msg"] = "Foto cargada con exito";
        echo json_encode($res);
    }

}

?>