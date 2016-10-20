<?php
 
require_once (dirname(__FILE__) . "/URFAClient_Connection.php");
require_once (dirname(__FILE__) . "/URFAClient_Packet.php");
require_once (dirname(__FILE__) . "/URFAClient_Admin.php");
require_once (dirname(__FILE__) . "/URFAClient_User5.php");
 
abstract class URFAClient
{
    /**
     * @var URFAClient_Connection
     */
    protected $connection = null;
    protected $address;
    protected $port;
    public $error = '';
    public function __construct($login, $pass, $address = "127.0.0.1", $port = "11758", $ssl = true, $admin)
    {
	$this->address = $address;
	$this->port = $port;
	$this->connection = new URFAClient_Connection($address, $port, $login, $pass, $ssl, $admin);
	$this->error = $this->connection->error;
    }
}
 
?>