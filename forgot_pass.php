<?php

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;700;800;900&display=swap"
      rel="stylesheet"
    />
</head>
<body>
  <nav>
    <span class="logo">
      <a href="index.php"
        ><span>Idyllic <b class="active"></b></span></a
      >
    </span>
    <ul>
      <li><a class="active" href="index.php"> HOME</a></li>
      <li><a href="product.php">EVENTS</a></li>
      <!-- <li><a href="webpages/faq.php">FAQs</a></li> -->
      <li><a href="contact.php">CONTACT US</a></li>
      <?php
          if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
            echo '<li><a href="user_profile.php">MY PROFILE</a></li>';
              echo '<li> <a href="logout.php">LOG OUT</a></li>';
          } else {
              echo '<li> <a href="login.php">LOG IN</a></li>';
          }
          ?>
    </ul>
  </nav>
  <main>
    <div class="container_">
      <div class="card">
        <div class="card_title">
          <h1>Reset Your Password</h1>
        </div>
        <div class="form">
        <form action="register.php" method="post">
          <input type="text" name="forgot-pass" placeholder="Email or Username" id="forgot-pass"/>
            <button class="register_btn">Send Password Reset Email</button>
          </form>
        </div>
       
        <div class="text-center mt-4">Don't have an account? <a href="register.php">Sign Up</a></div>
        <div class="text-center mt-1">Back to <a href="login.php">Login</a></div>
      </div>
    </div>
  </main>
  <footer>
    <div class="left w-33">
      <p class="head">QUICK LINKS</p>
      <ul class="clear">
        <li><a href="product.php">EVENTS</a></li>
        <li><a href="contact.php">Contact Us</a></li>
        <!-- <li><a href="webpages/faq.php">FAQs</a></li> -->
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
          <img src="images/footer/facebook.png" alt="facebook" />
          <img src="images/footer/instagram.png" alt="instagram" />
          <img src="images/footer/tik-tok.png" alt="tik-tok" />
          <img src="images/footer/twitter.png" alt="twitter" />
        </div>
      </div>
    </div>
  </footer>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
      </html>