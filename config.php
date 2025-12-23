<?php
$host = "localhost";
$username = "admin";
$password = "juanageralddarren";
$database = "umahkawan";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("connection failed" . mysqli_connect_error());
}
