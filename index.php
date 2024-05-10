<?php

session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <script
      src="https://code.jquery.com/jquery-3.6.4.js"
      integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body class="homepage">
    <div class="popup d-none">
      <div class="overlay"></div>
     <div class="popupmain">
      <div class="popcontent">
        <div class="top">
          <h2>Site Brief Overview</h2>
          <div class="close">X</div>
        </div>
        <div class="bottom">
          <div class="page">
            <h3>HOME</h3>
            <ul>
              <li>Homepage has slider</li>
              <li>It works on button (prev and next)</li>
              <li>On the Homepage, after 2 seconds the popup will show up which provides a brief overview of the site and now it's visible 😁</li>
              <li>If you close this popup with close button, it will not show up till next 1 hour[This will work only with live server]</li>
            </ul>
          </div>
          <div class="page">
            <h3>PRODUCTS</h3>
            <ul>
              <li>Product page has popup.</li>
              <li>When you click on <b>More Details</b>, popup will show up.</li>
              <li>We are getting data from the clicked card like - title, category, image [description will be same in all cards]</li>
            </ul>
          </div>
          <div class="page">
            <h3>FAQs</h3>
            <ul>
              <li>FAQs page has Accordion</li>
            </ul>
          </div>
          <div class="page">
            <h3>CONTACT US</h3>
            <ul>
              <li>Contact us page has Form Validation</li>
            </ul>
          </div>
        </div>
      </div>
     </div>
    </div>
    <?php
    // Check if the user is logged in
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] === true) {
            // Admin navigation bar
            echo '<nav>
                    <span class="logo">
                        <a href="index.php"><span>Idyllic <b class="active"></b></span></a>
                    </span>
                    <ul>
                        <li><a class="active" href="index.php"> HOME</a></li>
                        <li><a href="product.php">EVENTS</a></li>
                        <li><a href="admin_panel.php">ADMIN PANEL</a></li>
                        <li><a href="contact.php">CONTACT US</a></li>
                        <li><a href="logout.php">LOG OUT</a></li>
                    </ul>
                </nav>';
        } else {
            // Regular user navigation bar
            echo '<nav>
                    <span class="logo">
                        <a href="index.php"><span>Idyllic <b class="active"></b></span></a>
                    </span>
                    <ul>
                        <li><a class="active" href="index.php"> HOME</a></li>
                        <li><a href="product.php">EVENTS</a></li>
                        <li><a href="contact.php">CONTACT US</a></li>
                        <li><a href="logout.php">LOG OUT</a></li>
                    </ul>
                </nav>';
        }
    } else {
        // Non-logged in user navigation bar
        echo '<nav>
                <span class="logo">
                    <a href="index.php"><span>Idyllic <b class="active"></b></span></a>
                </span>
                <ul>
                    <li><a class="active" href="index.php"> HOME</a></li>
                    <li><a href="product.php">EVENTS</a></li>
                    <li><a href="contact.php">CONTACT US</a></li>
                    <li><a href="login.php">LOG IN</a></li>
                </ul>
            </nav>';
    }
?>


    <main>
      <div class="slider-main">
        <div class="slider">
          <img src="/images/banner4.jpg" alt="" />
        </div>
        <div class="slider">
          <img src="/images/banner2.jpg" alt="" />
        </div>
        <div class="slider">
          <img src="/images/banner3.jpg" alt="" />
        </div>
        <div class="slider">
          <img src="/images/banner.jpg" alt="" />
        </div>
        <div class="prevbtn sliderbtn">
          <img src="/images/prev.png" alt="" />
        </div>
        <div class="nextbtn sliderbtn">
          <img src="/images/next.png" alt="" />
        </div>
      </div>
      <div class="content">
        <h2 class="heading">LET'S PLAN YOUR EVENT</h2>

        <img src="/images/IMG1.png" alt="about us" class="about" />
        <p class="about_txt">
          We help people plan their events hassle free and at an affordable pricing.
          Our wide range of events and dynamic team provides excellent services related to events all under one roof.
        </p>
        <p class="about_txt">
          At Idyllic, we bring a wealth of expertise to the table. Whether you're planning a corporate conference, a grand wedding, a product launch, or a social celebration, our team of seasoned professionals is dedicated to translating your vision into reality. From concept to execution, we meticulously handle every detail to ensure a flawless and unforgettable event
      
        </p>
        <p class="about_txt">
          Recognizing that each event is unique, we pride ourselves on offering tailored solutions that cater to your specific needs and preferences. Our skilled event planners work closely with clients to understand their objectives, style, and desired outcomes, customizing every aspect of the event to reflect your distinct personality or brand identity.
        </p>
        <p class="about_txt">
          In a rapidly evolving events landscape, we stay ahead of trends and infuse creativity in project. From cutting-edge technology integrations to unique thematic elements, to bring fresh and innovative ideas to the table, so that your event stands out from the crowd.
        </p>

        <h2 class="clear heading">Featured EVENTS</h2>
