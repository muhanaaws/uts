<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

if (isset($_POST["edit-nilai"])) {
  $id_mhs = $_POST['id_mhs'];
  $id_dosen = $_POST['id_dosen'];
  $subject = $_POST['subject'];
  $score = $_POST['score'];
  $id_grade = $_POST['grade'];
  $grade;

  if ($score >= 81 && $score <= 100) {
    $grade = 'A';
  } elseif ($score >= 61 && $score <= 80) {
    $grade = 'B';
  } elseif ($score >= 41 && $score <= 60) {
    $grade = 'C';
  } elseif ($score >= 21 && $score <= 40) {
    $grade = 'D';
  } else {
    $grade = 'E';
  }

  $sql = "UPDATE grades SET student_id='$id_mhs', instructor_id='$id_dosen', subject='$subject', score='$score', grade='$grade' WHERE id='$id_grade'";
  $query = mysqli_query($connect, $sql);

  if ($query) {
    header("Location: ../../views/dashboard.php");
  } else {
    die("Failed to update nilai");
  }
} else {
  header("Location: ../../views/dashboard.php");
}
