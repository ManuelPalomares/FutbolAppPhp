<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// Inialize session
require_once('../include/index.php');
require_once("../../model/session.php");
/*$session = new SessionApp();
$session->isRegisterUserJson();
*/
SessionApp::isRegisterUserJson();
?>