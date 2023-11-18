<?php
include '../database/connection.php';
include '../body/function.php';

session_start();
$errors = array();


if (isset($_POST['register'])) {
  $source = mysqli_real_escape_string($con, $_POST["source"]);
  $username = mysqli_real_escape_string($con, $_POST["username"]);
  $password = mysqli_real_escape_string($con, $_POST["password"]);
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  $firstname = mysqli_real_escape_string($con, $_POST["firstname"]);
  $middlename = mysqli_real_escape_string($con, $_POST["middlename"]);
  $lastname = mysqli_real_escape_string($con, $_POST["lastname"]);
  $extension_name = mysqli_real_escape_string($con, $_POST["extension_name"]);
  $gender = mysqli_real_escape_string($con, $_POST["gender"]);
  $civil_status = mysqli_real_escape_string($con, $_POST["civil_status"]);
  $age = mysqli_real_escape_string($con, $_POST["age"]);
  $mobile_number = mysqli_real_escape_string($con, $_POST["mobile_number"]);
  $email = mysqli_real_escape_string($con, $_POST["email"]);
  $dob = mysqli_real_escape_string($con, $_POST["dob"]);
  $address = mysqli_real_escape_string($con, $_POST["address"]);
  $region = mysqli_real_escape_string($con, $_POST["region"]);
  $city = mysqli_real_escape_string($con, $_POST["city"]);

  // Check if the username is already exist
  $check = "SELECT username FROM applicant WHERE username = '$username'";
  $check_result = $con->query($check);
  if ($check_result->num_rows === 0) {
    // Insert the record into the MySQL table
    $query = "INSERT INTO applicant(source, username, password, firstname, middlename, lastname, extension_name, gender, civil_status, age, mobile_number, email_address, birthday, present_address, city, region)
         VALUES ('$source', '$username', '$hashedPassword', '$firstname', '$middlename', '$lastname', '$extension_name', '$gender', '$civil_status', '$age', '$mobile_number', '$email', '$dob', '$address', '$city', '$region')";

    $results = mysqli_query($con, $query);

    if ($results) {
      $_SESSION['message'] = "Successful";
      header("location: login_applicant.php");
    } else {
      $_SESSION['errorMessage'] = "Registration Unsuccessful" . mysqli_error($con);;
    }
  } else {
    $_SESSION['errorMessage'] = "Username is already exist";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/img/pcn.png" type="image/x-icon">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://kit.fontawesome.com/f63d53b14e.js" crossorigin="anonymous"></script>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@500&family=Inter:wght@300;400;600;800&family=Poiret+One&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:wght@500;600&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,400;1,500;1,700;1,900&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

  <!-- Flatpickr -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <!-- Modified CSS-->
  <link rel="stylesheet" href="../css/style/register.css">
  <link rel="stylesheet" href="../css/bootstrap.css">




  <title>Register - Job Seeker</title>

  <style>
    .alert {
      display: none;
    }

    .requirements {
      list-style-type: none;
    }

    .wrong .fa-check {
      display: none;
    }

    .good .fa-times {
      display: none;
    }
  </style>
</head>

<body>

  <main>
    <div class="row justify-content-right" style="width: 50vh;"></div>
    <img src="../img/Enter OTP-cuate.svg" width="40%" class="rounded" alt="..." id="bg">
    </div>
    <div class="container">
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
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-left">
            <div class="col-lg-7 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4" id="logo">
                <a href="../index.php" class="logo d-flex align-items-center w-auto">
                  <img src="../assets/img/pcn.png" alt="HR Logo" width="30%">
                </a>
              </div><!-- End Logo -->


              <div class="card mb-3" style="border: none;">

                <div class="card-body" id="card-body" style="background: #fff; color: #000; width: 80% !important;">
                  <div class="pt-4 pb-2">
                    <h2 class="card-title text-center " style="color: #1d8a81; font-family: 'Inter', sans-serif; font-weight: 800;">REGISTER AN ACCOUNT</h2>
                    <br>
                  </div>

                  <form class="row g-3 needs-validation" novalidate method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-floating col-sm-6 col-md-6 col-lg-6">
                      <select name="source" id="source" class="form-select" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;" required>
                        <option value=""></option>
                        <option value="REFERRAL">REFERRAL</option>
                        <option value="NON REFERRAL">NON REFERRAL</option>
                      </select>
                      <label for="source" style="color: #000">Source</label>
                      <div class="invalid-feedback">
                        Please input Source.
                      </div>
                    </div>

                    <div class="form-floating col-sm-12 col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="username" name="username" placeholder="Username" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;" required>
                      <label for="email" style="color: #000">Username</label>
                      <div class="invalid-feedback">
                        Please input Username.
                      </div>
                    </div>

                    <div class="form-floating col-md-12 col-lg-12">
                      <input type="password" class="form-control validate" name="password" id="password" placeholder="Password" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                      <label for="password" style="color: #000">Password</label>
                      <div class="invalid-feedback">Please input Password.</div>
                    </div>

                    <div class="alert alert-warning password-alert" role="alert">
                      <ul>
                        <li class="requirements leng"><i class="fas fa-check green-text"></i></i></i><i class="fas fa-times red-text"></i> Your password must have at least 8 characters.</li>
                        <li class="requirements big-letter"><i class="fas fa-check green-text"></i><i class="fas fa-times red-text"></i> Your password must have at least 1 big letter.</li>
                        <li class="requirements num"><i class="fas fa-check green-text"></i><i class="fas fa-times red-text"></i> Your password must have at least 1 number.</li>
                      </ul>
                    </div>


                    <div class="form-floating  col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;" required>
                      <label for="firstname" style="color: #000">First Name</label>
                      <div class="invalid-feedback">
                        Please input First Name.
                      </div>
                    </div>
                    <div class="form-floating  col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;">
                      <label for="middlename" style="color: #000">Middle Name</label>
                      <div class="invalid-feedback">
                        Please input Middle Name.
                      </div>
                    </div>
                    <div class="form-floating  col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;" required>
                      <label for="lastname" style="color: #000">Last Name</label>
                      <div class="invalid-feedback">
                        Please input Last Name.
                      </div>
                    </div>
                    <div class="form-floating  col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="extension_name" name="extension_name" placeholder="Extension Name" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;">
                      <label for="extension_name" style="color: #000">Extension Name</label>
                    </div>

                    <fieldset>
                      <legend class="col-form-label col-lg-12 col-md-12 col-sm-2 pt-0">Gender</legend>
                      <div class="col-sm-6">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="gender" id="gender" value="Male" required checked>
                          <label class="form-check-label" for="gender">Male</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="gender" id="gender" value="Female" required>
                          <label class="form-check-label" for="gender">Female</label>
                        </div>
                      </div>
                    </fieldset>


                    <div class="form-floating  col-md-6 col-lg-6">
                      <select name="civil_status" id="civil_status" class="form-select" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;" required>
                        <option value=""></option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Separated">Separated</option>
                      </select>
                      <label for="civil_status" class="form-label">Civil Status</label>
                      <div class="invalid-feedback">
                        Please input Civil Status.
                      </div>
                    </div>

                    <div class="form-floating  col-md-6 col-lg-6">
                      <input type="text" class="form-control" name="mobile_number" id="mobile_number" maxlength="11" placeholder="Mobile Number" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;" required>
                      <label for="mobile_number" class="form-label">Mobile Number</label>
                      <div class="invalid-feedback">
                        Please input Mobile Number.
                      </div>
                    </div>
                    <div class="form-floating col-sm-12 col-md-12 col-lg-5">
                      <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;" required>
                      <label for="email" style="color: #000">Email Address</label>
                      <div class="invalid-feedback">
                        Please input Email Address.
                      </div>
                    </div>
                    <div class="form-floating col-md-12 col-lg-7">
                      <input type="date" class="form-control" name="dob" id="dob" onchange="calculateAge()" placeholder="Date of Birth" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;">
                      <label for="dob" class="form-label">Date of Birth</label>
                    </div>
                    <div class="form-floating  col-md-2 col-lg-2">
                      <input type="hidden" class="form-control" name="age" id="age" placeholder="Age" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;" readonly>
                    </div>
                    <div class="form-floating  col-md-12 col-lg-12">
                      <input type="text" class="form-control" name="address" id="address" placeholder="Address" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;" required>
                      <label for="Address" class="form-label">Present Address</label>
                      <div class="invalid-feedback">
                        Please input Present Address.
                      </div>
                    </div>
                    <div class="form-floating  col-md-6 col-lg-6">
                      <select name="region" id="region" class="form-select" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;" required>
                        <option value=""></option>
                        <?php
                        $select_region = "SELECT * FROM region";
                        $select_region_result = $con->query($select_region);
                        while ($select_region_row = mysqli_fetch_assoc($select_region_result)) {
                        ?>
                          <option value="<?php echo $select_region_row['regCode'] ?>"><?php echo $select_region_row['regDesc'] ?></option>
                        <?php } ?>
                      </select>
                      <label for="Region" class="form-label">Region</label>
                      <div class="invalid-feedback">
                        Please input Present Region.
                      </div>
                    </div>
                    <div class="form-floating  col-md-6 col-lg-6">
                      <select name="city" id="city" class="form-select" style="background-color: inherit; border-top: none; border-left: none; border-right: none; box-shadow: none !important; border-color: #000 !important;" required>
                        <option value=""></option>
                        <option value="">ads</option>
                      </select>
                      <label for="city" class="form-label">City</label>
                      <div class="invalid-feedback">
                        Please input Present City.
                      </div>
                    </div>


                    <div class="col-12">
                      <br><br>
                      <p class="small mb-0" style="color: #000;">By continuing, you acknowledge that you accept Job Portal's <a href="register_applicant.html" style="color: #57d8cd; ">Privacy Policies</a> and <a href="" style="color: #57d8cd;">Terms & Conditions</a></p>
                    </div>

                    <div class="col-12">
                      <button class="btn w-100" type="submit" id="register" name="register" style="background: #57d8cd; color: white; box-shadow: none;">Register</button>
                      <br><br>
                      <a href="../applicant/login_applicant.php" class="small mb-0" style="color: #000; ">Cancel Registration</a>
                    </div>
                    <br><br>
                  </form>
                </div>
              </div>

            </div>
          </div>
        </div>


      </section>
    </div>
  </main>

  <script>
    $(function() {
      var $password = $(".form-control[type='password']");
      var $passwordAlert = $(".password-alert");
      var $requirements = $(".requirements");
      var leng, bigLetter, num, specialChar;
      var $leng = $(".leng");
      var $bigLetter = $(".big-letter");
      var $num = $(".num");
      var numbers = "0123456789";

      $requirements.addClass("wrong");
      $password.on("focus", function() {
        $passwordAlert.show();
      });

      $password.on("input blur", function(e) {
        var el = $(this);
        var val = el.val();
        $passwordAlert.show();

        if (val.length < 8) {
          leng = false;
        } else if (val.length > 7) {
          leng = true;
        }


        if (val.toLowerCase() == val) {
          bigLetter = false;
        } else {
          bigLetter = true;
        }

        num = false;
        for (var i = 0; i < val.length; i++) {
          for (var j = 0; j < numbers.length; j++) {
            if (val[i] == numbers[j]) {
              num = true;
            }
          }
        }

        console.log(leng, bigLetter, num, specialChar);

        if (leng == true && bigLetter == true && num == true) {
          $(this).addClass("valid").removeClass("invalid");
          $requirements.removeClass("wrong").addClass("good");
          $passwordAlert.removeClass("alert-warning").addClass("alert-success");
        } else {
          $(this).addClass("invalid").removeClass("valid");
          $passwordAlert.removeClass("alert-success").addClass("alert-warning");

          if (leng == false) {
            $leng.addClass("wrong").removeClass("good");
          } else {
            $leng.addClass("good").removeClass("wrong");
          }

          if (bigLetter == false) {
            $bigLetter.addClass("wrong").removeClass("good");
          } else {
            $bigLetter.addClass("good").removeClass("wrong");
          }

          if (num == false) {
            $num.addClass("wrong").removeClass("good");
          } else {
            $num.addClass("good").removeClass("wrong");
          }
        }


        if (e.type == "blur") {
          $passwordAlert.hide();
        }
      });
    });


    // For City and Region
    $("#region").on("change", function() {
      var x_values = $("#region").find(":selected").val();

      $.ajax({
        url: 'region.php',
        type: 'POST',
        data: {
          city_code: x_values
        },
        success: function(result) {
          result = JSON.parse(result);

          // Empty the options of the specific dropdown with ID 'cityn'
          $("#city").empty();

          // Append new options
          result.forEach(function(item, index) {
            var option = $("<option>").text(item['city_name']).val(item['city_name']);
            $("#city").append(option);
          });
        },
        error: function(result) {
          console.log(result)
        }
      });
    });

    // For birthday and age 
    function calculateAge() {
      // Get the date of birth from the input field
      const birthdate = document.getElementById("dob").value;

      // Calculate the age
      const birthTimestamp = new Date(birthdate).getTime();
      const currentTimestamp = new Date().getTime();
      const age = new Date(currentTimestamp - birthTimestamp).getUTCFullYear() - 1970;

      // Update the age input field with the calculated age
      document.getElementById("age").value = age;
    }

    // Date Format
    flatpickr("#dob", {
      dateFormat: "m/d/Y", // Set the desired date format (MM-DD-YYYY)
      altInput: false, // Enable the alternate input field
      altFormat: "F j, Y", // Set the format for the alternate input field (placeholder)
      placeholder: "Select a date", // Set the text for the placeholder
    });
  </script>


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