<?php
$servername = "localhost";
$username = "root";
$password = "";
$datebaseName = "symposioumdb";

$connect = mysqli_connect($servername, $username, $password, $datebaseName);

if(!$connect) {
    die("Connection Failed " + mysqli_connect_error());
}
// echo "Connected Successfully";