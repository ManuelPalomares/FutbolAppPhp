<?php

include_once (__DIR__).'/../libs/ConexionBD.php';

class MenuApp {
    
    public function menuModulos($usuario){
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql  = "SELECT * FROM MODULOSUSUARIO WHERE USUARIO = '$usuario'";
        $rs = $db->Execute($sql);      
        $res = $rs->getRows();
        return $res;
    }
    
    public function menusSubmenus($usuario,$opcion){
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql  = "SELECT * FROM menusopciones WHERE CODIGO_PADRE = '$opcion' and usuario='$usuario'";
        
        $rs = $db->Execute($sql);      
        $res = $rs->getRows();
        return $res;
    }
    
    public function menuModulosJson($usuario){
        $result = null;
        $result["success"] = true;
        $result["modulos"] = $this->menuModulos($usuario);
        echo json_encode($result);
    }
    
    public function menuSubMenusJson($usuario,$opcion){
        $result = null;
        $result["success"] = true;
        $result["opciones"] = $this->menusSubmenus($usuario,$opcion);
        echo json_encode($result);
    }
}
?>

