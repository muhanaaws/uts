<?php
include '../../configs/config.php';

$username = $_POST['username'];
$pass = md5($_POST['pass']);

if (!empty($username) && !empty($pass)) {
  $query = mysqli_query($connect, "select * from users where username='$username' and password='$pass'");
  $result = mysqli_num_rows($query);
  $user = mysqli_fetch_array($query);

  if ($result > 0) {
    session_start();

    $_SESSION["username"] = $username;
    $_SESSION["id"] = $user["user_id"];
    $_SESSION["level"] = $user["level"];
    $_SESSION["status"] = "login";

    if ($user["level"] == 'mahasiswa') {
      $query_user = mysqli_query($connect, "SELECT * FROM students WHERE user_id = '$user[user_id]'");
      $student_data = mysqli_fetch_array($query_user);
      $_SESSION["id_role"] = $student_data["student_id"];
    }

    if ($user["level"] == 'dosen') {
      $query_user =  mysqli_query($connect, "SELECT * FROM lecturers WHERE user_id = '$user[user_id]'");
      $lecturer_data = mysqli_fetch_array($query_user);
      $_SESSION["id_role"] = $lecturer_data["lecturer_id"];
    }

    if ($user["level"] == 'admin') {
      $_SESSION["id_role"] = "admin";
    }

    header("location:../../views/dashboard.php");
  } else {
    header("location:../../index.php?app=failed");
  }
} else {
  header("location:../../index.php?app=error");
}
