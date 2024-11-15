<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

if (isset($_POST['add-user'])) {
  $username = $_POST['username'];
  $pass = md5($_POST['pass']);
  $level = $_POST['level'];

  $check_user_query = "SELECT * FROM users WHERE username = '$username'";
  $check_user_result = mysqli_query($connect, $check_user_query);
  if (mysqli_num_rows($check_user_result) > 0) {
    header("Location: ../../views/dashboard.php?error=username_taken");
    die("Username is already taken.");
  }

  $sql = "INSERT INTO users (username,password,level) VALUES ('$username', '$pass', '$level')";
  $query = mysqli_query($connect, $sql);

  if ($query) {
    $query = mysqli_query($connect, "select * from users where username='$username' and password='$pass'");
    $result = mysqli_num_rows($query);
    $user = mysqli_fetch_array($query);

    $name = $_POST['name'];
    $level_id = $_POST['level_id'];
    $user_id = $user["user_id"];

    if ($level == 'mahasiswa') {
      $sql = "INSERT INTO students (name,user_id,student_number) VALUES ('$name', '$user_id', '$level_id')";
    }

    if ($level == 'dosen') {
      $sql = "INSERT INTO lecturers (name,user_id,lecturer_number) VALUES ('$name', '$user_id', '$level_id')";
    }

    $sec_query = mysqli_query($connect, $sql);

    if ($sec_query) {
      header("Location: ../../views/dashboard.php");
    } else {
      header("Location: ../../views/dashboard.php");
      die("Failed to create user");
    }
  } else {
    header("Location: ../../views/dashboard.php");
    die("Failed to create user");
  }
} else {
  header("Location: ../../views/dashboard.php");
  die("Failed to create user");
}
