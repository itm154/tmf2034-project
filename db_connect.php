<?php

$hostname = "db";
$username = "root";
$password = "";
$database_name = "FitLifeDB";
$port = 3306;

// Create connection
$conn = new mysqli($hostname, $username, $password, $database_name, $port);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
global $conn;
