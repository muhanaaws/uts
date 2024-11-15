<?php
session_start();
include '../../configs/config.php';

if (isset($_POST['register'])) {
  $username = $_POST['username'];
  $pass = md5($_POST['pass']);
  $level = $_POST['level'];

  $check_user_query = "SELECT * FROM users WHERE username = '$username'";
  $check_user_result = mysqli_query($connect, $check_user_query);
  if (mysqli_num_rows($check_user_result) > 0) {
    header("Location: ../../views/register.php?error=username_taken");
    die("Username is already taken.");
  }

  $sql = "INSERT INTO users (username,password,level) VALUES ('$username', '$pass', '$level')";
  $query = mysqli_query($connect, $sql);

  if ($query) {
    $query = mysqli_query($connect, "select * from users where username='$username' and password='$pass'");
    $result = mysqli_num_rows($query);
    $user = mysqli_fetch_array($query);

    $_SESSION['user_id'] = $user["user_id"];
    $_SESSION['level'] = $level;

    if ($level == 'admin') {
      header("location:../../index.php?status=success");
      exit;
    } else {
      header("location:../../views/post_registration.php");
    }
  } else {
    header("location:../../views/register.php?status=failed");
    exit;
  }
}

if (isset($_POST['post_regis'])) {
  $level = $_SESSION["level"];
  $user_id = $_SESSION['user_id'];

  $name = $_POST['name'];
  $level_id = $_POST['level_id'];
  $sql;

  if ($level == 'mahasiswa') {
    $sql = "INSERT INTO students (user_id,name,student_number) VALUES ('$user_id', '$name', '$level_id')";
  }
  if ($level == 'dosen') {
    $sql = "INSERT INTO lecturers (user_id,name,lecturer_number) VALUES ('$user_id', '$name', '$level_id')";
  }
  header("location:../../index.php?status=$user_id");

  $query = mysqli_query($connect, $sql);
  if ($query) {
    header("location:../../index.php?status=success");
    exit;
  } else {
    header("location:../../views/register.php?status=failed");
    exit;
  }
}
