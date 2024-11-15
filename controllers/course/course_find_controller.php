<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

if (isset($_POST['find-course'])) {
  $course_id = $_POST['course_id'];
  $nilai_student_data = $_SESSION['nilai_student_data'] ?? null;

  $sql2 = "
    SELECT
      e.enrollment_id
    FROM enrollments e
    JOIN courses c ON c.course_id = e.course_id
    JOIN students s ON s.student_id = e.student_id
    WHERE c.course_id = '$course_id' AND s.student_id = '$nilai_student_data[student_id]'
  ";

  $query2 = mysqli_query($connect, $sql2);
  $enrollment = mysqli_fetch_array($query2);

  $sql1 = "
    SELECT
      c.course_id,
      ac.component_id,
      ac.component_name,
      sas.score
    FROM assessment_components ac
    JOIN courses c ON c.course_id = ac.course_id
    LEFT JOIN student_assessment_scores sas ON sas.component_id = ac.component_id 
    AND sas.enrollment_id = '$enrollment[enrollment_id]'
    WHERE c.course_id = '$course_id'
  ";

  $course_ass = [];
  $query1 = mysqli_query($connect, $sql1);
  if ($query1) {
    while ($row = mysqli_fetch_assoc($query1)) {
      $course_ass[] = $row;
    }

    $_SESSION['nilai_course_ass'] = $course_ass;
    $_SESSION['nilai_course_selected'] = $course_id;
    $_SESSION['nilai_enrollment_selected'] = $enrollment['enrollment_id'];
    header("Location: ../../views/input_nilai.php");
  } else {
    die("Failed to find user");
  }
} else {
  header("Location: ../../views/input_nilai.php");
}
