<?php
require 'environment.php';

$config = array();
if(ENVIRONMENT == 'development') {
	define("BASE_URL", "http://localhost/wspedcontrol/");
	$config['dbname'] = 'db_pedcontrol';
	$config['host'] = 'localhost';
	$config['dbuser'] = 'root';
	$config['dbpass'] = '';
	$config['jwt_secret_key'] = 'abc123!';
} else {
	define("BASE_URL", "http://aejsolucoes.net.br/wspedcontrol/");
	$config['dbname'] = 'aejsoluc_pedcontrol';
	$config['host'] = 'localhost';
	$config['dbuser'] = 'aejsoluc_pedcontrol';
	$config['dbpass'] = 'LtY6f}zM&0&u';
	$config['jwt_secret_key'] = 'abc123!';
}

define('EMPRESA', "DAN'ART DECORAÇÔES LTDA ME");
define('EMPRESA_CNPJ', "04.974.973/0001-82");
define('EMPRESA_ENDERECO', "Av. Marlene Dias, 1655 - SP");
define('EMPRESA_TEL', "(11) 2545-8845");
define('EMPRESA_EMAIL', "contato@danart.com.br");

global $db;
try {
	$db = new PDO("mysql:dbname=".$config['dbname'].";host=".$config['host'], $config['dbuser'], $config['dbpass']);
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo "ERRO: ".$e->getMessage();
	exit;
}