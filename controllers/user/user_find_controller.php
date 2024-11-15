<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

if (isset($_POST['find-student'])) {
  $student_number = $_POST['student_number'];

  $sql1 = "
    SELECT 
        s.user_id,
        s.student_id,
        s.student_number,
        s.name
    FROM students s
    WHERE s.student_number = '$student_number'
  ";

  $sql2 = "
    SELECT 
      c.course_id, 
      c.course_code, 
      c.course_name,
      e.enrollment_id
    FROM courses c
    JOIN enrollments e ON e.course_id = c.course_id
    JOIN students s ON s.student_id = e.student_id
    WHERE s.student_number = '$student_number'
  ";

  $query1 = mysqli_query($connect, $sql1);
  if ($query1) {
    $student_data = mysqli_fetch_assoc($query1);

    $_SESSION['nilai_student_data'] = $student_data;

    $student_courses = [];
    $query2 = mysqli_query($connect, $sql2);
    if ($query2) {
      while ($row = mysqli_fetch_assoc($query2)) {
        $student_courses[] = $row;
      }

      $_SESSION['nilai_student_courses'] = $student_courses;
      header("Location: ../../views/input_nilai.php");
    } else {
      die("Failed to find user");
    }
  } else {
    die("Failed to find user");
  }
} else {
  header("Location: ../../views/input_nilai.php");
}
