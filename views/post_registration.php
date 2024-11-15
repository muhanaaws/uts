<?php
session_start();

$level = $_SESSION["level"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link rel="stylesheet" href="../styles/styles.css" />
  <title>Register</title>
</head>

<body>
  <div class="vh-100 d-flex justify-content-center align-items-center primary-bg py-5">
    <div class="row w-100">
      <div class="col-lg-7 col-md-6 d-flex align-items-center">
        <div class="ms-5 secondary-text">
          <h1>Website</h1>
          <h6>Rekapitulasi Penilaian Mahasiswa</h6>
          <h6>Universitas Amikom Yogyakarta</h6>
        </div>
      </div>
      <div class="col-lg-5 col-md-6 rounded-3 px-5">
        <div class="d-flex flex-column align-items-center rounded-3 bg-light py-5 px-4">
          <div class="w-25 text-center mb-3">
            <img class="img-fluid" src="/assets/images/logo.png" alt="">
          </div>
          <h4 class="mb-3">Complete Your Profile For <?php echo ucfirst($level) ?></h4>
          <form action="../controllers/auth/register_controller.php" method="POST" class="w-100">
            <div class="w-100 mb-3">
              <label for="name" class="form-label">Nama</label>
              <input type="text" require class="form-control" id="name" name="name" placeholder="Username" />
            </div>
            <?php
            if ($level == 'mahasiswa') {
              echo
              "<div class='w-100 mb-3'>
                <label for='level_id' class='form-label'>NPM</label>
                <input type='text' require class='form-control' id='level_id' name='level_id' placeholder='NPM' />
              </div>";
            }

            if ($level == 'dosen') {
              echo
              "<div class='w-100 mb-3'>
                <label for='level_id' class='form-label'>NIDN</label>
                <input type='text' require class='form-control' id='level_id' name='level_id' placeholder='NIDN' />
              </div>";
            }
            ?>
            <button name="post_regis" class="w-100 btn btn-secondary mb-3">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>