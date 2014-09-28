<?php
// Inialize session
include (__DIR__).'/../../model/session.php';

$session = new SessionApp();
$session->isRegisterUserJson();
?>