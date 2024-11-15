<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

if (isset($_POST['add-course'])) {
  $course_code = $_POST['course_code'];
  $course_name = $_POST['course_name'];
  $credit = $_POST['credit'];
  $lecturer_id = $_POST["lecturer"];

  $sql = "
    INSERT 
    INTO courses (course_code, course_name, credits, lecturer_id) 
    VALUES ('$course_code', '$course_name', '$credit', '$lecturer_id')
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
