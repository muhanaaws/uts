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
          <a href="dashboard.php" class="d-flex w-100 align-items-center text-center tertiary-bg secondary-text mb-2 link-dark py-2">
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
          <a href='rekap.php' class='d-flex w-100 align-items-center text-center mb-2 link-secondary secondary-text'>
            <img width='16px' src='../assets/icons/rotate-solid.svg' alt='' />
            <span class='ms-4'>Rekap</span>
          </a>
        </div>
      </div>

      <div class="col-10 p-5">
        <div class="row">
          <span class="fs-2 p-0">DASHBOARD <?php echo strtoupper($user_level); ?></span>
        </div>
        <div class="row bg-secondary">
          <span class="fs-5 secondary-text">Semester - Genap 2024/2025</span>
        </div>

        <div class="row mt-3">
          <div class="col-10 mb-3">
            <span class="fs-5 p-0">LIST USER</span>
            <!-- <span class="fs-5">JADWAL PERKULIAHAN</span> -->
          </div>
          <?php
          if ($user_level == 'admin') {
            echo "
              <div class='col-2'>
              
                <!-- START BUTTON ADD USER -->
                <div class='mb-2'>
                  <button class='btn w-100 btn-secondary' data-bs-toggle='modal' data-bs-target='#add-user'>Tambah
                    User</button>
                </div>
                <!-- END BUTTON ADD USER -->

                <!-- START MODAL ADD USER -->
                <div class='modal fade' id='add-user' tabindex='-1' aria-labelledby='add-user-label' aria-hidden='true'>
                  <div class='modal-dialog'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h1 class='modal-title fs-5' id='add-user-label'>Tambah User</h1>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <form action='../controllers/user/user_add_controller.php' method='POST' class='w-100'>
                        <div class='modal-body'>
                          <div class='w-100 mb-3'>
                            <label for='username' class='form-label'>Username</label>
                            <input type='text' require class='form-control' id='username' name='username' placeholder='Username' />
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='level' class='form-label'>Level</label>
                            <select class='form-select form-select-md' require id='level' name='level'
                              aria-label='Small select example'>
                              <option disabled selected>Pilih Level</option>
                              <option value='dosen'>Dosen</option>
                              <option value='mahasiswa'>Mahasiswa</option>
                            </select>
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='pass' class='form-label'>Password</label>
                            <input type='password' require class='form-control' id='pass' name='pass' placeholder='Password' />
                          </div>
                          <hr>
                          <div class='w-100 mb-3'>
                            <label for='name' class='form-label'>Nama</label>
                            <input type='text' require class='form-control' id='name' name='name' placeholder='Nama' />
                          </div>
                          <div class='w-100 mb-3'>
                            <label for='level_id' class='form-label'>Nomor Identitas</label>
                            <input type='text' require class='form-control' id='level_id' name='level_id' placeholder='Nomor Identitas' />
                          </div>
                        </div>
                        <div class='modal-footer'>
                          <button class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                          <button name='add-user' class='btn primary-btn' data-bs-dismiss='modal'>Save</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- END MODAL ADD USER -->
              </div>
            ";
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
                    <th scope='col'>Username</th>
                    <th scope='col'>Level</th>
                    <th scope='col'>Nomor Identitas</th>
                    <th scope='col'>Nama</th>
                    <th scope='col'>Action</th>
                  ";
                }

                if ($user_level == 'dosen') {
                  echo "
                    <th scope='col'>No</th>
                    <th scope='col'>Nama</th>
                    <th scope='col'>NPM</th>
                    <th scope='col'>Mata Kuliah</th>
                    <th scope='col'>Action</th>
                  ";
                }

                if ($user_level == 'mahasiswa') {
                  echo "
                    <th scope='col'>No</th>
                    <th scope='col'>Kode</th>
                    <th scope='col'>Mata Kuliah</th>
                    <th scope='col'>SKS</th>
                    <th scope='col'>Pengampu</th>
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
                    u.user_id,
                    u.username,
                    u.level,
                    COALESCE(s.student_number, l.lecturer_number, '-') AS id_number,
                    COALESCE(s.name, l.name, '-') AS name
                  FROM 
                    users u
                  LEFT JOIN 
                    students s ON u.user_id = s.user_id
                  LEFT JOIN 
                    lecturers l ON u.user_id = l.user_id
                  ORDER BY 
                    CASE 
                        WHEN u.level = 'dosen' THEN 1 
                        WHEN u.level = 'mahasiswa' THEN 2 
                    END;
                ";
              }
              // QUERY FOR DOSEN
              if ($user_level == 'dosen') {
                $sql = "
                  SELECT 
                    s.student_id,
                    s.name,
                    s.student_number,
                    e.enrollment_id,
                    c.course_name
                  FROM  
                    students s 
                  LEFT JOIN 
                  	enrollments e ON e.student_id = s.student_id
                  LEFT JOIN
                  	courses c ON c.course_id = e.course_id
                  LEFT JOIN 
                    lecturers l ON l.lecturer_id = c.lecturer_id
                  WHERE l.lecturer_id = '$id_role'
                ";
              }
              // QUERY FOR MAHASISWA
              if ($user_level == 'mahasiswa') {
                $sql = "
                  SELECT
                      c.course_code,
                      c.course_name,
                      c.credits,
                      e.enrollment_id,
                      l.name AS lecturer_name
                  FROM courses c
                  LEFT JOIN enrollments e ON e.course_id = c.course_id
                  LEFT JOIN lecturers l ON l.lecturer_id = c.lecturer_id
                  WHERE e.student_id = '$id_role'
                ";
              }

              $query = mysqli_query($connect, $sql);

              $i = 1;
              while ($user_data = mysqli_fetch_array($query)) {
                // TABLE DATA FOR ADMIN
                if ($user_level == 'admin') {
                  $username = $user_data['username'];
                  $level_level = $user_data['level'];
                  $id_number = $user_data['id_number'];
                  $name = $user_data['name'];

                  echo "
                    <tr>
                    <td>$i</td>
                    <td>$username</td>
                    <td>$level_level</td>
                    <td>$id_number</td>
                    <td>$name</td>
                    <td>
                    <a class='btn btn-secondary btn-sm' href='../controllers/user/user_delete_controller.php?user_id=$user_data[user_id]'>Hapus</a>
                    </td>
                    </tr>
                  ";
                }
                // TABLE DATA FOR DOSEN
                if ($user_level == 'dosen') {
                  $student_id = $user_data['student_id'];
                  $name = $user_data['name'];
                  $student_number = $user_data['student_number'];
                  $course_name = $user_data['course_name'];
                  $enrollment_id = $user_data['enrollment_id'];

                  echo "
                    <tr>
                    <td>$i</td>
                    <td>$name</td>
                    <td>$student_number</td>
                    <td>$course_name</td>
                    <td>
                    <a class='btn btn-secondary btn-sm' href='../controllers/enrollment/enrollment_delete_controller.php?student_id=$user_data[student_id]&enrollment_id=$enrollment_id'>Hapus</a>
                    </td>
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
                    </tr>
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