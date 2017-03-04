<?php

class Database{

	public function __construct(){

		global $config_sett;	
		global $con;

		require('equinox/libs/helpers/MysqliDb.php');

		$db = new MysqliDb($config_sett['dbhost'], $config_sett['dbuser'], $config_sett['dbpass'], $config_sett['dbname']);
	}
}
