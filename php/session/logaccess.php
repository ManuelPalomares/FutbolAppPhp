<?php

include (__DIR__).'/../../model/usuarios.php';

$datos = $_REQUEST;

$usuario = isset($datos['usuario']);
$password = isset($datos['password']);


$usuarios = new Usuarios();
print_r($usuarios->consultarUsuario($usuario, $password));

?>