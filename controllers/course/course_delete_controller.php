<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

$course_id = @$_GET["course_id"];

if (empty($course_id)) {
  header("Location: ../../views/kelas.php");
} else {
  $query = "DELETE FROM courses where course_id='$course_id'";

  mysqli_query($connect, $query);

  header("Location: ../../views/kelas.php");
}
