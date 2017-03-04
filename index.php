<?php

	ini_set('display_errors', '1');     # don't show any errors...
	// error_reporting(E_ALL ^ E_STRICT);  # ...but do log them

	ob_start();
	session_start();
	date_default_timezone_set("Atlantic/Bermuda");


	// Load
	require_once('equinox/config/conf.php');

	require_once('libs/functions.php');
	require_once('libs/boot.php');

	require_once('libs/database.php');
	require_once('libs/view.php');
	require_once('libs/controller.php');
	// require 'libs/functions.php';
	require_once('libs/model.php');

	// Load all helpers!


	//if person logged in hasnt completed their profile then redirect them to their profile
	// $eqApp = new Apps();
	// Boot it up!
	$app = new Boot();
?>
