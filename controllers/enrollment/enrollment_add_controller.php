<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

$course_id = $_GET['course_id'];
$student_id = $_GET['student_id'];

if (empty($course_id) && empty($student_id)) {
  header("Location: ../../views/kelas.php");
} else {
  $sql = "INSERT INTO enrollments (course_id, student_id) VALUES ('$course_id', '$student_id')";

  $query = mysqli_query($connect, $sql);
  if ($query) {
    header("Location: ../../views/kelas.php");
  } else {
    die("Failed to add class");
  }
}
