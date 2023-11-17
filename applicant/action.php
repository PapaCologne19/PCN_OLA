<?php
session_start();
include "../database/connection.php";
include "../body/function.php";

// Login User
if (isset($_POST['login'])) {

  $id = $_POST['job_id'];
  $username = mysqli_real_escape_string($con, $_POST['username']);
  $password = mysqli_real_escape_string($con, $_POST['password']);

  $query = "SELECT * FROM applicant WHERE username = '$username'";
  $result = mysqli_query($con, $query);

  if (mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_assoc($result);

    $_SESSION['username'] = $row['username'];
    $_SESSION['password'] = $row['password'];
    $_SESSION['firstname'] = $row['firstname'];
    $_SESSION['middlename'] = $row['middlename'];
    $_SESSION['lastname'] = $row['lastname'];
    $_SESSION['extension_name'] = $row['extension_name'];
    $hashedPassword = $row['password'];

    if (password_verify($password, $hashedPassword)) {
      header("location: job_details.php?jobid=$id");
      exit(0);
    } else {
      $_SESSION['errorMessage'] = "Username or Password is incorrect. Please try again.";
      header("Location: JobApply_Login.php?job_id=$id");
    }
  } else {
    $_SESSION['errorMessage'] = "Username or Password is incorrect. Please try again";
    header("Location: JobApply_Login.php?job_id=$id");
  }
}
?>



<!--DELETE USER-->
<?php

if (isset($_POST['delete_btn_set'])) {
  $id = $_POST['delete_id'];

  $query = "DELETE FROM job_tbl WHERE job_id = '$id'";
  $result = mysqli_query($con, $query);

  $_SESSION['success'] = "Job is succesfully deleted!";
  header("location: manage_jobs.php");
}
?>

<!--CLOSE JOBS -->
<?php

if (isset($_POST['close_btn_set'])) {
  $id = $_POST['close_id'];
  $status = "CLOSED";

  $query = "UPDATE job_tbl SET status = '$status' WHERE job_id = '$id'";
  $result = mysqli_query($con, $query);

  $_SESSION['success'] = "Job is succesfully closed!";
  header("location: manage_jobs.php");
}
?>

<!-- OPEN JOBS -->
<?php

if (isset($_POST['open_btn_set'])) {
  $id = $_POST['open_id'];
  $status = "ACTIVE";

  $query = "UPDATE job_tbl SET status = '$status' WHERE job_id = '$id'";
  $result = mysqli_query($con, $query);

  $_SESSION['success'] = "Job is succesfully open!";
  header("location: manage_jobs.php");
}


// Appying Jobs and submitting resume
if (isset($_POST['apply'])) {
  $job_id = $_POST['job_id'];
  $applicant_id = $_POST['applicant_id'];

  $file = $_FILES['file'];
  $filename = $_FILES["file"]["name"];
  $tempname = $_FILES["file"]["tmp_name"];
  $folder = "../employer/resumeStorage/" . $filename;

  $folderDestination = $folder;

  // Get the MIME type of the uploaded file
  $file_type = mime_content_type($tempname);

  // List of allowed MIME types
  $allowed_types = array('application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');



  // Check if the applicant has been rejected for the same job before
  $check_query = "SELECT project.*, applicant.*, rejected.* 

    FROM projects project, applicant applicant, rejected_applicants_history rejected 
    WHERE project.client_company_id = rejected.job_applied 
    AND applicant.id = rejected.applicant_id
    AND project.id = '$job_id' 
    AND rejected.username = '" . $_SESSION['username'] . "'";

  if (isset($_POST['apply'])) {
    $job_id = $_POST['job_id'];
    $applicant_id = $_POST['applicant_id'];

    $file = $_FILES['file'];
    $filename = $_FILES["file"]["name"];
    $tempname = $_FILES["file"]["tmp_name"];
    $folder = "../resumeStorage/" . $filename;

    $folderDestination = $folder;

    // Get the MIME type of the uploaded file
    $file_type = mime_content_type($tempname);

    // List of allowed MIME types
    $allowed_types = array('application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');


    // Check if the applicant has been rejected for the same job before
    $check_query = "SELECT project.*, applicant.*, resume.*, rejected.* 
        FROM projects project, applicant applicant, applicant_resume resume, rejected_applicants_history rejected 
        WHERE project.id = resume.project_id 
        AND applicant.id = resume.applicant_id
        AND resume.project_id = rejected.resume_attachment 
        AND project.id = '$job_id' 
        AND rejected.username = '" . $_SESSION['username'] . "'";

    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {

      // get the date when the applicant was rejected
      $row = mysqli_fetch_assoc($check_result);
      $rejected_date = $row['date_rejected'];
      $number_of_months = $row['number_of_months'];

      // calculate the time difference between the current date and the rejected date
      $time_diff = time() - strtotime($rejected_date);
      $months_diff = floor($time_diff / (30 * 24 * 60 * 60));

      // check if the applicant is eligible to apply again
      // Change if you want to customize the number of months of rejection using this code $number_of_months. But for now, one month muna
      if ($months_diff < 1) {
        $_SESSION['errorMessage'] = "You are rejected to this job. You can re-apply again after 3 months";
        header("location: job_details.php?jobid=$job_id");
        exit;
      }
    }

    // Check if the MIME type is in the list of allowed types
    else if (!in_array($file_type, $allowed_types)) {
      $_SESSION['errorMessage'] = "Please upload PDF and Docx file only.";
      header("location: job_details.php?jobid=$job_id");
      exit;
    }

    $today = date("Y-m-d");

    // Check if the applicant has already applied to 3 different jobs today
    $query = "SELECT COUNT(DISTINCT project_id) as job_count FROM applicant_resume
       WHERE applicant_id = $applicant_id AND DATE(date_applied) = '$today'";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      if ($row['job_count'] >= 100) {

        // Show the error message
        $_SESSION['errorMessage'] = "You already applied to 3 different jobs today.";
        header("location: job_details.php?jobid=$job_id");
        mysqli_close($con);
        exit;
      } else {


        // Resume checking
        $resume_check = "SELECT * FROM applicant_resume WHERE applicant_id = '$applicant_id' AND project_id = '$job_id'";
        $res = mysqli_query($con, $resume_check);
        if (mysqli_num_rows($res) > 0) {
          $_SESSION['errorMessage'] = "You are already applied to this Job!";
          header("location: job_details.php?jobid=$job_id");
        } else if (!empty($filename)) {

          $applicant_name = $_SESSION['firstname'] . " " . $_SESSION['middlename'] . " " . $_SESSION['lastname'] . " " . $_SESSION['extension_name'];
          $folder_name = $applicant_name;
          $destination = "../201 Files/" . $folder_name;

          if (file_exists($destination) && file_exists($filename)) {
            $_SESSION['errorMessage'] = "Error";
        } else {
            mkdir("{$destination}", 0777);
            move_uploaded_file($tempname, $destination . DIRECTORY_SEPARATOR . $filename);

            $sql = "INSERT INTO applicant_resume(applicant_id, project_id, resume_file) VALUES('$applicant_id', '$job_id', '$filename')";
            $result = mysqli_query($con, $sql);
            $_SESSION['successMessage'] = "File uploaded successfully";
        }
        
        } else {

          $_SESSION['errorMessage'] = "Failed to upload file";
        }
      }
    }
  }
  header("location: searchjob.php");
  exit(0);
}
?>