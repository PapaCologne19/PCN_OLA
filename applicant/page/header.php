<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="../css/bootstrap.css">
  <style>
    .choices {
      min-width: 20rem !important;
    }
    .choices li .dropdown-item{
      font-family: 'Inter', sans-serif !important;
    }
  </style>
</head>

<body>
  <nav class="top-nav">
    <div class="nav">
      <div class="d-flex justify-content-start" id="logo" style="width: 100%;">
        <a href="../index.php" class="logo d-flex align-items-center w-auto mx-3" style="box-shadow: none;">
          <img src="../img/pcn.png" alt="HR Logo" width="20%">
        </a>
      </div>

      <div class=".menu-page d-flex justify-content-center py-4" style="width: 100%;">
        <input id="menu-toggle" type="checkbox" />
        <label class='menu-button-container' for="menu-toggle">
          <div class='menu-button'></div>
        </label>

        <ul class="menu">
          <li><button class="btn" onclick="location.href = 'searchjob.php'">HOME</button></li>
          <li><button class="btn" onclick="location.href = 'about.php'">ABOUT</button></li>
          <li><button class="btn" onclick="location.href = 'contact.php'">CONTACT</button></li>
          <li><button class="btn" id="profile" onclick="location.href='profile.php'">PROFILE</button></li>
          <li><button class="btn" id="logout" onclick="location.href = '../body/logout.php'">LOGOUT</button></li>
        </ul>
      </div>

      <div class=" d-flex justify-content-end py-4" id="icons" style="width: 100%;">

        <div class="dropdown col-md-4">
          <i class="bi bi-bell" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 22px; position: relative; left: 150px; color: #000000 important;" title="Notifications" onclick="clearNotifications()">
            <span class="badge rounded-pill bg-danger" id="number_of_notification" style="position: absolute; margin-left: -.8rem; font-style: normal !important; font-size: 11px !important;"></span>
          </i>

          <ul class="dropdown-menu choices">
            <?php
            $select_notif = "SELECT * FROM applicant_notifications WHERE applicant_id = '" . $_SESSION['id'] . "' AND view = '0'";
            $select_notif_result = $con->query($select_notif);
            $total = $select_notif_result->num_rows;
            while ($select_notif_row = $select_notif_result->fetch_assoc()) {
              $notification = $select_notif_row['notification'];
            ?>
              <li><a class="dropdown-item" href="#"><?php echo $notification ?></a></li>
            <?php } ?>
          </ul>
        </div>
        <div class="icons">
          <button class="btn" onclick="location.href='profile.php'" id="profile2">PROFILE</button>
          <button class="btn btn-default" id="logout2" onclick="location.href = '../body/logout.php'">LOGOUT</button>
        </div>
      </div>
    </div>
  </nav>


  <script src="../js/bootstrap.js"></script>
  <script>
    function loadDoc() {
      setInterval(function() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("number_of_notification").innerHTML = this.responseText;
          }
        };
        xhttp.open("GET", "get_notification.php", true);
        xhttp.send();
      }, 100)
    }

    function clearNotifications() {
      // Clear the displayed notifications
      document.getElementById("number_of_notification").innerHTML = "";

      // Send a request to reset the notifications
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // Update the notification count after resetting
          document.getElementById("number_of_notification").innerHTML = this.responseText;
        }
      };
      xhttp.open("GET", "reset_notification.php", true);
      xhttp.send();
    }


    loadDoc();
  </script>
</body>

</html>