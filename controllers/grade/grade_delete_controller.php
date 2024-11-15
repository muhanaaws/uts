<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

$grade_id = @$_GET["grade"];

if (empty($grade_id)) {
  header("Location: ../../views/dashboard.php");
} else {
  $query = "DELETE FROM grades where id='$grade_id'";

  mysqli_query($connect, $query);

  header("Location: ../../views/dashboard.php");
}
