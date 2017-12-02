<?php
	$api_key = "RGAPI-011facdf-3349-40eb-b566-d1f4f54f5ef9";

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
