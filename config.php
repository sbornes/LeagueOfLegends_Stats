<?php
	$cache_path = 'cache/';
	
	$api_key = "RGAPI-844998e6-ab4b-4f45-b44f-609e8f9bf90f";

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "league_site";

	$league_version = "7.23.1";

	// Create connection
	$conn = new mysqli($servername, $username, $password,$dbname);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

?>
