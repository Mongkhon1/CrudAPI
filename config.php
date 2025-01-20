<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, PUT, DELETE');
header('Access-Control-Allow-Headers: token,Content-Type');
header('Access-Control-Max-Age:1278000');
header('Content-Length: 0');
header('Content-Type: text/plain');

$host = "localhost";
$user = "root";
$password = "";
$dbname = "project28";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>