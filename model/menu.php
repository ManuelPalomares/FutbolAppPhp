<?php

require_once(dirname(dirname(__FILE__))."/libs/conexionBD.php");
require_once(dirname(dirname(__FILE__)) . "/libs/utf8Array.php");

class MenuApp {
    
    public function menuModulos($usuario){
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql  = "SELECT * FROM modulosusuario WHERE USUARIO = '$usuario'";
        //echo $sql;
        $rs = $db->Execute($sql);      
        $res = utf8Array::Utf8_string_array_encode($rs->getRows());
        return $res;
    }
    
    public function menusSubmenus($usuario,$opcion){
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql  = "SELECT * FROM menusopciones WHERE CODIGO_PADRE = '$opcion' and usuario='$usuario'";
        //echo $sql;
        //exit();
        $rs = $db->Execute($sql);      
        $res = utf8Array::Utf8_string_array_encode($rs->getRows());
        return $res;
    }
    
    public function menuModulosJson($usuario){
        
        $result = null;
        $result["success"] = true;
        $result["modulos"] = $this->menuModulos($usuario);
        foreach ($result["modulos"] as $key => $value) {
            $value["SUBMENUS"] = $this->menusSubmenus($usuario, $value["CODIGO"]);
            $result["modulos"][$key]=$value;
        }
        
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

