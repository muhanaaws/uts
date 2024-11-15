<?php
session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location:../index.php?error=login_first");
}

$user_level = $_SESSION["level"];
$id_role = $_SESSION["id_role"];

$rekap_components = $_SESSION['rekap_components'] ?? [];
$rekap_students = $_SESSION['rekap_students'] ?? [];
$rekap_course_id = $_SESSION['rekap_course_id'] ?? '';

require "../configs/config.php"
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link rel="stylesheet" href="../styles/styles.css" />
  <title>Dashboard</title>
</head>

<body>
  <div class="container-fluid p-0" style="height: 100vh">
    <nav class="navbar border-bottom primary-bg w-100">
      <div class="w-100 d-flex align-items-center justify-content-between p-2">
        <a class="navbar-brand d-flex flex-row align-items-center" href="dashboard.html">
          <img width="64px" src="../assets/images/logo.png" alt="" />
          <span class="fs-1 secondary-text">Website</span>
        </a>
        <a class="btn btn-light" href="../controllers/auth/logout_controller.php">
          <span>Logout</span>
          <img width=" 16px" src="../assets/icons/arrow-right-from-bracket-solid.svg" alt="" />
        </a>
      </div>
    </nav>

    <div class="row h-100">
      <div class="col-2 primary-bg p-0">
        <div class="row d-flex flex-column align-items-center text-center p-2 my-4">
          <div class="w-50 text-center mb-4">
            <img class="img-fluid" src="../assets/icons/circle-user-regular.svg" alt="" />
          </div>

          <?php
          echo "<span class='secondary-text'>" . $_SESSION["username"] . "</span>";
          ?>
          <!-- <span>Nama dosen</span> -->
        </div>

        <div class="row d-flex flex-column m-0 w-100 align-items-center py-2 ps-4">
          <a href="dashboard.php" class="d-flex w-100 align-items-center text-center mb-2 link-secondary secondary-text">
            <img width="16px" src="../assets/icons/house-solid.svg" alt="" />
            <span class="ms-4">Dashboard</span>
          </a>
          <a href='kelas.php' class='d-flex w-100 align-items-center text-center mb-2 link-secondary secondary-text'>
            <img width='16px' src='../assets/icons/school-solid.svg' alt='' />
            <span class='ms-4'>Kelas</span>
          </a>
          <?php
          if ($user_level == 'dosen') {
            echo "
                <a href='penilaian.php' class='d-flex w-100 align-items-center text-center mb-2 link-secondary secondary-text'>
                 <img width='16px' src='../assets/icons/pen-to-square-solid.svg' alt='' />
                 <span class='ms-4'>Penilaian</span>
                </a>
                <a href='input_nilai.php' class='d-flex w-100 align-items-center text-center mb-2 link-secondary secondary-text'>
                  <img width='16px' src='../assets/icons/circle-plus-solid.svg' alt='' />
                  <span class='ms-4'>Input Nilai</span>
                </a>
               ";
          }
          ?>
          <a href='rekap.php' class='d-flex w-100 align-items-center text-center tertiary-bg secondary-text mb-2 link-dark py-2'>
            <img width='16px' src='../assets/icons/rotate-solid.svg' alt='' />
            <span class='ms-4'>Rekap</span>
          </a>
        </div>
      </div>

      <div class="col-10 p-5">
        <div class="row">
          <span class="fs-2 p-0">REKAPITULASI NILAI</span>
        </div>
        <div class="row bg-secondary">
          <span class="fs-5 secondary-text">Semester - Genap 2024/2025</span>
        </div>

        <div class="row mt-3">
          <div class="col mb-3">
            <span class="fs-5 p-0">REKAP NILAI</span>
          </div>
          <?php

          $sql_course = "
            SELECT 
                c.course_id,
                c.course_name
            FROM courses c
            WHERE c.lecturer_id = 1
          ";

          $query_course = mysqli_query($connect, $sql_course);

          // if ($user_level == 'admin' || $user_level == 'dosen') {
          mysqli_data_seek($query_course, 0);
          echo "
              <div class='col mb-2 d-flex align-center'>
                <form class='d-flex w-100' action='../controllers/final/final_find_controller.php' method='POST' role='search'>
                  <select class='form-select form-select-md me-1' id='course_id' name='course_id' aria-label='Small select example'>
                    <option disabled selected>Pilih Kelas</option>
            ";
          while ($data = mysqli_fetch_array($query_course)) {

            if ($rekap_course_id == $data['course_id']) {
              echo "
                  <option selected value='$data[course_id]'>$data[course_name]</option>
                ";
            } else {
              echo "
                  <option value='$data[course_id]'>$data[course_name]</option>
                ";
            }
          }
          echo "
                  </select>
                  <button name='find-final' class='btn w-25 btn-secondary'>TAMPILKAN</button>
                </form>
              </div>
            ";
          // }
          ?>

          <table class="table">
            <thead class="table-secondary">
              <tr>
                <th scope="col">NIM</th>
                <th scope="col">Nama</th>
                <?php
                if (empty($rekap_components)) {
                  echo "
                  <th scope='col'>Total</th>
                  <th scope='col'>Grade</th>
                  ";
                }
                foreach ($rekap_components as $component):
                  echo "
                  <th scope='col'>$component[component_name]</th>                  
                  ";
                endforeach;

                if ($user_level == 'admin' || $user_level == 'dosen') {
                  echo "<th scope='col'>
                    <a class='btn btn-secondary btn-sm' href='../controllers/finaL/finaL_add_controller.php?course_id=$rekap_course_id'>Kalkulasi</a>
                  </th>";
                }
                ?>
                <!-- <th scope="col">Kehadiran</th> -->
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($rekap_students as $student):
                echo "
                <tr>             
                  <td>$student[student_number]</td>
                  <td>$student[name]</td>
                ";
                foreach ($rekap_components as $component):
                  $component_name = $component['component_name'];
                  echo "
                  <td>$student[$component_name]</td>                  
                  ";
                endforeach;
                $student_data = json_encode($student);
                if ($user_level == 'dosen') {
                  echo "  
                  <td>
                  <a class='btn btn-secondary btn-sm me-1' href='../controllers/final/final_edit_controller.php?student_data=$student_data'>Edit</button>
                  <a class='btn btn-secondary btn-sm' href='../controllers/final/final_delete_controller.php?student_data=$student_data'>Hapus</a>
                  </td>
                  </tr>                  
                  ";
                }
              endforeach
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>