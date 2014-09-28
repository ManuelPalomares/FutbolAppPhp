<?php

include_once (__DIR__).'/../libs/ConexionBD.php';
class PermisosApp {
    
    public function validarPermisoSistema($usuario,$accion,$opcion){
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        
        $sql = "select d.DESCRIPCION_ACCION from usuarios a ,usuarios_rol b,roles_opciones c,opciones_acciones d
where 
a.USUARIO = '$usuario'
and b.CODIGO_USUARIO = a.CODIGO
and b.ESTADO = 'A'
and b.ROL = c.ROL
and c.OPCION = d.CODIGO_OPCION
and d.DESCRIPCION_ACCION = '$accion'
and c.OPCION = $opcion";
        
        //echo $sql;
        $rs = $db->Execute($sql);      
        $res = $rs->getRows();
        
        if($res[0]['DESCRIPCION_ACCION']==="$accion"){
            return true;
        }
        else{ 
            return false;
            
        }
    }
}
?>