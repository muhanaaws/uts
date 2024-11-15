<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];
if ($status != "login") {
  header("location: ../../index.php");
  exit;
}

$student_data = json_decode(@$_GET["student_data"], true);

if (!empty($student_data)) {
  $course_id = $_SESSION['rekap_course_id'];
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

  $query = mysqli_query($connect, $sql);

  if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
      $delete_sas_query = "
        DELETE FROM student_assessment_scores
        WHERE score_id = '$row[score_id]'
      ";
      $sas_result = mysqli_query($connect, $delete_sas_query);

      if (!$sas_result) {
        header("Location: ../../views/rekap.php?status=error");
      }
    }

    $delete_final_grade_query = "
      DELETE FROM final_grades
      WHERE final_grade_id = '$student_data[final_grade_id]'
    ";
    $final_grade_result = mysqli_query($connect, $delete_final_grade_query);

    if ($final_grade_result) {
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
    WHERE c.course_id = $course_id
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
  } else {
    header("Location: ../../views/rekap.php");
  }
}
