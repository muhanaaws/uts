<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

if (isset($_POST['add-assessment'])) {
  $course_id = $_POST['course_id'];
  $component_name = $_POST['component_name'];
  $component_weight = $_POST['component_weight'] / 100;

  $sql = "
    INSERT 
    INTO assessment_components (course_id, component_name, component_weight) 
    VALUES ('$course_id', '$component_name', '$component_weight')
  ";
  echo $sql;
  $query = mysqli_query($connect, $sql);
  if ($query) {
    header("Location: ../../views/penilaian.php");
  } else {
    die("Failed to add class");
  }
} else {
  header("Location: ../../views/penilaian.php");
}
