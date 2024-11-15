<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

if (isset($_POST['edit-course'])) {
  $course_id = $_POST['course_id'];
  $course_code = $_POST['course_code'];
  $course_name = $_POST['course_name'];
  $credit = $_POST['credit'];
  $lecturer_id = $_POST["lecturer"];

  $sql = "
    UPDATE courses 
    SET 
      course_code='$course_code', 
      course_name='$course_name', 
      credits='$credit',
      lecturer_id='$lecturer_id'
    WHERE course_id='$course_id'
  ";

  $query = mysqli_query($connect, $sql);
  if ($query) {
    header("Location: ../../views/kelas.php");
  } else {
    die("Failed to add class");
  }
} else {
  header("Location: ../../views/kelas.php");
}
