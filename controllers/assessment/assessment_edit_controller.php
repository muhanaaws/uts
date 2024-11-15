<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

if (isset($_POST['edit-assessment'])) {
  $component_id = $_POST['component_id'];
  $course_id = $_POST['course_id'];
  $component_name = $_POST['component_name'];
  $component_weight = $_POST['component_weight'] / 100;

  $sql = "
    UPDATE assessment_components 
    SET 
      course_id='$course_id',      
      component_name='$component_name', 
      component_weight='$component_weight'
    WHERE component_id='$component_id'
  ";

  $query = mysqli_query($connect, $sql);
  if ($query) {
    header("Location: ../../views/penilaian.php");
  } else {
    die("Failed to add class");
  }
} else {
  header("Location: ../../views/penilaian.php");
}
