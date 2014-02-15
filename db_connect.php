<?php
	$host = 'localhost';
	$DBuser = 'ricardo';
	$DBpswd = 'ricardo';
	$DBname = 'ricardo';

	$connect = mysqli_connect($host, $DBuser, $DBpswd, $DBname); //connect to db server

	if (! $connect) // cannot connect
	{
		exit('Cannot connect to the database: ' . mysqli_connect_error());
	}
?>