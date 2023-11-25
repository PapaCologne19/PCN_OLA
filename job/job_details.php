<?php
include "../database/connection.php";
include "../body/function.php";
session_start();
$errors = array();

if (isset($_POST['apply'])) {
  $job_id = $_POST['job_id'];
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo "Hello";
  } else {
    header("location: ../applicant/JobApply_Login.php?job_id=$job_id");
    $_SESSION['werror'] = "It appears that you are not currently logged in. Please log in before applying for this job.";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/img/alegario_logo.png" type="image/x-icon">

  <!-- Bootstrap Icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@500&family=Inter&family=Poiret+One&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:wght@500;600&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,400;1,500;1,700;1,900&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

  <!-- Main Quill library -->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

  <!-- JS -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>


  <link rel="stylesheet" href="../css/style/search_results.css">
  <link rel="stylesheet" href="../css/style/header.css">
  <link rel="stylesheet" href="../css/bootstrap.css">

  <style>
    .inside-container {
      padding-right: 20rem !important;
    }

    @media screen and (max-width: 770px) {
      .inside-container {
        padding: 1.3rem !important;
      }
    }
  </style>


  <title>Job Details</title>

</head>

<body>
  <?php
  include '../body/header.php'; ?>

  <br><br><br><br><br><br><br><br>

  <div class="head">
    <div class="container">
      <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../index.php" style="color: #000;">Home</a></li>
          <li class="breadcrumb-item"><a href="search_job.php" style="color: #000;">Search Jobs</a></li>
          <li class="breadcrumb-item active" aria-current="page">Job Details</li>
        </ol>
      </nav>
      <br><br>
    </div>
  </div>


  <div class="body">
    <div class="container inside-container">
      <?php

      $id = $_GET['jobid'];
      $query = "SELECT project.*, mrf.*, DATE_FORMAT(project.date_approved, '%M %d, %Y')as formatted_date
      FROM projects project, mrf mrf
      WHERE project.mrf_tracking = mrf.tracking
      AND project.id = '$id'";

      if ($result = mysqli_query($con, $query)) {

        $row = mysqli_fetch_assoc($result);
        $_SESSION['job_details'] = $row;
        $image = '../img/pcn.png';




        $query = "SELECT project.*, mrf.* 
        FROM projects project, mrf mrf
        WHERE project.mrf_tracking = mrf.tracking
        AND project.id = '$id'";

        $results = mysqli_query($con, $query);
        $rows = mysqli_fetch_assoc($results);
        $outlet = $rows['outlet'];

        $html = '';
        if (!empty($outlet)) {
            $data = json_decode($outlet, true);
            if (!empty($data['ops'])) {
                $html = '<ul>';
                foreach ($data['ops'] as $op) {
                    if (!empty($op['insert'])) {
                        $text = trim($op['insert']);
                        $attributes = isset($op['attributes']) ? $op['attributes'] : []; // Check if 'attributes' key exists
                        if (!empty($attributes) && isset($attributes['list']) && $attributes['list'] == 'bullet' && !empty($text)) {
                            $html .= '<li>' . $text . '</li>';
                        } elseif (!empty($text)) {
                            $html .= '<li>' . $text . '</li>';
                        }
                    }
                }
                $html .= '</ul>';
            }
        } 

      ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
          <input type="hidden" name="job_id" value="<?php echo $id; ?>">
          <button type="submit" name="apply" class="btn btn-primary" style="background: #279EFF; color: #fff; border: none; box-shadow: none; float: right;">Apply Now</button>
        </form>
        <img alt="" class="img-responsive" width="100vw" <?php echo '<img src="../imageStorage/' . $image . '" />'; ?> <br><br>
        <h2 style="text-transform: uppercase; color: #279EFF;"> <?php echo $row['project_title']; ?></h2>

        <p class="mb-3"><i class="bi bi-buildings"></i> <?php echo $row['client_company_id']; ?> </p>
        <p class="mb-3"><i class="bi bi-geo-alt"></i> <?php echo 'OUTLET/S:' . "&nbsp;&nbsp;&nbsp;" . $html; ?></p>
        <p class="mb-3"> DATE NEEDED:   <?php echo $row['formatted_date']; ?></p>
        <br>
        <hr>
        <h5 class="small mb-0" style="font-weight: bold;">JOB QUALIFICATIONS</h5>
        <ul>
          <li>
            <?php
            if ($rows['np_male'] !== '0' && $rows['np_female'] !== '0' && !empty($rows['np_male']) && !empty($rows['np_female'])) {
              echo 'Male/Female';
            } elseif (($rows['np_male'] !== '0' || !empty($rows['np_male'])) && ($rows['np_female'] === '0' || empty($rows['np_female']))) {
              echo 'Male';
            } elseif (($rows['np_female'] !== '0' || !empty($rows['np_female'])) && ($rows['np_male'] === '0' || empty($rows['np_male']))) {
              echo 'Female';
            } else {
              echo '';
            }
            ?>

          </li>
          <li><?php echo ucwords(strtolower($rows['work_experience'])); ?></li>
          <li><?php echo ucwords(strtolower($rows['edu'])) ?></li>
          <?php
          if (!empty($rows['pleasing_personality'])) { ?>
            <li><?php echo ucwords(strtolower($rows['pleasing_personality'])); ?></li>
          <?php } else {
            echo '';
          }
          ?>
          <?php
          if (!empty($rows['moral'])) { ?>
            <li><?php echo ucwords(strtolower($rows['moral'])); ?></li>
          <?php } else {
            echo '';
          }
          ?>
          <?php
          if (!empty($rows['comm_skills'])) { ?>
            <li><?php echo ucwords(strtolower($rows['comm_skills'])); ?></li>
          <?php } else {
            echo '';
          }
          ?>
          <?php
          if (!empty($rows['physically'])) { ?>
            <li><?php echo ucwords(strtolower($rows['physically'])); ?></li>
          <?php } else {
            echo '';
          }
          ?>
          <?php
          if (!empty($rows['articulate'])) { ?>
            <li><?php echo ucwords(strtolower($rows['articulate'])); ?></li>
          <?php } else {
            echo '';
          }
          ?>
          <?php
          if (!empty($rows['others'])) { ?>
            <li><?php echo ucwords(strtolower($rows['others'])); ?></li>
          <?php } else {
            echo '';
          }
          ?>
        </ul>
        <br>
          <h5 class="small mb-0" style="font-weight: bold;">REQUIREMENTS</h5>
          <ul>
            <li>SSS</li>
            <li>Philhealth</li>
            <li>Pagibig</li>
            <li>TIN</li>
            <li>NBI or Police Clearance</li>
            <li>Vaccination Card</li>
            <li>Medical/healthcard/mayors</li>
          </ul>
          <br>
        <br>
          <p style="color: #000;"> - There's a great surprise awaiting you. Hurry up!</p>
        <br>
        <br>
        <br>
        <hr>



      <?php }


      ?>
    </div>
  </div>
  </div>
  </main>



  <br><br><br><br><br><br><br><br>
  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.min.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../ssets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>


  <?php include '../body/footer.php'; ?>
</body>

</html>
