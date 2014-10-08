<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');


require_once('../include/index.php');
require_once("../../model/usuarios.php");
session_start();
$datos = $_REQUEST;

$accion = $datos["accion"];
$usuario_nombre = $datos['usuario'];
$password = $datos['password'];

if($accion=="CERRARSESION"){
    session_destroy();
    echo json_encode(array("success"=>true)); 
    
}
if ($accion == "CONECTARSE") {
    $usuarios = new Usuarios($usuario_nombre, $accion, 1);
    $usuario = $usuarios->consultarUsuario($usuario_nombre, $password);

    if ($usuario[0]["CODIGO"] != null) {

        if ($usuario[0]['ESTADO'] == 'I') {
            $res["success"] = true;
            $res["mensaje_error"] = "Su usuario esta inactivo";
            echo json_encode($res);
            exit();
        }
        //REGISTRAR USUARIO
        $_SESSION["APP_WEBMAPSOFT.COM"]["USER"] = $usuario[0]["USUARIO"];
        $_SESSION["APP_WEBMAPSOFT.COM"]["NOMBRE"] = $usuario[0]["NOMBRE"];
        $_SESSION["APP_WEBMAPSOFT.COM"]["NOMBRE"] = $usuario[0]["NOMBRE"];
        $res["success"] = true;
        echo json_encode($res);
    } else {
        $res["success"] = true;
        $res["mensaje_error"] = "Usuario o contraseña Invalida";
        echo json_encode($res);
        exit();
    }
}
?>