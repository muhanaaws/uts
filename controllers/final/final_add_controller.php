<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

$course_id = @$_GET["course_id"];

if (!empty($course_id)) {
  $component_query = "
        SELECT component_id, component_weight 
        FROM assessment_components 
        WHERE course_id = $course_id
    ";
  $component_result = mysqli_query($connect, $component_query);

  $components = [];
  while ($component = mysqli_fetch_assoc($component_result)) {
    $components[$component['component_id']] = $component['component_weight'];
  }

  $scores_query = "
        SELECT e.enrollment_id, sas.component_id, sas.score
        FROM enrollments e
        JOIN student_assessment_scores sas ON e.enrollment_id = sas.enrollment_id
        WHERE e.course_id = $course_id
    ";
  $scores_result = mysqli_query($connect, $scores_query);

  $student_scores = [];
  while ($row = mysqli_fetch_assoc($scores_result)) {
    $enrollment_id = $row['enrollment_id'];
    $component_id = $row['component_id'];
    $score = $row['score'];

    if (!isset($student_scores[$enrollment_id])) {
      $student_scores[$enrollment_id] = 0;
    }

    $student_scores[$enrollment_id] += $score * ($components[$component_id] ?? 0);
  }

  foreach ($student_scores as $enrollment_id => $final_score) {
    $final_letter_grade = 'E';
    if ($final_score >= 81) {
      $final_letter_grade = 'A';
    } elseif ($final_score >= 61) {
      $final_letter_grade = 'B';
    } elseif ($final_score >= 41) {
      $final_letter_grade = 'C';
    } elseif ($final_score >= 21) {
      $final_letter_grade = 'D';
    }

    $check_query = "
            SELECT final_grade_id 
            FROM final_grades 
            WHERE enrollment_id = $enrollment_id
        ";
    $check_result = mysqli_query($connect, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
      $update_query = "
                UPDATE final_grades
                SET final_score = $final_score, final_letter_grade = '$final_letter_grade'
                WHERE enrollment_id = $enrollment_id
            ";
      mysqli_query($connect, $update_query);
    } else {
      $insert_query = "
                INSERT INTO final_grades (enrollment_id, final_score, final_letter_grade)
                VALUES ($enrollment_id, $final_score, '$final_letter_grade')
            ";
      mysqli_query($connect, $insert_query);
    }

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
          s.student_number,
          s.name,";
    foreach ($components as $component) {
      $component_name = $component['component_name'];
      $component_id = $component['component_id'];
      $sql .= "
          SUM(CASE WHEN ac.component_id = $component_id THEN sas.score ELSE 0 END) AS `$component_name`,";
    }
    $sql .= "
        fg.final_score AS Total,
        fg.final_letter_grade AS Nilai
      FROM students s
      JOIN enrollments e ON s.student_id = e.student_id
      JOIN courses c ON e.course_id = c.course_id
      LEFT JOIN assessment_components ac ON ac.course_id = c.course_id
      LEFT JOIN student_assessment_scores sas ON sas.enrollment_id = e.enrollment_id AND sas.component_id = ac.component_id
      LEFT JOIN final_grades fg ON fg.enrollment_id = e.enrollment_id
      WHERE c.course_id = $course_id
      GROUP BY s.student_number, s.name, fg.final_score, fg.final_letter_grade";


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
  header("Location: ../../views/rekap.php?course_id=$course_id&status=calculated");
} else {
  header("Location: ../../views/rekap.php");
}
