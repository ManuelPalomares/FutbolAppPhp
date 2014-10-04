<?php
session_start();
        
class SessionApp {

    private function isRegisterUser() {
        //echo "__".$_SESSION["APP_WEBMAPSOFT.COM"]["USER"]."__";
        $user = $_SESSION["APP_WEBMAPSOFT.COM"]["USER"];
        $user ="ADMIN";
        if (isset($user)) {
            return "S";
        } else {
            return "N";
        }
    }

    public function isRegisterUserJson($jsonres = true) {
        $res = array();

        if (SessionApp::isRegisterUser() == "N") {
            $res["success"] = true;
            $res["message_error"] = "Usuario no registrado";
            echo json_encode($res);
            exit();
        } else {
            if ($jsonres ==true) {
                $res["success"] = true;
                $res["user"] =$_SESSION["APP_WEBMAPSOFT.COM"]["USER"] ;
                $res["nombre"] =$_SESSION["APP_WEBMAPSOFT.COM"]["NOMBRE"] ;
                echo json_encode($res);
                exit();
            }else{
                //return $_SESSION["APP_WEBMAPSOFT.COM"]["USER"];
                return 'ADMIN';
            }
                
        }
    }

}
