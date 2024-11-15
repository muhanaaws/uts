<?php
require "../../configs/config.php";

session_start();

$status = $_SESSION["status"];

if ($status != "login") {
    header("location: ../../index.php");
}

$user_id = @$_GET["user_id"];

if (empty($user_id)) {
    header("Location: ../../views/dashboard.php");
} else {
    $query = "DELETE FROM users where user_id='$user_id'";

    mysqli_query($connect, $query);

    header("Location: ../../views/dashboard.php");
}
