<?php 
	date_default_timezone_set('Australia/Sydney');
	
	define("HOST","localhost");
	// define("DB_USER","");
	// define("DB_PASS","");
	// define("DB_NAME","db_20630052");
	define("DB_USER","root");
	define("DB_PASS","");
	define("DB_NAME","medi_connect");
	$conn = mysqli_connect(HOST,DB_USER,DB_PASS,DB_NAME);
	if(!$conn)
	{
		die(mysqli_error());
	}
?>