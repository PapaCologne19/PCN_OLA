<?php

	$localhost = "localhost";
	$user = "root";
	$password = "";
	$database = "calendar";

	$connect = mysqli_connect($localhost, $user, $password, $database);

	if(!$connect){
		echo "There was an error in connecting database";
	}
	
	?>