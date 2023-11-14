<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="../css/bootstrap.css">
</head>

<body>
  <nav class="top-nav">
    <div class="nav">
      <div class="d-flex justify-content-start" id="logo" style="width: 100%;">
        <a href="../index.php" class="logo d-flex align-items-center w-auto" style="box-shadow: none;">
          <img src="../assets/img/alegario_logo.png" alt="HR Logo" width="20%">
          <span class="d-lg-block small mb-0" style="font-family: 'Poiret One', cursive !important; color: #000000; font-weight: 600;">ALEGARIO<b id="animated-text" style="font-family: 'Poiret One', cursive !important;"> CURE</b></span>
        </a>
      </div>

      <div class=".menu-page d-flex justify-content-center py-4" style="width: 100%;">
        <input id="menu-toggle" type="checkbox" />
        <label class='menu-button-container' for="menu-toggle">
          <div class='menu-button'></div>
        </label>


        <ul class="menu">
          <li><button class="btn"><a class="active" href="../index.php">HOME</a></button></li>
          <li><button class="btn"><a href="../body/about.php">ABOUT</a></button></li>
          <li><button class="btn"><a href="../body/contact.php">CONTACT</a></button></li>
          <li><button class="btn"><a href="../applicant/register_applicant.php" >SIGNUP</a></button></li>
          <li><button class="btn" id="login"><a href="../applicant/login_applicant.php">LOGIN</a></button></li>
        </ul>
      </div>

      <div class="d-flex justify-content-end py-4" id="icons" style="width: 100%;">
        <div class="icons">
          <a href="#" target="_blank" class="fa fa-facebook-square"></a>
          <a href="#" target="_blank" class="fa fa-instagram"></a>
          <a href="#" target="_blank" class="fa fa-twitter"></a>
          <a href="#" target="_blank" class="fa fa-google"></a>
        </div>
      </div>
    </div>
  </nav>

  <script src="../js/bootstrap.js"></script>
</body>

</html>