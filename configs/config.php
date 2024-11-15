<?php
$server = "localhost";
$user = "root";
$password = "";
$db = "db_crud_web_rekap";

$connect = mysqli_connect($server, $user, $password, $db);
if (!$connect) {
  die("Terjadi masalah koneksi ke database: " . mysqli_connect_error());
}
