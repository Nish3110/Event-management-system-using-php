<?php
session_start();
require_once 'config.php'; 
require_once 'dbConnect.php'; 
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
  <body>
    <div class="popup d-none">
      <div class="overlay"></div>
     <div class="popupmain">
      <div class="popcontent">
        <div class="top">
          <p class="poptitle"></p>
          <div class="close">X</div>
        </div>
        <div class="bottom">
          <p class="cat">Category: <span class="popcat"></span></p>
          <img class="popupImg" src="" alt="">
          <p class="description"></p>
        </div>
      </div>
     </div>
    </div>
    <nav>
      <span class="logo">
        <a href="index.php"
          ><span>Idyllic <b class="active"></b></span></a
        >
      </span>
      <ul>
        <li><a href="index.php"> HOME</a></li>
        <li><a class="active" href="product.php">EVENTS</a></li>
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
<?php

  $sql = "SELECT events.event_id, events.event_name, events.image_path 
          FROM events 
          GROUP BY events.event_id";
  $result = $db->query($sql);
?>

<main class="product_main">
  <h1>EVENTS</h1>
  <div class="product">
    <div class="cards">
     
      <?php
      if ($result->num_rows > 0) {
        
        // output data of each row
        while($row = $result->fetch_assoc()) {
          echo "<section class='card w-33'>";
          echo "<img src='" . $row["image_path"]. "' alt='" . $row["event_name"]. "' />";
          echo "<div class='bottom'>";
          echo "<p class='category'>" . $row["event_name"]. "</p>";
          echo "<a href='event_detail.php?event_id=" . $row["event_id"] . "' class='readmore pdp'>More Details</a>";
          echo "</div>";
          echo "</section>";
        }
      } else {
        echo "0 results";
      }
      ?>
    </div>
  </div>
</main>

<?php
$conn->close();
?>


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

    <script src="script.js"></script>
  </body>
        </html>