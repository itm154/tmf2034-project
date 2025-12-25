<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database_name = "FitLifeDB";
$port = 3307;

// Create connection
$conn = new mysqli($hostname, $username, $password, $database_name, $port);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
