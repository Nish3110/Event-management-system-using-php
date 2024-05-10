<?php

session_start();

include_once 'config.php'; 
include_once 'dbConnect.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Validate name
    if (empty($name)) {
        echo "Name is required";
        return;
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        return;
    }

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Prepare and bind
    $stmt = $db->prepare("INSERT INTO Contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    // Execute the statement
    $stmt->execute();

    echo "New record created successfully";

    // Close the statement and the connection
    $stmt->close();
    $db->close();
}


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
  <body class="contactus">
    <nav>
      <span class="logo">
        <a href="index.php"
          ><span>Idyllic <b class="active"></b></span></a
        >
      </span>
      <ul>
        <li><a href="index.php"> HOME</a></li>
        <li><a href="product.php">EVENTS</a></li>
        <li>
          <a class="active" href="contact.php">CONTACT US</a>
          
        </li>
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
    <header>
      <h1>CONTACT US PAGE</h1>
    </header>
    <main>
    <div class="contact">
  <form action=""  onsubmit="return validateForm()" method="post">
    <fieldset>
      <legend></legend>
      <div class="input-fields">
        <label for="name">Name: </label>
        <input type="text" name="name" id="name" placeholder="Enter Your Name " />
        <small>Error</small>
      </div>

      <div class="input-fields">
        <label for="email">Email: </label>
        <input type="email" name="email" id="email" placeholder="Enter Your Email" />
        <small>Error</small>
      </div>

      <div class="input-fields">
        <label for="subject">Subject: </label>
        <input type="text" name="subject" id="subject" placeholder="Enter Subject Here" />
        <small>Error</small>
      </div>

      <div class="input-fields">
        <label for="message">Message: </label>
        <textarea name="message" id="message" placeholder="Enter Your Message Here"></textarea>
        <small>Error</small>
      </div>

      <div class="input-fields btns">
        <input class="submit button" type="submit" value="SUBMIT" />
      </div>
    </fieldset>
  </form>
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
    <script src="contact.js"></script>
  </body>
        </html>
