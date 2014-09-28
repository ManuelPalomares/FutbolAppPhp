<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

class SessionApp {

    private function isRegisterUser() {
        session_start();
        if (isset($_SESSION["APP_WEBMAPSOFT.COM"]["USER"])) {
            return "S";
        } else {
            return "N";
        }
    }

    public function isRegisterUserJson() {
        $res = array();

        if ($this->isRegisterUser()=="N") {
            $res["success"] = true;
            $res["message_error"] = "Usuario no registrado";
        }
        else{
            $res["success"] = true;
        }
        
        echo json_encode($res);
        
    }

}
