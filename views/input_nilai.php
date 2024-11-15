<?php
session_start();

$status = $_SESSION["status"];

if ($status != "login") {
  header("location:../index.php?error=login_first");
}

$user_level = $_SESSION["level"];
$id_role = $_SESSION["id_role"];

$nilai_student_data = $_SESSION['nilai_student_data'] ?? null;
$nilai_student_courses = $_SESSION['nilai_student_courses'] ?? [];
$nilai_course_ass = $_SESSION['nilai_course_ass'] ?? [];
$nilai_course_selected = $_SESSION['nilai_course_selected'] ?? null;
$nilai_enrollment_selected = $_SESSION['nilai_enrollment_selected'] ?? null;

$return_url = isset($_GET['return_url']) ? $_GET['return_url'] : '../../views/input_nilai.php';

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
                <a href='input_nilai.php' class='d-flex w-100 align-items-center text-center tertiary-bg secondary-text mb-2 link-dark py-2'>
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
          <div class="col">
            <span class="fs-2 pb-4">TAMBAHKAN NILAI MAHASISWA</span>
          </div>
          <div class="col d-flex align-center">
            <form class="d-flex w-100" action='../controllers/user/user_find_controller.php' method='POST' role="search">
              <input class="form-control me-1" type="search" placeholder="NIM Mahasiswa" aria-label="Search" name="student_number" />
              <button name="find-student" class="w-25 btn btn-secondary">Cari</button>
            </form>
          </div>
        </div>

        <div class="row border mt-2 px-4 pt-2 pb-4">
          <div class="row w-100">
            <div class="col">
              <span class="fs-4 mb-3">Data Mahasiswa</span>
            </div>
            <div class="col">
              <span class="fs-4 mb-3">Penilaian</span>
            </div>
          </div>
          <div class="row d-flex justify-content-center">
            <div class="col">
              <div class="row">
                <div class="w-100 mb-4">
                  <label for="nim" class="form-label">NIM</label>
                  <input type="text" class="form-control" id="nim" name="nim" placeholder="Nomor Induk Mahasiswa"
                    disabled readonly value="<?php echo isset($nilai_student_data['student_number']) ? $nilai_student_data['student_number'] : ''; ?>" />
                </div>
                <div class="w-100 mb-4">
                  <label for="nama" class="form-label">Nama</label>
                  <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap Mahasiswa"
                    disabled readonly value="<?php echo isset($nilai_student_data['name']) ? $nilai_student_data['name'] : ''; ?>" />
                </div>
                <form action="../controllers/course/course_find_controller.php" method='POST'>
                  <div class="w-100 d-flex mb-4">
                    <div class="w-75">
                      <label for="course_id" class="form-label">Mata Kuliah</label>
                      <select class="form-select form-select-md" id="course_id" name="course_id"
                        aria-label="Small select example">
                        <option disabled selected>Pilih Mata Kuliah</option>

                        <?php foreach ($nilai_student_courses as $course): ?>
                          <option value="<?php echo $course['course_id']; ?>"
                            <?php echo ($nilai_course_selected == $course['course_id']) ? 'selected' : ''; ?>>
                            <?php echo "$course[course_code] - $course[course_name]"; ?>
                          </option>
                        <?php endforeach; ?>

                      </select>
                    </div>
                    <div class="w-25 ms-2 d-flex flex-column">
                      <label for="nim" class="form-label opacity-0">Konfirmasi</label>
                      <button name="find-course" class="w-100 btn btn-secondary">Cari</button>
                    </div>
                  </div>
                </form>
                <!-- <div class="w-100 mb-4">
                  <label for="kehadiran" class="form-label">Kehadiran</label>
                  <input type="text" class="form-control" id="kehadiran" name="kehadiran" placeholder="Jumlah Kehadiran"
                    disabled readonly />
                </div> -->
              </div>
            </div>
            <div class="col">
              <div id="grade-add" class="row ">
                <form id="grade-form" action="../controllers/grade/grade_add_controller.php" method='POST'>
                  <input type="hidden" name="return_url" value="<?php echo $return_url; ?>">
                  <?php foreach ($nilai_course_ass as $assessment): ?>
                    <div class="w-100 mb-4">
                      <label for="<?php echo $assessment['component_id']; ?>" class="form-label"><?php echo $assessment['component_name']; ?></label>
                      <input type="number" min="1" max="100" class="form-control" id="<?php echo $assessment['component_id']; ?>" name="grades[<?php echo $assessment['component_id']; ?>][<?php echo isset($assessment['score_id']) ? $assessment['score_id'] : 'default'; ?>]" placeholder="<?php echo $assessment['component_name']; ?>"
                        value="<?php echo isset($assessment['score']) ? $assessment['score'] : ''; ?>" />
                    </div>
                  <?php endforeach; ?>
                </form>

              </div>
            </div>
            <div class="row d-flex justify-content-center">
              <button name="add-grade" form="grade-form" class="btn btn-secondary">TAMBAHKAN</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>