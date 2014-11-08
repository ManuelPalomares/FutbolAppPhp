<?php

//lIBRERIA PHP DE CONEXION A LA BASE DE DATOS
require_once(dirname(dirname(__FILE__)) . "/libs/conexionBD.php");
//LIBRERIA PARA VALIDACION DE PERMISOS
require_once(dirname(__FILE__) . "/permisos.php");

require_once(dirname(dirname(__FILE__)) . "/libs/phpmailer/PHPMailerAutoload.php");

define('ADODB_FETCH_ASSOC', 2);


require_once(dirname(dirname(__FILE__)) . "/libs/utf8Array.php");

class CitasDeportivas {
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

    public function guardarCita($titulo_evento, $fecha_inicio, $fecha_fin, $estado_evento, $descripcion_evento) {
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $rs = $db->Execute("insert into eventos_deportivos values(null,'$titulo_evento','$fecha_inicio','$fecha_fin','$descripcion_evento','$estado_evento')");
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

    public function updateCita($codigo, $titulo_evento, $fecha_inicio, $fecha_fin, $estado_evento, $descripcion_evento) {
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $sql = "UPDATE eventos_deportivos set titulo_evento = '$titulo_evento',"
                . "fecha_inicio ='$fecha_inicio',"
                . "fecha_fin = '$fecha_fin',"
                . "descripcion_evento = '$descripcion_evento',"
                . "estado_evento    = '$estado_evento'"
                . "where codigo=$codigo";
        //echo $sql;
        $rs = $db->Execute($sql);

        if ($rs == false) {
            $res["success"] = true;
            $res["msg"] = "Error actualizando el registro " . $db->ErrorMsg();
            $res["newId"] = $codigo;
            return $res;
        }
        $res["success"] = true;
        $res["msg"] = "Se actualizo el registro correctamente";
        $res["newId"] = $codigo;
        return($res);
    }

    public function eliminarCita($codigo) {
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $sql = "delete from roles where codigo='$codigo';";
        //echo $sql;
        $rs = $db->Execute($sql);

        if ($rs == false) {
            $res["success"] = true;
            $res["msg"] = "Error eliminando el registro " . $db->ErrorMsg();
            $res["newId"] = $codigo;
            return $res;
        }

        $res["success"] = true;
        $res["msg"] = "Se elimino el registro correctamente ";
        $res["newId"] = $codigo;
        return($res);
    }

    public function consultarCitasDeportivas($start = 0, $end = 50) {



        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql = "SELECT codigo,titulo_evento,DATE_FORMAT(fecha_inicio,'%Y/%m/%d') fecha_inicio,DATE_FORMAT(fecha_fin,'%Y/%m/%d') fecha_fin,descripcion_evento,estado_evento,DATE_FORMAT(fecha_inicio, '%H:%i') hora1,DATE_FORMAT(fecha_fin, '%H:%i') hora2 FROM eventos_deportivos LIMIT $start, $end ;";
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        //echo $sql;
        $rs = $db->Execute($sql);
        $res = $rs->getrows();
        return $res;
    }
    
    public function consultarAgendaDeportivaCita($codigo_citaDeportiva,$tipo='J'){
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql = "SELECT * from vw_agendadosEventos where evento =$codigo_citaDeportiva and tipo like '%$tipo%';";
        
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        $rs = $db->Execute($sql);
        $res = $rs->getrows();
        return $res;
    }
    
    public function consultarCitasDeportivasPorId($codigo) {

        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql = "SELECT codigo,titulo_evento,DATE_FORMAT(fecha_inicio,'%Y/%m/%d') fecha_inicio,DATE_FORMAT(fecha_fin,'%Y/%m/%d') fecha_fin,descripcion_evento,estado_evento,DATE_FORMAT(fecha_inicio, '%H:%i') hora1,DATE_FORMAT(fecha_fin, '%H:%i') hora2 FROM eventos_deportivos where codigo =$codigo ;";
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        //echo $sql;
        $rs = $db->Execute($sql);
        $res = $rs->getrows();
        return $res;
    }

    public function consultarCitasJson($start, $end) {
        $result = null;
        $result["citas"] = utf8Array::Utf8_string_array_encode($this->consultarCitasDeportivas());
        $result["totalRows"] = sizeof($result["roles"]);
        echo json_encode($result);
    }

    public function enviarEmailCita($codigo =0) {
        $agendados = null;
        
        $datos = $this->consultarCitasDeportivasPorId($codigo);
        $datos = $datos[0];
        $titulo_cita = $datos["titulo_evento"];
        $descripcion_evento = $datos["descripcion_evento"];
        
        
        $agendados = CitasDeportivas::consultarAgendaDeportivaCita($codigo,'');
        //echo sizeof($agendados);
        
        if(sizeof($agendados)== 0){
            $res["success"] = true;
            $res["permiso"] = false;
            $res["mensaje_error"] = "No existen registros de jugadores asociados al evento ingrese los jugadores";
            echo json_encode($res);
            exit();
        }
        
        date_default_timezone_set('Etc/UTC');
        //Create a new PHPMailer instance
        $mail = new PHPMailer;

//Tell PHPMailer to use SMTP
        $mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
        $mail->SMTPDebug = 0;

//Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';

//Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 465;

//Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'ssl';

//Whether to use SMTP authentication
        $mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "diegochampionsfc@gmail.com";

//Password to use for SMTP authentication
        $mail->Password = "Ch4mpi0nsfc_2014";

//Set who the message is to be sent from
        $mail->setFrom('diegochampionsfc@gmail.com', 'Escuela de Futbol Champions');

//Set an alternative reply-to address
        //$mail->addReplyTo('aymer.com', 'First Last');

//Set who the message is to be sent to
        //$mail->addAddress('aiobando@gmail.com', 'Aymer');
        $mail->addAddress('manuel936@gmail.com', 'Manuel');

//Set the subject line
        $mail->Subject = "Citacion deportiva [".$titulo_cita."]";

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
        $mail->msgHTML($descripcion_evento, dirname(__FILE__));

//Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';

//Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
        
        $res["success"] = true;
        if (!$mail->send()) {
            $res["mensaje_error"] ="Mailer Error: " . $mail->ErrorInfo;
            
        } else {
            $res["msg"] ="Correo enviado con exito";
        }
        echo json_encode($res);
       
    }

}

?>