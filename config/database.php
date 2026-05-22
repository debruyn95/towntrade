<?php

$host = "sql203.infinityfree.com";
$username = "if0_41891793";
$password = "Debruyn1995";
$database = "if0_41891793_towntrade";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>
