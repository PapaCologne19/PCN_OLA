<?php
include '../database/connection.php';
include '../body/function.php';
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
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600&family=Bebas+Neue&family=Comfortaa:wght@500&family=Heebo:wght@100;200;300;400;500;600;700;800;900&family=Hind&family=Inter:wght@300;400;600;800&family=Poiret+One&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:wght@500;600&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,400;1,500;1,700;1,900&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.css">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

  <!-- JS -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

  <link rel="stylesheet" href="../css/style/search_job.css">
  <link rel="stylesheet" href="../css/style/header.css">
  <link rel="stylesheet" href="../css/style/footer.css">
  <link rel="stylesheet" href="../css/bootstrap.css">


  <title>Search Available Jobs</title>

</head>

<body>
  <?php
  include '../body/header.php';
  ?>
  <!-- Main Page -->
  <main>
    <div class="homepageSearch">
      <div class="home-search">

        <div class="search-title">
          <h1>SEARCH JOB VACANCIES</h1>
        </div>

        <div class="search-form">
          <form action="searchresults.php" method="get" class="row" id="search-form">
            <div class="col-xl-8 col-lg-8 col-md-8">
              <i class="bi bi-search search_icon" style="position: absolute; left: 2rem; padding-top: 1rem;"></i>
              <input type="search" name="search" id="search" class="form-control" placeholder="Search" style="box-shadow: none; font-family: 'Roboto', sans-serif" required>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4">
              <button type="submit" class="btn" name="searchbtn" id="searchbtn"><i class="bi bi-search"></i></button>
            </div>
          </form>
        </div>
      </div>


    </div>
  </main>

  <!-- AVAILABLE JOBS -->
  <div class="job other-elements" id="other-elements">

    <div class="row" style="margin: 3rem;">
      <h4>JOB OPENINGS</h4>
      <?php
      $query = "SELECT * FROM projects";
      $result = mysqli_query($con, $query);
      $row = mysqli_num_rows($result);

      

      ?>
      <p>1-12 out of <?php echo $row; ?> JOBS</p>

      <hr>
      <?php
      $query = "SELECT *, TIMESTAMPDIFF(SECOND, date_approved, NOW()) as diff FROM projects";
      $result = mysqli_query($con, $query);
      if (mysqli_num_rows($result)) {
        while ($row = mysqli_fetch_assoc($result)) {
          $tracking = $row['mrf_tracking'];
          $select = "SELECT * FROM mrf WHERE tracking = '$tracking'";
          $select_result = $con->query($select);
          while ($select_row = $select_result->fetch_assoc()) {
            $image = '../img/pcn.png';
            $diff = $row['diff'];
            if ($diff < 60) {
              $time_ago = $diff . " seconds ago";
            } else if ($diff < 3600) {
              $time_ago = floor($diff / 60) . " minute" . ((floor($diff / 60) > 1) ? "s" : "") . " ago";
            } else if ($diff < 86400) {
              $time_ago = floor($diff / 3600) . " hour" . ((floor($diff / 3600) > 1) ? "s" : "") . " ago";
            } else if ($diff < 604800) {
              $time_ago = floor($diff / 86400) . " day" . ((floor($diff / 86400) > 1) ? "s" : "") . " ago";
            } else if ($diff < 2592000) {
              $time_ago = floor($diff / 604800) . " week" . ((floor($diff / 604800) > 1) ? "s" : "") . " ago";
            } else if ($diff < 31536000) {
              $time_ago = floor($diff / 2592000) . " month" . ((floor($diff / 2592000) > 1) ? "s" : "") . " ago";
            } else {
              $time_ago = floor($diff / 31536000) . " year" . ((floor($diff / 31536000) > 1) ? "s" : "") . " ago";
            }
          

      ?>
            <div class="col-lg-3 col-md-6 col-sm-12">
              <?php if ($row['status'] === "1") { ?>
                <div class="card-content" style="display: none;">
                  <div class="card" style="width: 100%; height: 350px;">
                    <a href="job_details.php?jobid=<?php echo $row['id']; ?>" style="text-decoration: none;">
                      <div class="card-body">
                        <img width="30%" alt="Company Logo" style="box-sizing: border-box;" <?php echo '<img src="../imageStorage/' . $image . '" />'; ?> <br><br>
                        <p class="card-title" style="text-align: left !important; color: #279EFF !important;"><?php echo $row['project_title']; ?></p>
                        <p style="font-family: 'Roboto', sans-serif;"><?php echo $row['client_company_id']; ?></p>
                        <p style="color: #6e6e6e; z-index: -1; background: transparent;">Posted on <?php echo $time_ago; ?></p>
                      </div>
                    </a>
                  </div>
                </div>
              <?php
              }
              ?>

            </div>
      <?php
          }
        }
      }
      ?>


      <nav aria-label="Page navigation for Available Jobs">
        <br><br>
        <ul class="pagination justify-content-center">
          <li class="page-item previous-page disabled">
            <a class="page-link" href="#" tabindex="-1">Previous</a>
          </li>
          <li class="page-item current-page"><a class="page-link " href="#">1</a></li>
          <li class="page-item current-page"><a class="page-link " href="#">2</a></li>
          <li class="page-item current-page"><a class="page-link " href="#">3</a></li>
          <li class="page-item dots"><a class="page-link " href="#">...</a></li>
          <li class="page-item next-page">
            <a class="page-link" href="#">Next</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
  <!-- END OF AVAILABLE JOBS -->



  <script>
    function getPageList(totalPages, page, maxLength) {
      function range(start, end) {
        return Array.from(Array(end - start + 1), (_, i) => i + start);
      }

      var sideWidth = maxLength < 9 ? 1 : 2;
      var leftWidth = (maxLength - sideWidth * 2 - 3) >> 1;
      var rightWidth = (maxLength - sideWidth * 2 - 3) >> 1;

      if (totalPages <= maxLength) {
        return range(1, totalPages);
      }

      if (page <= maxLength - sideWidth - 1 - rightWidth) {
        return range(1, maxLength - sideWidth - 1).concat(0, range(totalPages - sideWidth + 1, totalPages));
      }

      if (page >= totalPages - sideWidth - 1 - rightWidth) {
        return range(1, sideWidth).concat(0, range(totalPages - sideWidth - 1 - rightWidth - leftWidth, totalPages));
      }

      return range(1, sideWidth).concat(0, range(page - leftWidth, page + rightWidth), 0, range(totalPages - sideWidth + 1, totalPages));
    }

    $(function() {
      var numberOfItems = $(".card-content .card").length;
      var limitPerPage = 6;
      var totalPages = Math.ceil(numberOfItems / limitPerPage);
      var paginationSize = 7;
      var currentPage;

      function showPage(whichPage) {
        if (whichPage < 1 || whichPage > totalPages) return false;

        currentPage = whichPage;

        $(".card-content .card").hide().slice((currentPage - 1) * limitPerPage, currentPage * limitPerPage).show();

        $(".pagination li").slice(1, -1).remove();

        getPageList(totalPages, currentPage, paginationSize).forEach(item => {
          $("<li>").addClass("page-item").addClass(item ? "current-page" : "dots")
            .toggleClass("active", item === currentPage).append($("<a>").addClass("page-link")
              .attr({
                href: "javascript:void(0)"
              }).text(item || "...")).insertBefore(".next-page");
        });

        $(".previous-page").toggleClass("disabled", currentPage === 1);
        $(".next-page").toggleClass("disabled", currentPage === totalPages);
        return true;
      }

      $(".pagination").append(
        $("<li>").addClass("page-item").addClass("previous-page").append($("<a>").addClass("page-link").attr({
          href: "javascript:void(0)"
        }).text("Previous")),
        $("<li>").addClass("page-item").addClass("next-page").append($("<a>").addClass("page-link").attr({
          href: "javascript:void(0)"
        }).text("Next")),

      );

      $(".card-content").show();
      showPage(1);

      $(document).on("click", ".pagination li.current-page:not(.active)", function() {
        return showPage(+$(this).text());
      });
    });
  </script>



</body>
<?php include '../body/footer.php'; ?>

</html>