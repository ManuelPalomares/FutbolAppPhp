<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require (__DIR__).'/../../model/usuarios.php';

$datos = $_REQUEST;

$accion  = $datos["accion"];
$usuario_nombre = $datos['usuario'];
$password = $datos['password'];

$usuarios = new Usuarios($usuario_nombre,$accion,1);
$usuario =  $usuarios->consultarUsuario($usuario_nombre, $password);

if($usuario[0]["CODIGO"] != null){
    
    if($usuario[0]['ESTADO']=='I'){
        $res["success"] = true;
        $res["mensaje_error"] = "Su usuario esta inactivo";
        echo json_encode($res);
        exit();
    }
    //REGISTRAR USUARIO
    session_start();
    $_SESSION["APP_WEBMAPSOFT.COM"]["USER"] = $usuario[0]["USUARIO"];
    $res["success"] = true;
    echo json_encode($res);

}else{
        $res["success"] = true;
        $res["mensaje_error"] = "Usuario o contraseña Invalida";
        echo json_encode($res);
        exit();
}

?>