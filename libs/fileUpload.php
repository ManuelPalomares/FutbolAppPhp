<?php

/**
 * Description of fileUpload
 *
 * @author WebmapsoftDev
 */
class fileUpload {
    public function cargarArchivo($file,$folderLoad,$nameLoaded){
          
          $name   = $file["name"];
          $type   = $file["type"];
          $tmp_name= $file["tmp_name"];
          $error   =$file["error"];
          $size   = $file["size"];
          
          
          
          if($error != 0){
              return false;
          }
         $ext = pathinfo($name, PATHINFO_EXTENSION);
         
         try {
             move_uploaded_file($tmp_name, dirname(__FILE__)."/../files/$folderLoad/$nameLoaded.".$ext);
         } catch (Exception $exc) {
             return false;
             
         }
        return $nameLoaded.".".$ext;
    }
}
