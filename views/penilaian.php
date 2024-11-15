<?php
session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location:../index.php?error=login_first");
}

$user_level = $_SESSION["level"];
$id_role = $_SESSION["id_role"];

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
                <a href='penilaian.php' class='d-flex w-100 align-items-center text-center tertiary-bg secondary-text mb-2 link-dark py-2'>
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
          <a href='rekap.php' class='d-flex w-100 align-items-center text-center mb-2 link-secondary secondary-text'>
            <img width='16px' src='../assets/icons/rotate-solid.svg' alt='' />
            <span class='ms-4'>Rekap</span>
          </a>
        </div>
      </div>

      <div class="col-10 p-5">
        <div class="row">
          <span class="fs-2 p-0">Penilaian</span>
        </div>
        <div class="row bg-secondary">
          <span class="fs-5 secondary-text">Semester - Genap 2024/2025</span>
        </div>

        <div class="row mt-3">
          <div class="col-10 mb-3">
            <span class="fs-5 p-0">LIST PENILAIAN</span>
            <!-- <span class="fs-5">JADWAL PERKULIAHAN</span> -->
          </div>

          <?php
          if ($user_level == 'dosen') {
            $sql = "
              SELECT 
                  c.course_id,
                  c.course_name
              FROM courses c
              WHERE c.lecturer_id = 1
            ";

            $query = mysqli_query($connect, $sql);

            echo "
              <div class='col-2'>
              
                <!-- START BUTTON ADD NILAI -->
                <div class='mb-2'>
                  <button class='btn w-100 btn-secondary' data-bs-toggle='modal' data-bs-target='#add-penilaian'>Tambah
                    Penilaian</button>
                </div>
                <!-- END BUTTON ADD NILAI -->
  
                <!-- START MODAL ADD NILAI -->
                <div class='modal fade' id='add-penilaian' tabindex='-1' aria-labelledby='add-penilaian-label' aria-hidden='true'>
                  <div class='modal-dialog'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h1 class='modal-title fs-5' id='add-penilaian-label'>Tambah Nilai</h1>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <form action='../controllers/assessment/assessment_add_controller.php' method='POST' class='w-100'>
                        <div class='modal-body'>
                          <div class='w-100 mb-3'>
                            <label for='course_id' class='form-label'>Mata Kuliah</label>
                            <select class='form-select form-select-md' require id='course_id' name='course_id'
                              aria-label='Small select example'>
                              <option disabled selected>Pilih Kelas</option>
                              ";
            while ($data = mysqli_fetch_array($query)) {
              $course_id = $data['course_id'];
              $course_name = $data['course_name'];

              echo "
              <option value='$course_id'>$course_name $course_id</option>
              ";
            }

            echo "
                              </select>
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='component_name' class='form-label'>Aspek Penilaian</label>
                            <input type='text' require class='form-control' id='component_name' name='component_name' placeholder='Aspek Penilaian' />
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='component_weight' class='form-label'>Bobot %</label>
                            <input type='number' min='0' max='100' require class='form-control' id='component_weight' name='component_weight' placeholder='Bobot' />
                          </div>
                        </div>
                        <div class='modal-footer'>
                          <button class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                          <button name='add-assessment' class='btn primary-btn' data-bs-dismiss='modal'>Save</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- END MODAL ADD NILAI -->
              </div>
            ";

            echo "<!-- START MODAL EDIT NILAI -->
                <div class='modal fade' id='edit-penilaian' tabindex='-1' aria-labelledby='edit-penilaian-label' aria-hidden='true'>
                  <div class='modal-dialog'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h1 class='modal-title fs-5' id='edit-penilaian-label'>Edit Penilaian</h1>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <form action='../controllers/assessment/assessment_add_controller.php' method='POST' class='w-100'>
                        <div class='modal-body'>
                          <div class='w-100 mb-3'>
                            <label for='course_id' class='form-label'>Mata Kuliah</label>
                            <select class='form-select form-select-md' require id='course_id' name='course_id'
                              aria-label='Small select example'>
                              <option disabled selected>Pilih Kelas</option>
                              ";
            while ($data = mysqli_fetch_array($query)) {
              $course_id = $data['course_id'];
              $course_name = $data['course_name'];

              echo "
              <option value='$course_id'>$course_name</option>
              ";
            }

            echo "
                              </select>
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='component_name' class='form-label'>Aspek Penilaian</label>
                            <input type='text' require class='form-control' id='component_name' name='component_name' placeholder='Aspek Penilaian' />
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='component_weight' class='form-label'>Bobot %</label>
                            <input type='number' min='0' max='100' require class='form-control' id='component_weight' name='component_weight' placeholder='Bobot' />
                          </div>
                        </div>
                        <div class='modal-footer'>
                          <button class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                          <button name='edit-assessment' class='btn primary-btn' data-bs-dismiss='modal'>Save</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- END MODAL EDIT NILAI -->
            ";
          }
          ?>

          <!-- START TABLE USER -->
          <table class="table">
            <thead class="table-secondary">
              <tr>
                <th scope='col'>No</th>
                <th scope='col'>Kode</th>
                <th scope='col'>Mata Kuliah</th>
                <th scope='col'>Aspek Penilaian</th>
                <th scope='col'>Bobot</th>
                <th scope='col'>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql;
              // QUERY FOR DOSEN
              if ($user_level == 'dosen') {
                $sql = "
                  SELECT
                      ac.component_id,
                      ac.course_id,
                      ac.component_name,
                      ac.component_weight,
                      c.course_code,
                      c.course_name
                  FROM assessment_components ac
                  LEFT JOIN courses c ON c.course_id = ac.course_id
                ";
              }

              $query = mysqli_query($connect, $sql);

              $sql_course = "
                SELECT 
                    c.course_id,
                    c.course_name
                FROM courses c
                WHERE c.lecturer_id = 1
              ";

              $query_course = mysqli_query($connect, $sql_course);

              $i = 1;
              while ($user_data = mysqli_fetch_array($query)) {
                // TABLE DATA FOR DOSEN
                if ($user_level == 'dosen') {
                  $component_id = $user_data['component_id'];
                  $course_id = $user_data['course_id'];
                  $component_name = $user_data['component_name'];
                  $component_weight = $user_data['component_weight'];
                  $course_code = $user_data['course_code'];
                  $course_name = $user_data['course_name'];

                  echo "
                    <tr>
                      <td>$i</td>
                      <td>$course_code</td>
                      <td>$course_name</td>
                      <td>$component_name</td>
                      <td>$component_weight</td>
                      <td>
                      <button class='btn btn-secondary btn-sm' data-bs-toggle='modal' data-bs-target='#edit-penilaian-$user_data[component_id]'>Edit</button>
                      <a class='btn btn-secondary btn-sm' href='../controllers/assessment/assessment_delete_controller.php?component_id=$user_data[component_id]'>Hapus</a>
                      </td>
                    </tr>
                  ";

                  echo "<!-- START MODAL EDIT NILAI -->
                <div class='modal fade' id='edit-penilaian-$user_data[component_id]' tabindex='-1' aria-labelledby='edit-penilaian-label' aria-hidden='true'>
                  <div class='modal-dialog'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h1 class='modal-title fs-5' id='edit-penilaian-label'>Edit Penilaian</h1>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <form action='../controllers/assessment/assessment_edit_controller.php' method='POST' class='w-100'>
                        <div class='modal-body'>
                          <div class='w-100 mb-3'>
                            <label for='course_id' class='form-label'>Mata Kuliah</label>
                            <select class='form-select form-select-md' require id='course_id' name='course_id'
                              aria-label='Small select example'>
                              <option disabled>Pilih Kelas</option>
                              ";
                  mysqli_data_seek($query_course, 0);

                  while ($data = mysqli_fetch_array($query_course)) {

                    if ($course_id == $data['course_id']) {
                      echo "
                        <option selected value='$data[course_id]'>$data[course_name]</option>
                      ";
                    } else {
                      echo "
                        <option value='$data[course_id]'>$data[course_name]</option>
                      ";
                    }
                  }
                  $component_weight *= 100;
                  echo "
                              </select>
                          </div>
                          <div class='w-100 mb-3 visually-hidden'>
                            <label for='component_id' class='form-label'>Aspek Penilaian</label>
                            <input type='text' require class='form-control' id='component_id' name='component_id' placeholder='Aspek Penilaian' value='$component_id'/>
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='component_name' class='form-label'>Aspek Penilaian</label>
                            <input type='text' require class='form-control' id='component_name' name='component_name' placeholder='Aspek Penilaian' value='$component_name'/>
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='component_weight' class='form-label'>Bobot %</label>
                            <input type='number' min='0' max='100' require class='form-control' id='component_weight' name='component_weight' placeholder='Bobot' value='$component_weight'/>
                          </div>
                        </div>
                        <div class='modal-footer'>
                          <button class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                          <button name='edit-assessment' class='btn primary-btn' data-bs-dismiss='modal'>Save</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- END MODAL EDIT NILAI -->
            ";
                }
                $i++;
              }
              ?>

            </tbody>
          </table>
          <!-- END TABLE USER -->
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>