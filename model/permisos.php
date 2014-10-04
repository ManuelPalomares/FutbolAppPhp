<?php

require_once(dirname(dirname(__FILE__))."/libs/conexionBD.php");

class PermisosApp {
    
    public function validarPermisoSistema($usuario,$accion,$opcion){
        $res = null;
        $con1 = new conexionBD();
        $db = $con1->getConexDB();
        $sql = "select d.DESCRIPCION_ACCION from usuarios a ,usuarios_rol b,roles_opciones c,opciones_acciones d, acciones_rol e
where 
a.USUARIO = '$usuario'
and b.CODIGO_USUARIO = a.CODIGO
and b.ESTADO = 'A'
and b.ROL = c.ROL
and c.OPCION = d.CODIGO_OPCION
and d.DESCRIPCION_ACCION = '$accion'
and c.OPCION = $opcion
and e.codigo_opcion = c.OPCION 
and e.codigo_accion = d.CODIGO_ACCION 
and e.codigo_rol    =b.rol";
        
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