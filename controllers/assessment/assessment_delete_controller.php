<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location: ../../index.php");
}

$component_id = @$_GET["component_id"];

if (empty($component_id)) {
  header("Location: ../../views/penilaian.php");
} else {
  $query = "DELETE FROM assessment_components where component_id='$component_id'";

  mysqli_query($connect, $query);

  header("Location: ../../views/penilaian.php");
}
