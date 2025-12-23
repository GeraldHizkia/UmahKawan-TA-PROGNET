<?php
include "config.php";
include "functions.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>UmahKawan</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet" />

  <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css" />
  <link rel="stylesheet" href="css/animate.css" />

  <link rel="stylesheet" href="css/owl.carousel.min.css" />
  <link rel="stylesheet" href="css/owl.theme.default.min.css" />
  <link rel="stylesheet" href="css/magnific-popup.css" />

  <link rel="stylesheet" href="css/aos.css" />

  <link rel="stylesheet" href="css/ionicons.min.css" />

  <link rel="stylesheet" href="css/flaticon.css" />
  <link rel="stylesheet" href="css/icomoon.css" />
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>

  <body>
    <?php require "navbar.php"; ?>


    <section class="home-hero">
      <div class="hero-bg d-flex align-items-center" style="background-image: url(img/bg1.png);">
        <div class="overlay"></div>

        <div class="container text-center">
          <h1 class="mb-3 mt-5 bread">Our Menu</h1>

          <p class="breadcrumbs mb-0">
            <a href="index.php">Home</a>
            <span class="mx-2">/</span>
            <span>Menu</span>
          </p>
        </div>
      </div>
    </section>



    <section class="ftco-intro">
      <div class="container-wrap">
        <div class="wrap d-md-flex align-items-xl-end">
          <div class="info">
            <div class="row no-gutters">
              <div class="col-md-4 d-flex ftco-animate">
                <div class="icon"><span class="icon-phone"></span></div>
                <div class="text">
                  <h3>(+62)895-3381-81468</h3>
                  <p>Authentic Indonesian Fruit Salad, Made with Love. Original Flavors, Cozy Vibes. Every bite is a
                    story, every blend is a moment of warmth.</p>
                </div>
              </div>
              <div class="col-md-4 d-flex ftco-animate">
                <div class="icon"><span class="icon-my_location"></span></div>
                <div class="text">
                  <h3>Denpasar Selatan</h3>
                  <p>Jl. P. Bungin I No.14, Pedungan, Bali 80222</p>
                </div>
              </div>
              <div class="col-md-4 d-flex ftco-animate">
                <div class="icon"><span class="icon-clock-o"></span></div>
                <div class="text">
                  <h3>Open Monday-Friday</h3>
                  <p>12:00am - 6:00pm</p>
                </div>
              </div>
            </div>
          </div>
          <div class="book p-4">
            <h3>Order Now</h3>
            <form action="#" class="appointment-form">
              <div class="d-md-flex">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="First Name" />
                </div>
                <div class="form-group ml-md-4">
                  <input type="text" class="form-control" placeholder="Last Name" />
                </div>
              </div>
              <div class="d-md-flex">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Alamat" />
                </div>
              </div>
              <div class="d-md-flex">
                <div class="form-group">
                  <textarea name="" id="" cols="30" rows="2" class="form-control" placeholder="Message"></textarea>
                </div>
                <div class="form-group ml-md-4">
                  <input type="submit" value="Order" class="btn btn-white py-3 px-4" />
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section">
      <div class="container">
        <div class="row">
          <?php include 'menu-display.php' ?>
        </div>
      </div>
    </section>

    <section class="ftco-menu mb-5 pb-5">
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-7 heading-section text-center ftco-animate">
            <span class="subheading">Discover</span>
            <h2 class="mb-4">Our Products</h2>
            <p>Feeling down? It’s time to grab our fresh rujak!.</p>
          </div>
        </div>
        <div class="row d-md-flex">
          <div class="col-lg-12 ftco-animate p-md-5">
            <?php include 'tab_menu_display.php' ?>
          </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
    <!-- loader -->
    <?php include 'loader.php'; ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/jquery.animateNumber.min.js"></script>
    <script src="js/scrollax.min.js"></script>
    <script src="js/main.js"></script>
  </body>

</html>