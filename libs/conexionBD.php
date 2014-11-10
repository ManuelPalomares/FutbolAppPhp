<?php

include "adodb5/adodb.inc.php";
class conexionBD{
    
    public $db;
    private $db_host;
    private $db_user;
    private $db_pass;
    private $db_name;
    
    
    public function __construct() {
        $fp = fopen((__DIR__)."/conexdata.dt","r");
        $data = fgetcsv($fp);
        $this->db_host =  $data[0];
        $data = fgetcsv($fp);
        $this->db_user = $data[0];
        $data = fgetcsv($fp);                ;
        $this->db_pass =  $data[0];
        $data = fgetcsv($fp);
        $this->db_name = $data[0];
        fclose($fp);
        
        
        $this->db = NewADOConnection('mysql');
        $this->db->Connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
        $this->db->EXECUTE("set names 'utf8'");
    }
    
    public function getConexDB(){
        return $this->db;
    }
    
    
}
/*
$con = new conexionBD();
$db= $con->getConexDB();
$rs= $db->Execute("show tables;");
print_r($rs->getRows());
 * */
?>