<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include (__DIR__).'/../libs/ConexionBD.php';
class Usuarios{
    
    public $codigo;
    public $usuario;
    public $password;
    public $nombre;
    public $correo;
    
    
    public function consultarUsuario($usuario,$password){
        
        $res = null;
        $con = new conexionBD();
        $db = $con->getConexDB();
        $rs = $db->Execute("select * from usuarios where usuario='$usuario' and password = '$password'");
        $res = $rs->getRows();
        return $res;
    }
}
?>
