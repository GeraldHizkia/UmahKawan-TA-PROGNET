<?php
include "config.php";

include "functions.php"

  ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UmahKawan</title>

  <link rel="stylesheet" href="./css/bootstrap.min.css" />
  <link rel="stylesheet" href="./css/animate.css" />

  <link rel="stylesheet" href="./css/owl.carousel.min.css" />
  <link rel="stylesheet" href="./css/owl.theme.default.min.css" />
  <link rel="stylesheet" href="./css/magnific-popup.css" />

  <link rel="stylesheet" href="./css/aos.css" />

  <link rel="stylesheet" href="./css/ionicons.min.css" />

  <link rel="stylesheet" href="./css/flaticon.css" />
  <link rel="stylesheet" href="./css/icomoon.css" />
  <link rel="stylesheet" href="./css/style.css" />
  <style>
    /* Map helpers with fixed height */
    .map-gap {
      margin: 30px 0;
    }

    .map-responsive {
      position: relative;
      height: 400px;
      overflow: hidden;
    }

    .map-responsive iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: 0;
    }
  </style>
</head>

<body>

  <?php require "navbar.php"; ?>

  <?php require "carousel.php"; ?>


  <section class="ftco-intro">
    <div class="container-wrap">
      <div class="wrap d-md-flex align-items-xl-end">
        <div class="info">
          <div class="row no-gutters">
            <div class="col-md-4 d-flex ftco-animate">
              <div class="icon"><span class="icon-phone"></span></div>
              <div class="text">
                <h3>+62 8953-3818-1468</h3>
                <p>
                  Authentic Indonesian Fruit Salad, Made with Love. Original
                  Flavors, Cozy Vibes. Every bite is a story, every blend is a
                  moment of warmth.
                </p>
              </div>
            </div>
            <div class="col-md-4 d-flex ftco-animate">
              <div class="icon"><span class="icon-my_location"></span></div>
              <div class="text">
                <h3>Jl. P. Bungin I No.14</h3>
                <p>Pedungan, Denpasar Selatan, Kota Denpasar, Bali</p>
              </div>
            </div>
            <div class="col-md-4 d-flex ftco-animate">
              <div class="icon"><span class="icon-clock-o"></span></div>
              <div class="text">
                <h3>Open Everyday</h3>
                <p>12:00am - 18:00pm</p>
              </div>
            </div>
          </div>
        </div>
        <div class="book p-4">
          <h3>Order Now</h3>
          <form id="orderForm" class="appointment-form" onsubmit="return handleWhatsAppOrder(event)">
            <div class="d-md-flex">
              <div class="form-group">
                <input type="text" class="form-control" id="firstName" placeholder="First Name" required />
              </div>
              <div class="form-group ml-md-4">
                <input type="text" class="form-control" id="lastName" placeholder="Last Name" required />
              </div>
            </div>
            <div class="d-md-flex">
              <div class="form-group">
                <input type="text" class="form-control" id="address" placeholder="Alamat" required />
              </div>
            </div>
            <div class="d-md-flex">
              <div class="form-group">
                <textarea id="orderMessage" cols="30" rows="2" class="form-control" placeholder="Order"
                  required></textarea>
              </div>
              <div class="form-group ml-md-4">
                <button type="submit" class="btn btn-white py-3 px-4">Order via WhatsApp</button>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </section>



  <section class="ftco-about d-md-flex">
    <div class="one-half img" style="background-image: url(./img/warung\ rujak.png)"></div>
    <div class="one-half ftco-animate">
      <div class="overlap">
        <div class="heading-section ftco-animate">
          <span class="subheading">Discover</span>
          <h2 class="mb-4">Our Story</h2>
        </div>
        <div>
          <p>
            On its journey, Umah Kawan found its heart in the small streets of
            Pedungan. What began as a simple local stall soon became a
            gathering place for friends, laughter, and the unmistakable flavor
            of Bali. Every bowl, every serving, carries the warmth of
            community and the spirit of home. The people came, one by one,
            drawn by the aroma and the smiles behind the counter. They shared
            stories, memories, and countless moments over plates of rujak and
            tipat cantok. Word spread across the island, and soon Umah Kawan
            was no longer just a name — it was an experience loved by many.
            Today, with more than 2,000 five-star reviews, Umah Kawan
            continues to serve not only food, but friendship in every bite.
          </p>
        </div>
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
                Whether you’re craving something sweet, spicy, or tangy, Umah Kawan makes it easy to order your favorite
                rujak in just a few clicks.
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
                Our fresh rujak is delivered quickly to your door. We make sure every bite stays delicious and full of
                flavor.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-4 ftco-animate">
          <div class="media d-block text-center block-6 services">
            <div class="icon d-flex justify-content-center align-items-center mb-5">
              <img src="./fonts/flaticon/icon-fruit.png" width="50%">
            </div>
            <div class="media-body">
              <h3 class="heading">Fresh Ingredients</h3>
              <p>
                Made from handpicked tropical fruits and authentic Balinese spices, every serving is crafted with care
                to bring you the perfect balance of sweet, spicy, and tangy flavors.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 pr-md-5">
          <div class="heading-section text-md-right ftco-animate">
            <span class="subheading">Discover</span>
            <h2 class="mb-4">Our Menu</h2>
            <p class="mb-4">
              We bring you a menu bursting with flavor, using only the freshest fruits, crispest vegetables, and
              signature sauces handcrafted daily. Get ready for a delicious explosion of sweet, sour, and spicy.
            </p>
            <p>
              <a href="./menu.php" class="btn btn-primary btn-outline-primary px-4 py-3">View Full Menu</a>
            </p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-6">
              <div class="menu-entry">
                <a href="#" class="img" style="background-image: url(./img/syrtlrcantok.jpg)"></a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="menu-entry mt-lg-4">
                <a href="#" class="img" style="background-image: url(./img/bg-rujak2.png)"></a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="menu-entry">
                <a href="#" class="img" style="background-image: url(./img/esdaluman2.png)"></a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="menu-entry mt-lg-4">
                <a href="#" class="img" style="background-image: url(./img/tipat_cantok.png)"></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-counter ftco-bg-dark img" id="section-counter" style="background-image: url(./img/gambar_1.jpg)"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <div class="row">
            <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
              <div class="block-18 text-center">
                <div class="text">
                  <div class="icon d-flex justify-content-center align-items-center">
                    <img src="./fonts/flaticon/icon-branch.png" width="50%" style="object-fit: contain;"
                      alt="Branch Icon">
                  </div>

                  <strong class="number" data-number="5">0</strong>
                  <span>Umah Kawan Branches</span>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
              <div class="block-18 text-center">
                <div class="text">
                  <div class="icon d-flex justify-content-center align-items-center">
                    <img src="./fonts/flaticon/icon-award.png" width="50%" style="object-fit: contain;"
                      alt="Awards Icon">
                  </div>
                  <strong class="number" data-number="20">0</strong>
                  <span>Number of Awards</span>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
              <div class="block-18 text-center">
                <div class="text">
                  <div class="icon d-flex justify-content-center align-items-center">
                    <img src="./fonts/flaticon/icon-cust.png" width="50%" style="object-fit: contain;"
                      alt="Customer Icon">
                  </div>
                  <strong class="number" data-number="2521">0</strong>
                  <span>Happy Customer</span>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
              <div class="block-18 text-center">
                <div class="text">
                  <div class="icon d-flex justify-content-center align-items-center">
                    <img src="./fonts/flaticon/icon-staf.png" width="50%" style="object-fit: contain;" alt="Staff Icon">
                  </div>
                  <strong class="number" data-number="100">0</strong>
                  <span>Staff</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-section">
    <div class="container">
      <div class="row justify-content-center mb-5 pb-3">
        <div class="col-md-7 heading-section ftco-animate text-center">
          <span class="subheading">Discover</span>
          <h2 class="mb-4">Umah Kawan Best Sellers</h2>
          <p>
            These are the dishes everyone's talking about. Hand-picked favorites and timeless classics, perfected to be
            the freshest, spiciest, and most satisfying treats on the island. Taste what makes us the best.
          </p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="menu-entry">
            <a href="#" class="img" style="background-image: url(./img/rujakuahpindangHD.png)"></a>
            <div class="text text-center pt-4">
              <h3><a href="./product-single.php">Rujak Kuah Pindang</a></h3>
              <p>
                Segar pedas gurih khas kuah pindang Bali.
              </p>
              <p class="price"><span>Rp8.000</span></p>
              <p>
                <a href="#" class="btn btn-primary btn-outline-primary">Add to Cart</a>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="menu-entry">
            <a href="#" class="img" style="background-image: url(./img/tipat_cantok2.png)"></a>
            <div class="text text-center pt-4">
              <h3><a href="#">Tipat Cantok</a></h3>
              <p>
                Lontong sayur khas Bali dengan bumbu kacang gurih.

              </p>
              <p class="price"><span>Rp10.000</span></p>
              <p>
                <a href="#" class="btn btn-primary btn-outline-primary">Add to Cart</a>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="menu-entry">
            <a href="#" class="img" style="background-image: url(./img/bulungboni.jpeg)"></a>
            <div class="text text-center pt-4">
              <h3><a href="#">Bulung Boni Cantok</a></h3>
              <p>
                Bulung boni disiram bumbu kacang gurih pedas.

              </p>
              <p class="price"><span>Rp14.000</span></p>
              <p>
                <a href="#" class="btn btn-primary btn-outline-primary">Add to Cart</a>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="menu-entry">
            <a href="#" class="img" style="background-image: url(./img/images.jpeg)"></a>
            <div class="text text-center pt-4">
              <h3><a href="#">Es Extra Joss Susu</a></h3>
              <p>
                Segarnya susu berpadu energi dari Extra Joss.
              </p>
              <p class="price"><span>Rp5.000</span></p>
              <p>
                <a href="#" class="btn btn-primary btn-outline-primary">Add to Cart</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-gallery">
    <div class="container-wrap">
      <div class="row no-gutters">
        <div class="col-md-3 ftco-animate">
          <span href="#" class="gallery img d-flex align-items-center"
            style="background-image: url(./img/restoran1.jpg)">

          </span>
        </div>
        <div class="col-md-3 ftco-animate">
          <span href="#" class="gallery img d-flex align-items-center"
            style="background-image: url(./img/restoran2.jpg)">

          </span>
        </div>
        <div class="col-md-3 ftco-animate">
          <span href="#" class="gallery img d-flex align-items-center"
            style="background-image: url(./img/gallery3.png)">

          </span>
        </div>
        <div class="col-md-3 ftco-animate">
          <span href="#" class="gallery img d-flex align-items-center"
            style="background-image: url(./img/gallery1.jpg)">

          </span>
        </div>
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
  <section class="ftco-section img" id="ftco-testimony" style="background-image: url(./img/rujakuahpindangHD.png)"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
      <div class="row justify-content-center mb-5">
        <div class="col-md-7 heading-section text-center ftco-animate">
          <span class="subheading">Testimony</span>
          <h2 class="mb-4">Customers Says</h2>
          <p>
            Your satisfaction is our most delicious ingredient. See and experience the real experiences of customers who
            have enjoyed the freshness of our Rujak and the warmth of our Balinese cuisine.
          </p>
        </div>
      </div>
    </div>
    <div class="container-wrap">
      <div class="row d-flex no-gutters">
        <div class="col-lg align-self-sm-end">
          <div class="testimony">
            <blockquote>
              <p>
                &ldquo;“Best rujak in Bali! The mix of sweet, spicy, and tangy flavors is just perfect. I come here
                almost every week — it never disappoints!”.&rdquo;
              </p>
            </blockquote>
            <div class="author d-flex mt-4">
              <div class="image mr-3 align-self-center">
                <img src="img/maudy_cust2.jpg" alt="" />
              </div>
              <div class="name align-self-center">
                Ayu Prameswari
                <span class="position">Illustrator Designer</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg align-self-sm-end">
          <div class="testimony overlay">
            <blockquote>
              <p>
                &ldquo;Umah Kawan is a hidden gem in Pedungan! Authentic Balinese flavors and warm hospitality — I’ll
                definitely come back next time I visit Bali.&rdquo;
              </p>
            </blockquote>
            <div class="author d-flex mt-4">
              <div class="image mr-3 align-self-center">
                <img src="img/jerome_cust1.jpg" alt="" />
              </div>
              <div class="name align-self-center">
                Jerome Polin
                <span class="position">Influencer</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg align-self-sm-end">
          <div class="testimony">
            <blockquote>
              <p>
                &ldquo;The rujak reminds me of my childhood! Spicy, fresh, and full of flavor. Plus, their delivery
                service is super fast and always on time. &rdquo;
              </p>
            </blockquote>
            <div class="author d-flex mt-4">
              <div class="image mr-3 align-self-center">
                <img src="img/gerald_cust3.jpg" alt="" />
              </div>
              <div class="name align-self-center">
                Gerald Hizkia
                <span class="position">Student</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg align-self-sm-end">
          <div class="testimony overlay">
            <blockquote>
              <p>
                &ldquo;I’ve tried many rujak places, but Umah Kawan has the best balance of taste and texture. You can
                really feel it’s made with love.&rdquo;
              </p>
            </blockquote>
            <div class="author d-flex mt-4">
              <div class="image mr-3 align-self-center">
                <img src="img/person_3.jpg" alt="" />
              </div>
              <div class="name align-self-center">
                Gusti Joko
                <span class="position">Actress</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg align-self-sm-end">
          <div class="testimony">
            <blockquote>
              <p>
                &ldquo;This place deserves all the five-star reviews! Every dish feels homemade and so good. The rujak
                bulung is a must-try for anyone visiting Bali. &rdquo;
              </p>
            </blockquote>
            <div class="author d-flex mt-4">
              <div class="image mr-3 align-self-center ">
                <img src="img/marulis_cus5.jpg" alt="" />
              </div>
              <div class="name align-self-center">
                Marulis
                <span class="position">Soldier</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="map-gap">
    <div class="map-responsive">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2217.168506788972!2d115.20344982241302!3d-8.685001196221139!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd241ae1c626eef%3A0x2d7612833e0600fa!2sUmah%20Kawan%20Pedungan!5e1!3m2!1sid!2sid!4v1762246776584!5m2!1sid!2sid"
        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
  <?php include 'footer.php'; ?>
  <!-- loader -->
  <?php include 'loader.php'; ?>

  <script src="./js/jquery.min.js"></script>
  <script src="./js/jquery-migrate-3.0.1.min.js"></script>
  <script src="./js/popper.min.js"></script>
  <script src="./js/bootstrap.min.js"></script>
  <script src="./js/jquery.easing.1.3.js"></script>
  <script src="./js/jquery.waypoints.min.js"></script>
  <script src="./js/jquery.stellar.min.js"></script>
  <script src="./js/owl.carousel.min.js"></script>
  <script src="./js/jquery.magnific-popup.min.js"></script>
  <script src="./js/aos.js"></script>
  <script src="./js/jquery.animateNumber.min.js"></script>
  <script src="./js/scrollax.min.js"></script>
  <script src="./js/main.js"></script>
</body>

</html>