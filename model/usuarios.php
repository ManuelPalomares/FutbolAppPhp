<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once (__DIR__).'/../libs/ConexionBD.php';
require (__DIR__).'/permisos.php';

class Usuarios{
    
    public $codigo;
    public $usuario;
    public $password;
    public $nombre;
    public $correo;
    
    public function __construct($usuario, $accion, $opcion) {
        
        
       $res = null;
       $usuarioData = $this->usuarioExiste($usuario);
       
       //valido si existe el usuario
       if(!isset($usuarioData[0]["NOMBRE"])){
           $res["success"] = true;
           $res["mensaje_error"] = "Usuario invalido o no existe";
           echo json_encode($res);
           exit();
       }
       //permiso
       $permisos = new PermisosApp();
       $permiso = $permisos->validarPermisoSistema($usuario, $accion, $opcion);
       if($permiso == false){
           $res["success"] = true;
           $res["permiso"] = false;
           $res["mensaje_error"] = "No tiene permiso para ejecutar la opcion o acceso por favor comunicar con su administrador";
           echo json_encode($res);
           exit();
       }
       
    }
    
    public function usuarioExiste($usuario){
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $rs = $db->Execute("select * from usuarios where usuario='$usuario'");
        $res = $rs->getRows();
        return $res;
    }
    
    public function consultarUsuario($usuario,$password){
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $rs = $db->Execute("select * from usuarios where usuario='$usuario' and password = '$password'");
        $res = $rs->getRows();
        return $res;
    }
    
    public function crearUsuarioNuevo($usuario,$password,$nombre,$correo){
         $res = null;
         $con = new conexionBD();
         $db = $con->getConexDB();
          $rs = $db->Execute("insert into usuarios value(null,'$usuario','$password','$nombre','$correo')");
        $res = $rs->getRows();
        return $res;
    }
    
    
}
?>