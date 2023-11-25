 <?php
  include "../database/connection.php";
  include "../body/function.php";

  session_start();
  $errors = array();

  if (isset($_SESSION['username'], $_SESSION['password'])) {
  ?>

   <!DOCTYPE html>
   <html lang="en">

   <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="shortcut icon" href="../assets/img/alegario_logo.png" type="image/x-icon">

     <!-- Icons -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
     <link rel="stylesheet" href="assets/fontawesome/css/fontawesome.css">
     <link rel="stylesheet" href="assets/fontawesome/css/brands.css">
     <link rel="stylesheet" href="assets/fontawesome/css/solid.css">
     <script src="https://kit.fontawesome.com/f63d53b14e.js" crossorigin="anonymous"></script>

     <!-- Fonts -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@500&family=Inter:wght@300;400;600;800&family=Poiret+One&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:wght@500;600&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,400;1,500;1,700;1,900&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
     <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>


     <link rel="stylesheet" href="../css/style/search_results.css">
     <link rel="stylesheet" href="../css/style/header.css">
     <link rel="stylesheet" href="../css/bootstrap.css">



     <title>Job Details</title>

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
   </head>

   <body>
     <?php
      include 'page/header.php';
      ?>
<?php
      if (isset($_SESSION['successMessage'])) { ?>
        <script>
          Swal.fire({
            icon: 'success',
            title: "<?php echo $_SESSION['successMessage']; ?>",
          })
        </script>
      <?php unset($_SESSION['successMessage']);
      } ?>

      <?php
      if (isset($_SESSION['errorMessage'])) { ?>
        <script>
          Swal.fire({
            icon: 'error',
            title: "<?php echo $_SESSION['errorMessage']; ?>",
          })
        </script>
      <?php unset($_SESSION['errorMessage']);
      }
      ?>
     <br><br><br><br><br><br><br><br>

     <div class="head">
       <div class="container">
         <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
           <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="../applicant/searchjob.php" style="color: #000;">Search Jobs</a></li>
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
           <button type="button" name="apply" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="background: #279EFF; color: #fff; border: none; box-shadow: none; float: right;">Apply Now</button>

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
              <?php if(!empty($rows['work_experience'])) {?>
                <li><?php echo ucwords(strtolower($rows['work_experience'])) ?></li>
              <?php } else {?>
              <?php }?>
              <?php if(!empty($rows['edu'])) {?>
                <li><?php echo ucwords(strtolower($rows['edu'])) ?></li>
              <?php } else {?>
              <?php }?>
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


           <!--Modal-->
           <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
                 <div class="modal-header">
                   <br>
                   <h5 style="color: #000; font-family: 'Roboto', sans-serif; font-weight: 800; text-align: center;">UPLOAD YOUR RESUME PICTURE</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: none !important;"></button>
                 </div>
                 <div class="modal-body">
                   <!-- Form -->
                   <?php
                    $query = "SELECT * FROM applicant WHERE username = '" . $_SESSION['username'] . "'";
                    $result = mysqli_query($con, $query);
                    $fetch = mysqli_fetch_assoc($result);
                    ?>

                   <form action="action.php" method="post" class="form-group needs-validation" novalidate enctype="multipart/form-data">
                     <input type="hidden" class="form-control" name="job_id" id="job_id" value="<?php echo $id ?>">
                     <input type="hidden" class="form-control" name="applicant_id" id="applicant_id" value="<?php echo $fetch['id']; ?>">
                     <div class="col-auto">
                       <label for="email" class="form-label" style="color: #000;">Please upload your resume (Docx and PDF only)</label>
                       <input type="file" class="form-control" name="file" id="file" style="color: #000; box-shadow: none; border-color: #279EFF;" required>
                       <div class="invalid-feedback">
                         Please upload your Resume/CV.
                       </div>
                     </div>
                     <br>
                     <button type="submit" name="apply" class="btn" style="background: #279EFF; color: #fff; border: none; box-shadow: none;">Upload</button>
                     <?php
                      ?>
                   </form>

                 </div>
                 <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Close</button>
                 </div>
               </div>
             </div>
           </div>


         <?php }


          ?>
       </div>
     </div>





     </div>


     </main>
     <br><br><br><br><br><br><br><br>
     <?php include '../body/footer.php'; ?>







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



   </body>

   </html>
 <?php
  } else {
    header("location:../applicant/login_applicant.php");
    session_destroy();
  }
  unset($_SESSION['prompt']);
  mysqli_close($con);
  ?>