<?php
$host = "localhost";
$username = "umahkawa_admin";
$password = "juanageralddarren";
$database = "umahkawa_umahkawan";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("connection failed" . mysqli_connect_error());
}
