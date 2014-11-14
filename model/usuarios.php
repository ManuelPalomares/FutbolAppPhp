<?php
require_once(dirname(dirname(__FILE__))."/libs/conexionBD.php");

require_once(dirname(__FILE__)."/permisos.php");

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
    
    public function consultarUsuarioPorNombre() {
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql = "SELECT codigo,nombre FROM usuarios ORDER BY nombre ASC;";
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        
        $rs = $db->Execute($sql);
        $res = $rs->getrows();
        return $res;
    }

    public function consultarUsuariosJson() {
        $result = null;
        $result["usuarios"] = $this->consultarUsuarios();
        $result["totalRows"] = sizeof($result["usuarios"]);
        echo json_encode($result);
    }
}
?>