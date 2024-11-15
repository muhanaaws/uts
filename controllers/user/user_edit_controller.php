<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

if (isset($_POST["edit"])) {
  $username = $_POST['username'];
  $name = $_POST['name'];
  $id = $_POST['idUser'];

  $sql = "UPDATE users SET name='$name', username='$username' WHERE id='$id'";
  $query = mysqli_query($connect, $sql);

  if ($query) {
    header("Location: ../../views/dashboard.php");
  } else {
    die("Failed to update user");
  }
} else {
  header("Location: ../../views/dashboard.php");
}
