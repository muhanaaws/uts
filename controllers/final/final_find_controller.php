<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

if (isset($_POST['find-final'])) {
  $course_id = $_POST['course_id'];
  $user_level = $_SESSION["level"];
  $id_role = $_SESSION["id_role"];

  $component_query = "SELECT component_id, component_name FROM assessment_components WHERE course_id = $course_id";
  $component_result = mysqli_query($connect, $component_query);

  $components = [];
  while ($component = mysqli_fetch_assoc($component_result)) {
    $components[] = $component;
  }

  $components[] = ['component_id' => 'final_score', 'component_name' => 'Total'];
  $components[] = ['component_id' => 'final_letter_grade', 'component_name' => 'Nilai'];

  $sql = "
    SELECT 
        s.user_id,
        s.student_id,
        s.student_number,
        s.name,";
  foreach ($components as $component) {
    $component_name = $component['component_name'];
    $component_id = $component['component_id'];
    $sql .= "
        SUM(CASE WHEN ac.component_id = $component_id THEN sas.score ELSE 0 END) AS `$component_name`,";
  }
  $sql .= "
      e.enrollment_id,
      c.course_id,
      fg.final_grade_id,
      fg.final_score AS Total,
      fg.final_letter_grade AS Nilai
    FROM students s
    JOIN enrollments e ON s.student_id = e.student_id
    JOIN courses c ON e.course_id = c.course_id
    LEFT JOIN assessment_components ac ON ac.course_id = c.course_id
    LEFT JOIN student_assessment_scores sas ON sas.enrollment_id = e.enrollment_id AND sas.component_id = ac.component_id
    LEFT JOIN final_grades fg ON fg.enrollment_id = e.enrollment_id
    WHERE c.course_id = $course_id";
  if ($user_level == 'mahasiswa') {
    $sql .= " AND s.student_id = $id_role"; // Sesuaikan dengan kolom yang relevan
  }
  $sql .= "
      GROUP BY s.student_number, s.name, e.enrollment_id, fg.final_grade_id, fg.final_score, fg.final_letter_grade";


  $query = mysqli_query($connect, $sql);

  $students = [];
  if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
      $students[] = $row;
    }
  }

  $_SESSION['rekap_components'] = $components;
  $_SESSION['rekap_students'] = $students;
  $_SESSION['rekap_course_id'] = $course_id;


  header("Location: ../../views/rekap.php");
} else {
  header("Location: ../../views/rekap.php");
}