<div class="cards">
  <section class="card w-33">
    <img src="/images/wedding.jpg" alt="blog1 feature image" />
    <div class="bottom">
      <p class="category">Weddings</p>
      <p class="title">
        <a href="#">Elevating love stories with exquisite details, our wedding expertise turns your special day into a timeless celebration of romance and elegance.</a>
      </p>
      <!-- Add link around the button -->
      <a href="product.php" class="readmore">Explore</a>
    </div>
  </section>
  <section class="card w-33 card-2">
    <img src="/images/concert.jpg" alt="blog2 feature image" />
    <div class="bottom">
      <p class="category">Concerts</p>
      <p class="title">
        <a href="#">From stage to spectacle, we orchestrate unforgettable concerts, blending artistic vision with seamless execution for an electrifying audio-visual experience.</a>
      </p>
      <!-- Add link around the button -->
      <a href="product.php" class="readmore">Explore</a>
    </div>
  </section>
  <section class="card w-33">
    <img src="/images/celebration.jpg" alt="blog3 feature image" />
    <div class="bottom">
      <p class="category">Celebrations</p>
      <p class="title">
        <a href="#">
          Igniting joy in every detail, our celebrations are a harmonious fusion of creativity and precision, creating memories that resonate with laughter, warmth, and pure delight.
        </a>
      </p>
      <!-- Add link around the button -->
      <a href="product.php" class="readmore">Explore</a>
    </div>
  </section>
</div>

        <h2 class="heading card_heading">CUSTOMER REVIEWS</h2>
        <div class="cards reviews_card">
          <section class="card w-50 card_1">
            <img src="/images/user1.jpg" alt="customer1 image" />
            <p class="review">
              In trying to get my Big event done right, I connected with
              <b>Idyllic</b> and I'm happy that I did. They were able to
              follow designs given by them to create the experience and they were
              collaborative in helping us define the event experience we
              needed.
            </p>
            <p class="reviewer">CHAVEZ PROCOPE</p>
          </section>
          <section class="card w-50">
            <img src="/images/user2.jpg" alt="customer2 image" />
            <p class="review">
              We would definitely recommend Sanket and the team at
              <b>Idyllic</b>. They completed all of the work we needed on
              our event to a great standard. We would definitely work with
              <b>Idyllic</b> again.
            </p>
            <p class="reviewer">JOHN S</p>
          </section>
        </div>
      </div>
    </main>
    <footer>
      <div class="left w-33">
        <p class="head">QUICK LINKS</p>
        <ul class="clear">
          <li><a href="product.php">EVENTS</a></li>
          <li><a href="contact.php">Contact Us</a></li>
          
          <li><a href="">Privacy & Policy</a></li>
          <li><a href="">Terms & Conditions</a></li>
        </ul>
      </div>
      <div class="mid w-33">
        <div class="logo">
          <a href="index.php"
            ><span>Idyllic <b class="active"></b></span></a
          >
        </div>
        <div class="address">
          <p>Waterloo, Ontario </p>
        </div>
        <div class="info">
          <p><a href="tel:1234567890">12.3456.7890</a></p>
          <p><a href="mail:info@idyllicevents.com">info@idyllicevents.com</a></p>
        </div>
        <div class="copy">
          <p>Idyllic. All rights reserved |<span> Sitemap</span></p>
        </div>
      </div>
      <div class="right w-33">
        <div class="signup">
          <p>SIGN UP FOR</p>
          <p>THE NEWSLETTER</p>
        </div>
        <div class="newsletter">
          <input type="email" name="" id="" placeholder="@ email address ..." />
          <button>GO</button>
        </div>

        <div class="social">
          <div class="border">
            <img src="/images/footer/facebook.png" alt="facebook" />
            <img src="/images/footer/instagram.png" alt="instagram" />
            <img src="/images/footer/tik-tok.png" alt="tik-tok" />
            <img src="/images/footer/twitter.png" alt="twitter" />
          </div>
        </div>
      </div>
    </footer>
    <script src="script.js"></script>
  </body>
        </html>

