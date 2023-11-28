<?php
session_start();
include "../database/connection.php";
include "../body/function.php";

// Login User
if (isset($_POST['login'])) {

  $id = $_POST['job_id'];
  $username = mysqli_real_escape_string($con, $_POST['username']);
  $password = mysqli_real_escape_string($con, $_POST['password']);

  $query = "SELECT * FROM applicant WHERE username = '$username' AND is_deleted = '0'";
  $result = mysqli_query($con, $query);

  if (mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_assoc($result);

    $_SESSION['id'] = $row['id'];
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

          $applicant_name = chop($_SESSION['firstname'] . " " . $_SESSION['middlename'] . " " . $_SESSION['lastname'] . " " . $_SESSION['extension_name']);
          $folder_name = $applicant_name;
          $destination = "../201 Files/" . $folder_name;

          if (file_exists($destination) && file_exists($filename)) {
            $_SESSION['errorMessage'] = "Error";
          } else {
            mkdir("{$destination}", 0777);

            $applicant_name_subfolder = "Requirements";
            $folder_name_subfolder = $applicant_name_subfolder;
            $destination_subfolder = $destination . "/" . $folder_name_subfolder;
            $folder_path =  "201 Files/" . $applicant_name . $folder_name_subfolder;

            mkdir("{$destination_subfolder}", 0777);

            $check_folder_query = "SELECT id FROM folder WHERE applicant_id = '$applicant_id' AND folder_name = '$applicant_name_subfolder' AND folder_path = '$folder_path'";
            $check_folder_query_result = $con->query($check_folder_query);
            $check_folder_query_row = $check_folder_query_result->fetch_assoc();

            if ($check_folder_query_result->num_rows === 0) {
              $insert_folder = "INSERT INTO folder (applicant_id, folder_name, folder_path) VALUES(?, ?, ?)";
              $insert_folder_result = $con->prepare($insert_folder);
              $insert_folder_result->bind_param("iss", $applicant_id, $applicant_name_subfolder, $folder_path);

              if ($insert_folder_result->execute()) {
                $folder_id = $insert_folder_result->insert_id;
                $sql = "INSERT INTO applicant_resume(applicant_id, project_id, folder_id, resume_file, resume_path) 
                VALUES('$applicant_id', '$job_id', '$folder_id', '$filename', '$destination_subfolder')";
                $result = mysqli_query($con, $sql);

                if ($result) {
                  move_uploaded_file($tempname, $destination_subfolder . DIRECTORY_SEPARATOR . $filename);
                  $_SESSION['successMessage'] = "File uploaded successfully";
                } else {
                  $_SESSION['errorMessage'] = "Error" . mysqli_error($con);
                }
              } else {
                $_SESSION['errorMessage'] = "Error";
              }
            } else {
              $folder_id = $check_folder_query_row['id'];
              $sql = "INSERT INTO applicant_resume(applicant_id, project_id, folder_id, resume_file, resume_path) 
                VALUES('$applicant_id', '$job_id', '$folder_id', '$filename', '$destination_subfolder')";
              $result = mysqli_query($con, $sql);

              if ($result) {
                move_uploaded_file($tempname, $destination_subfolder . DIRECTORY_SEPARATOR . $filename);
                $_SESSION['successMessage'] = "File uploaded successfully";
              } else {
                $_SESSION['errorMessage'] = "Error" . mysqli_error($con);
              }
            }
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

// Submission of Resume File
if (isset($_POST['submit_files'])) {
  $files = $_FILES['files'];
  $project_id = $_POST['project_id'];
  $applicant_id = $_SESSION['id'];
  $sss = $con->real_escape_string($_POST['sss']);
  $philhealth = $con->real_escape_string($_POST['philhealth']);
  $pagibig = $con->real_escape_string($_POST['pagibig']);
  $tin = $con->real_escape_string($_POST['tin']);
  $emergency_contact_person = $con->real_escape_string($_POST['emergency_contact_person']);
  $emergency_contact_number = $con->real_escape_string($_POST['emergency_contact_number']);
  $emergency_contact_address = $con->real_escape_string($_POST['emergency_contact_address']);
  $date_now = date('Y-m-d');

  $select_path = "SELECT resume_path FROM applicant_resume WHERE project_id = '$project_id' AND applicant_id = '$applicant_id'";
  $select_path_result = $con->query($select_path);


  $fetch = "SELECT id FROM employees WHERE app_id = '$applicant_id'";
  $fetched = $con->query($fetch);
  $fetched_row = $fetched->fetch_assoc();
  $employee_id = $fetched_row['id'];

  $insert_emp = "UPDATE employees SET sssnum = '$sss', phnum = '$philhealth', pagibignum = '$pagibig', tinnum = '$tin',
    e_person = '$emergency_contact_person', e_address = '$emergency_contact_address', e_number = '$emergency_contact_address' 
    WHERE id = '$employee_id' AND app_id = '$applicant_id'";
  $insert_emp_result = $con->query($insert_emp);
  while ($select_path_row = $select_path_result->fetch_assoc()) {
    $path = $select_path_row['resume_path'] . "/";

    foreach ($files['tmp_name'] as $key => $tmp_name) {
      $targetFile = $path . basename($files['name'][$key]);
      $filename = basename($files['name'][$key]);

      $select_folder = "SELECT * FROM folder WHERE applicant_id = '$applicant_id' AND folder_name = 'Requirements'";
      $select_folder_result = $con->query($select_folder);
      $select_folder_row = $select_folder_result->fetch_assoc();
      $folder_id = $select_folder_row['id'];

      $insert_file = "INSERT INTO `201files` (`applicant_id`, `project_id`, `folder_id`, `requirements_files`, `requirements_files_uploaded`) 
                            VALUES (?, ?, ?, ?, ?)";
      $stmt = $con->prepare($insert_file);
      $stmt->bind_param("sssss", $applicant_id, $project_id, $folder_id, $filename, $date_now);
      $insert_file_result = $stmt->execute();

      if ($insert_file_result) {
        move_uploaded_file($tmp_name, $targetFile);
        $_SESSION['successMessage'] =  "Files uploaded successfully.";
      } else {
        $_SESSION['errorMessage'] =  "Files uploaded error.";
      }
    }
  }


  header("Location: profile.php");
  exit(0);
}

// For Returning Signed LOA File
if (isset($_POST['signed_loa_button'])) {
  $deployment_id = $con->real_escape_string($_POST['deployment_id']);
  $file = $_FILES['signed_loa'];
  $filename = $_FILES["signed_loa"]["name"];
  $tempname = $_FILES["signed_loa"]["tmp_name"];
  $signed_loa_status = "SUBMITTED";

  $select = "SELECT deployment.*, employee.*, resumes.*
  FROM deployment deployment, employees employee, applicant_resume resumes
  WHERE deployment.employee_id = employee.id
  AND deployment.app_id = resumes.applicant_id
  AND deployment.id = '$deployment_id'";
  $select_result = $con->query($select);
  $select_row = $select_result->fetch_assoc();


  $start_loa = $select_row['loa_start_date'];
  $end_loa = $select_row['loa_end_date'];
  $start_loa_date = new DateTime($start_loa);
  $start_loa_formatted = $start_loa_date->format('F j, Y');
  $end_loa_date = new DateTime($end_loa);
  $end_loa_formatted = $end_loa_date->format('F j, Y');

  $applicant_name = chop($select_row['firstnameko'] . " " . $select_row['mnko'] . " " . $select_row['lastnameko'] . " " . $select_row['extnname']);
  $folder_path = $select_row['resume_path'];
  $folder_name = $applicant_name;
  $applicant_name_subfolder = $applicant_name . "- From " . $start_loa_formatted . " To " . $end_loa_formatted;
  $folder_name_subfolder = $applicant_name_subfolder;
  $destination_subfolder = "../" . $folder_path . "/" . $folder_name_subfolder . "/";


  if (!empty($filename)) {
    $insert_loa = "UPDATE deployment SET signed_loa_file = ?, signed_loa_status = ? WHERE id = ?";
    $stmt = $con->prepare($insert_loa);
    $stmt->bind_param("sss", $filename, $signed_loa_status, $deployment_id);
    if ($stmt->execute()) {
      move_uploaded_file($tempname, $destination_subfolder . $filename);
      $_SESSION['successMessage'] = "Success";
    } else {
      $_SESSION['errorMessage'] = "Error";
    }
  }
  header("Location: profile.php");
  exit(0);
}
