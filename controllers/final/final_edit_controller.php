<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

$student_data = json_decode(@$_GET["student_data"], true);

if (!empty($student_data)) {
  $student_data_filtered = [
    'user_id' => $student_data['user_id'],
    'student_id' => $student_data['student_id'],
    'student_number' => $student_data['student_number'],
    'name' => $student_data['name'],
    'final_grade_id' => $student_data['final_grade_id']
  ];

  $sql = "
    SELECT
      c.course_id,
      ac.component_id,
      ac.component_name,
      sas.score_id,
      sas.score
    FROM assessment_components ac
    JOIN courses c ON c.course_id = ac.course_id
    LEFT JOIN student_assessment_scores sas ON sas.component_id = ac.component_id 
    AND sas.enrollment_id = '$student_data[enrollment_id]'
    WHERE c.course_id = '$student_data[course_id]'
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
    WHERE s.student_number = '$student_data[student_number]'
  ";

  $course_ass = [];
  $query = mysqli_query($connect, $sql);

  if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
      $course_ass[] = $row;
    }
  }

  $student_courses = [];
  $query2 = mysqli_query($connect, $sql2);
  if ($query2) {
    while ($row = mysqli_fetch_assoc($query2)) {
      $student_courses[] = $row;
    }
  }

  $_SESSION['nilai_course_ass'] = $course_ass;
  $_SESSION['nilai_student_courses'] = $student_courses;
  $_SESSION['nilai_student_data'] = $student_data_filtered;
  $_SESSION['nilai_course_selected'] = $student_data['course_id'];
  $_SESSION['nilai_enrollment_selected'] = $student_data['enrollment_id'];
  header("Location: ../../views/input_nilai.php?return_url=../../views/rekap.php");
}
