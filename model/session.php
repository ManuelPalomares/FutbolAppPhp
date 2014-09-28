<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
ini_set('session.cookie_domain', '*');
session_start();
        
class SessionApp {

    private function isRegisterUser() {
        //echo "__".$_SESSION["APP_WEBMAPSOFT.COM"]["USER"]."__";
        if (isset($_SESSION["APP_WEBMAPSOFT.COM"]["USER"])) {
            return "S";
        } else {
            return "N";
        }
    }

    public function isRegisterUserJson($jsonres = true) {
        $res = array();

        if ($this->isRegisterUser() == "N") {
            $res["success"] = true;
            $res["message_error"] = "Usuario no registrado";
            echo json_encode($res);
            exit();
        } else {
            if ($jsonres ==true) {
                $res["success"] = true;
                echo json_encode($res);
                exit();
            }else{
                return $_SESSION["APP_WEBMAPSOFT.COM"]["USER"];
            }
                
        }
    }

}
