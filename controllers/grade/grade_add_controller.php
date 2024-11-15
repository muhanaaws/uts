<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

$return_url = isset($_POST['return_url']) ? $_POST['return_url'] : '../../views/input_nilai.php';

if (isset($_POST['add-grade'])) {
  $grades = $_POST['grades'];
  $nilai_student_data = $_SESSION['nilai_student_data'];
  $nilai_student_courses = $_SESSION['nilai_student_courses'];
  $nilai_enrollment_selected = $_SESSION['nilai_enrollment_selected'];

  foreach ($grades as $component_id => $scores) {
    foreach ($scores as $score_id => $score_value) {
      if ($score_value >= 1 && $score_value <= 100) {
        $check_query = "
          SELECT score_id
          FROM student_assessment_scores
          WHERE score_id = '$score_id' AND enrollment_id = '$nilai_enrollment_selected' AND component_id = '$component_id'
        ";

        $check_result = mysqli_query($connect, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
          $update_query = "
            UPDATE student_assessment_scores
            SET score = '$score_value'
            WHERE score_id = '$score_id' AND enrollment_id = '$nilai_enrollment_selected' AND component_id = '$component_id'
          ";

          mysqli_query($connect, $update_query);
        } else {
          $insert_query = "
            INSERT INTO student_assessment_scores (enrollment_id, component_id, score)
            VALUES ('$nilai_enrollment_selected', '$component_id', '$score_value')
          ";

          mysqli_query($connect, $insert_query);
        }
      }
    }
  }

  // if ($query) {
  if ($return_url == '../../views/rekap.php') {
    $course_id = $_SESSION['rekap_course_id'];

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
  }

  unset($_SESSION['nilai_student_data']);
  unset($_SESSION['nilai_student_courses']);
  unset($_SESSION['nilai_course_ass']);
  unset($_SESSION['nilai_course_selected']);
  unset($_SESSION['nilai_enrollment_selected']);


  header("Location: $return_url");
  // header("Location: ../../views/input_nilai.php");
  // } else {
  //   die("Failed to add grade");
  // }
} else {
  header("Location: ../../views/input_nilai.php");
}
