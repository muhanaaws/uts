<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

$enrollment_id = @$_GET["enrollment_id"];
$student_id = @$_GET["student_id"];

if (empty($enrollment_id)) {
  header("Location: ../../views/dashboard.php");
} else {
  $query = "DELETE FROM enrollments where enrollment_id='$enrollment_id' AND student_Id='$student_id'";

  mysqli_query($connect, $query);

  header("Location: ../../views/dashboard.php");
}
