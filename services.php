<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UmahKawan</title>

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
  <style>
    /* Video gallery: make embeds show only video, maintain aspect ratio */
    .ftco-video-gallery .video-wrap {
      background: #000;
      overflow: hidden;
      border-radius: 6px;
    }

    /* use portrait 9:16 aspect so TikTok vertical videos fill the frame without cropping */
    .ftco-video-gallery .video-wrap .embed-frame {
      width: 100%;
      height: 0;
      padding-bottom: 177.78%;
      position: relative;
    }

    .ftco-video-gallery .video-wrap iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: 0;
    }

    @media (max-width: 767px) {
      .ftco-video-gallery .col-md-4 {
        margin-bottom: 1rem;
      }
    }

    /* Blur only the slider background image (uses CSS variable set on the element) */
    .slider-item {
      position: relative;
      overflow: hidden;
    }

    .slider-item::before {
      content: "";
      position: absolute;
      inset: 0;
      background-image: var(--bg-url);
      background-size: cover;
      background-position: center;
      filter: blur(2px);
      transform: scale(1.03);
      z-index: 0;
    }

    /* keep overlay and content above the blurred layer */
    .slider-item>.overlay,
    .slider-item .container {
      position: relative;
      z-index: 1;
    }
  </style>
</head>

<body>
  <?php require "navbar.php"; ?>


  <!-- END nav -->

  <section class="home-hero">
    <div class="hero-bg d-flex align-items-center" style="background-image: url('img/Gallery2.png');">
      <div class="overlay"></div>

      <div class="container text-center">
        <h1 class="mb-3 mt-5 bread">Review</h1>

        <p class="breadcrumbs mb-0">
          <a href="index.php">Home</a>
          <span class="mx-2">/</span>
          <span>Review</span>
        </p>
      </div>
    </div>
  </section>

  <section class="ftco-section ftco-services">
    <div class="container">
      <div class="row">
        <div class="col-md-4 ftco-animate">
          <div class="media d-block text-center block-6 services">
            <div class="icon d-flex justify-content-center align-items-center mb-5">
              <span class="flaticon-choices"></span>
            </div>
            <div class="media-body">
              <h3 class="heading">Easy to Order</h3>
              <p>
                Whether you’re craving something sweet, spicy, or tangy, Umah
                Kawan makes it easy to order your favorite rujak in just a few
                clicks.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-4 ftco-animate">
          <div class="media d-block text-center block-6 services">
            <div class="icon d-flex justify-content-center align-items-center mb-5">
              <span class="flaticon-delivery-truck"></span>
            </div>
            <div class="media-body">
              <h3 class="heading">Fastest Delivery</h3>
              <p>
                Our fresh rujak is delivered quickly to your door. We make
                sure every bite stays delicious and full of flavor.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-4 ftco-animate">
          <div class="media d-block text-center block-6 services">
            <div class="icon d-flex justify-content-center align-items-center mb-5">
              <span class="flaticon-coffee-bean"></span>
            </div>
            <div class="media-body">
              <h3 class="heading">Fresh Ingredients</h3>
              <p>
                Made from handpicked tropical fruits and authentic Balinese
                spices, every serving is crafted with care to bring you the
                perfect balance of sweet, spicy, and tangy flavors.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Video Gallery: 6 TikTok/Instagram embeds -->
  <section class="ftco-section ftco-video-gallery">
    <div class="container">
      <div class="row justify-content-center mb-4">
        <div class="col-md-8 text-center heading-section ftco-animate">
          <h2 class="mb-3">From Our Socials</h2>
          <p class="mb-4">
            Enjoy short clips from our TikTok and Instagram — recipes,
            behind-the-scenes, and customer favorites.
          </p>
        </div>
      </div>

      <div class="row">
        <!-- Each column contains a TikTok embed. Replace the blockquote's cite hrefs with Instagram embed code if needed. -->
        <div class="col-md-4 mb-4 ftco-animate">
          <div class="video-wrap">
            <div class="embed-frame">
              <iframe src="https://www.tiktok.com/embed/v2/7502293484468997383?autoplay=1&loop=1&muted=1"
                allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4 ftco-animate">
          <div class="video-wrap">
            <div class="embed-frame">
              <iframe src="https://www.tiktok.com/embed/v2/7566861582559431943?autoplay=1&loop=1&muted=1"
                allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4 ftco-animate">
          <div class="video-wrap">
            <div class="embed-frame">
              <iframe src="https://www.tiktok.com/embed/v2/7179041704509590810?autoplay=1&loop=1&muted=1"
                allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4 ftco-animate">
          <div class="video-wrap">
            <div class="embed-frame">
              <iframe src="https://www.tiktok.com/embed/v2/7564261197210537234?autoplay=1&loop=1&muted=1"
                allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4 ftco-animate">
          <div class="video-wrap">
            <div class="embed-frame">
              <iframe src="https://www.tiktok.com/embed/v2/7550899881553005831?autoplay=1&loop=1&muted=1"
                allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4 ftco-animate">
          <div class="video-wrap">
            <div class="embed-frame">
              <iframe src="https://www.tiktok.com/embed/v2/7504519206344461586?autoplay=1&loop=1&muted=1"
                allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-12 text-center">
          <p class="small text-muted">
            Tip: What are you waiting for? Come and enjoy delicious rujak at
            Umah Kawan today!
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Note: using iframe-based TikTok embeds (autoplay + loop + muted) so the platform script is not required -->

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