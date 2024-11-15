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
          <a href='kelas.php' class='d-flex w-100 align-items-center text-center tertiary-bg secondary-text mb-2 link-dark py-2'>
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
          <a href='rekap.php' class='d-flex w-100 align-items-center text-center mb-2 link-secondary secondary-text'>
            <img width='16px' src='../assets/icons/rotate-solid.svg' alt='' />
            <span class='ms-4'>Rekap</span>
          </a>
        </div>
      </div>

      <div class="col-10 p-5">
        <div class="row">
          <span class="fs-2 p-0">KELAS</span>
        </div>
        <div class="row bg-secondary">
          <span class="fs-5 secondary-text">Semester - Genap 2024/2025</span>
        </div>

        <div class="row mt-3">
          <div class="col-10 mb-3">
            <span class="fs-5 p-0">LIST KELAS</span>
            <!-- <span class="fs-5">JADWAL PERKULIAHAN</span> -->
          </div>
          <?php
          if ($user_level == 'admin') {
            $sql = "
              SELECT 
                  l.lecturer_id, 
                  l.name 
              FROM lecturers l
              LEFT JOIN users u ON u.user_id = l.user_id
              WHERE u.level = 'dosen'
            ";
            $query = mysqli_query($connect, $sql);

            echo "
              <div class='col-2'>
              
                <!-- START BUTTON ADD COURSE -->
                <div class='mb-2'>
                  <button class='btn w-100 btn-secondary' data-bs-toggle='modal' data-bs-target='#add-course'>Tambah
                    Kelas</button>
                </div>
                <!-- END BUTTON ADD COURSE -->

                <!-- START MODAL ADD COURSE -->
                <div class='modal fade' id='add-course' tabindex='-1' aria-labelledby='add-course-label' aria-hidden='true'>
                  <div class='modal-dialog'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h1 class='modal-title fs-5' id='add-course-label'>Tambah Kelas</h1>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <form action='../controllers/course/course_add_controller.php' method='POST' class='w-100'>
                        <div class='modal-body'>
                          <div class='w-100 mb-3'>
                            <label for='course_code' class='form-label'>Kode Mata Kuliah</label>
                            <input type='text' require class='form-control' id='course_code' name='course_code' placeholder='Kode Kelas' />
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='course_name' class='form-label'>Nama Kelas</label>
                            <input type='text' require class='form-control' id='course_name' name='course_name' placeholder='Nama Kelas' />
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='credit' class='form-label'>Jumlah SKS</label>
                            <input type='text' require class='form-control' id='credit' name='credit' placeholder='Jumlah SKS' />
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='lecturer' class='form-label'>Pengampu</label>
                            <select class='form-select form-select-md' require id='lecturer' name='lecturer'
                              aria-label='Small select example'>
                              <option disabled selected>Pilih Pengampu</option>";
            while ($dosen_data = mysqli_fetch_array($query)) {
              $id_dosen = $dosen_data['lecturer_id'];
              $nama_dosen = $dosen_data['name'];
              echo "<option value='$id_dosen'>$nama_dosen</option>";
            }

            echo "</select>
                          </div>                        
                        </div>
                        <div class='modal-footer'>
                          <button class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                          <button name='add-course' class='btn primary-btn' data-bs-dismiss='modal'>Save</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- END MODAL ADD COURSE -->
              </div>
            ";
          }

          if ($user_level == 'dosen') {
          }
          ?>
          <!-- START TABLE USER -->
          <table class="table">
            <thead class="table-secondary">
              <tr>
                <?php
                if ($user_level == 'admin') {
                  echo "
                    <th scope='col'>No</th>
                    <th scope='col'>Kode</th>
                    <th scope='col'>Mata Kuliah</th>
                    <th scope='col'>Jumlah Sks</th>
                    <th scope='col'>Jumlah Mahasiswa</th>
                    <th scope='col'>Pengampu</th>
                    <th scope='col'>Action</th>
                  ";
                }

                if ($user_level == 'dosen') {
                  echo "
                    <th scope='col'>No</th>
                    <th scope='col'>Kode</th>
                    <th scope='col'>Mata Kuliah</th>
                    <th scope='col'>Jumlah Sks</th>
                    <th scope='col'>Jumlah Mahasiswa</th>
                  ";
                }

                if ($user_level == 'mahasiswa') {
                  echo "
                    <th scope='col'>No</th>
                    <th scope='col'>Kode</th>
                    <th scope='col'>Mata Kuliah</th>
                    <th scope='col'>Jumlah Sks</th>
                    <th scope='col'>Pengampu</th>
                    <th scope='col'>Action</th>
                  ";
                }
                ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql;
              // QUERY FOR ADMIN
              if ($user_level == 'admin') {
                $sql = "
                  SELECT 
                      c.course_id,
                      c.course_code,
                      c.course_name,
                      c.credits,
                      l.lecturer_id AS lecturer_id,
                      l.name AS lecturer_name,
                      COUNT(s.student_id) AS student_count
                  FROM courses c
                  LEFT JOIN lecturers l ON l.lecturer_id = c.lecturer_id
                  LEFT JOIN enrollments e ON e.course_id = c.course_id
                  LEFT JOIN students s ON s.student_id = e.student_id
                  GROUP BY c.course_id, c.course_code, c.course_name, c.credits, l.name;
                ";
              }
              // QUERY FOR DOSEN
              if ($user_level == 'dosen') {
                $sql = "
                  SELECT 
                      c.course_id,
                      c.course_code,
                      c.course_name,
                      c.credits,
                      COUNT(s.student_id) AS student_count
                  FROM courses c
                  LEFT JOIN lecturers l ON l.lecturer_id = c.lecturer_id
                  LEFT JOIN enrollments e ON e.course_id = c.course_id
                  LEFT JOIN students s ON s.student_id = e.student_id
                  WHERE l.lecturer_id = '$id_role'
                  GROUP BY c.course_id, c.course_code, c.course_name, c.credits;
                ";
              }
              // QUERY FOR MAHASISWA
              if ($user_level == 'mahasiswa') {
                $sql = "
                  SELECT
                      c.course_id,
                      c.course_code,
                      c.course_name,
                      c.credits,
                      l.name AS lecturer_name,
                      e.student_id 
                  FROM courses c
                  LEFT JOIN enrollments e ON e.course_id = c.course_id 
                  AND (e.student_id = '$id_role' OR e.student_id IS NULL)
                  LEFT JOIN lecturers l ON l.lecturer_id = c.lecturer_id
                ";
              }

              $query = mysqli_query($connect, $sql);

              $sql_lecturer = "
                SELECT 
                    l.lecturer_id, 
                    l.name 
                FROM lecturers l
                LEFT JOIN users u ON u.user_id = l.user_id
                WHERE u.level = 'dosen'
              ";
              $query_lecturer = mysqli_query($connect, $sql_lecturer);

              $i = 1;
              while ($user_data = mysqli_fetch_array($query)) {
                // TABLE DATA FOR ADMIN
                if ($user_level == 'admin') {
                  $course_code = $user_data['course_code'];
                  $course_name = $user_data['course_name'];
                  $credits = $user_data['credits'];
                  $student_count = $user_data['student_count'];
                  $lecturer_name = $user_data['lecturer_name'];
                  $lecturer_id = $user_data['lecturer_id'];

                  echo "
                    <tr>
                      <td>$i</td>
                      <td>$course_code</td>
                      <td>$course_name</td>
                      <td>$credits</td>
                      <td>$student_count</td>
                      <td>$lecturer_name</td>
                      <td>
                      <button class='btn btn-secondary btn-sm' data-bs-toggle='modal' data-bs-target='#edit-course-$user_data[course_id]'>Edit</button>
                      <a class='btn btn-secondary btn-sm' href='../controllers/course/course_delete_controller.php?course_id=$user_data[course_id]'>Hapus</a>
                      </td>
                    </tr>
                  ";

                  echo "
                  <!-- START MODAL EDIT COURSE -->
                  <div class='modal fade' id='edit-course-$user_data[course_id]' tabindex='-1' aria-labelledby='edit-course-label' aria-hidden='true'>
                    <div class='modal-dialog'>
                      <div class='modal-content'>
                        <div class='modal-header'>
                          <h1 class='modal-title fs-5' id='edit-course-label'>Edit Course</h1>
                          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <form action='../controllers/course/course_edit_controller.php' method='POST' class='w-100'>
                          <div class='modal-body'>
                          <div class='w-100 mb-3 visually-hidden'>
                            <label for='course_id' class='form-label'>Kode Mata Kuliah</label>
                            <input type='text' require class='form-control' id='course_id' name='course_id' placeholder='Kode Kelas' value='$user_data[course_id]' />
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='course_code' class='form-label'>Kode Mata Kuliah</label>
                            <input type='text' require class='form-control' id='course_code' name='course_code' placeholder='Kode Kelas' value='$course_code' />
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='course_name' class='form-label'>Nama Kelas</label>
                            <input type='text' require class='form-control' id='course_name' name='course_name' placeholder='Nama Kelas' value='$course_name' />
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='credit' class='form-label'>Jumlah SKS</label>
                            <input type='text' require class='form-control' id='credit' name='credit' placeholder='Jumlah SKS' value='$credits' />
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='lecturer' class='form-label'>Pengampu</label>
                            <select class='form-select form-select-md' require id='lecturer' name='lecturer'
                              aria-label='Small select example'>
                              <option disabled >Pilih Pengampu</option>
                              ";

                  mysqli_data_seek($query_lecturer, 0);

                  while ($dosen_data = mysqli_fetch_array($query_lecturer)) {
                    $id_dosen = $dosen_data['lecturer_id'];
                    $nama_dosen = $dosen_data['name'];

                    if ($id_dosen == $lecturer_id) {
                      echo "<option selected value='$id_dosen'>$nama_dosen</option>";
                    } else {
                      echo "<option value='$id_dosen'>$nama_dosen</option>";
                    }
                  }

                  echo "
                  </select>
                          </div>                        
                        </div>
                        <div class='modal-footer'>
                          <button class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                          <button name='edit-course' class='btn primary-btn' data-bs-dismiss='modal'>Save</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- END MODAL EDIT COURSE -->
                  ";
                }
                // TABLE DATA FOR DOSEN
                if ($user_level == 'dosen') {
                  $course_code = $user_data['course_code'];
                  $course_name = $user_data['course_name'];
                  $credits = $user_data['credits'];
                  $student_count = $user_data['student_count'];

                  echo "
                    <tr>
                      <td>$i</td>
                      <td>$course_code</td>
                      <td>$course_name</td>
                      <td>$credits</td>
                      <td>$student_count</td>
                    </tr>
                  ";
                }
                // TABLE DATA FOR MAHASISWA
                if ($user_level == 'mahasiswa') {
                  $course_code = $user_data['course_code'];
                  $course_name = $user_data['course_name'];
                  $credits = $user_data['credits'];
                  $lecturer_name = $user_data['lecturer_name'];

                  echo "
                    <tr>
                      <td>$i</td>
                      <td>$course_code</td>
                      <td>$course_name</td>
                      <td>$credits</td>
                      <td>$lecturer_name</td>
                      <td>
                  ";
                  if ($id_role == $user_data['student_id']) {
                    echo " 
                        <a class='btn btn-secondary btn-sm disabled' href='../controllers/enrollment/enrollment_add_controller.php?course_id=$user_data[course_id]&student_id=$id_role'>Pilih</a>
                        </td>
                      </tr>
                    ";
                  } else {
                    echo " 
                        <a class='btn btn-secondary btn-sm' href='../controllers/enrollment/enrollment_add_controller.php?course_id=$user_data[course_id]&student_id=$id_role'>Pilih</a>
                        </td>
                      </tr>
                    ";
                  }
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