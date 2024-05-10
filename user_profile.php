<?php
require_once 'config.php'; 
require_once 'dbConnect.php'; 

session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit;
}

// Define variables and initialize with empty values
$username = $email = "";
$userId = $_SESSION['user_id'];
// echo "<pre>";
// print_r($userId);
// die;
// Prepare a select statement
$sql = "SELECT username, email FROM users WHERE `user_id` = ".$userId;
$result= $db->query($sql);
$result = $result->fetch_assoc();
$username = isset($result['username']) ? $result['username'] : "";
$email = isset($result['email']) ? $result['email'] : "";




$bookedEventSql = "SELECT date_time, event_name,number_of_people,price FROM booked_event WHERE `user_id` = ".$userId;

$bookedEvents= $db->query($bookedEventSql);
// $bookedEvents = $result->fetch_assoc();

// Close connection
$db->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <!-- Add your custom styles here -->
</head>
<body>
    <nav>
        <span class="logo">
            <a href="index.php"><span>Idyllic <b class="active"></b></span></a>
        </span>
        <ul>
            <li><a href="index.php">HOME</a></li>
            <li><a href="product.php">EVENTS</a></li>
            <li><a href="contact.php">CONTACT US</a></li>
            <?php
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
            echo '<li><a class="active" href="user_profile.php">MY PROFILE</a></li>';
            echo '<li><a href="logout.php">LOG OUT</a></li>';
        } else {
            echo '<li><a href="login.php">LOG IN</a></li>';
        }
        ?>
        </ul>
    </nav>
    <main>
    <h1>Welcome back, <?php echo ($username)? $username :$email; ?>!</h1>

      <h2>Booked events</h2>
      <center>
     <table border="1" cellspacing="0" cellpadding="0" width="1000" >
      <tr>
        <th>Event Name</th>
        <th>No Of people</th>
        <th>Price</th>
        <th>Date</th>
      </tr>
      <?php 
      while ($_be = $bookedEvents->fetch_assoc()) {
        
        echo "<tr>
        <td>".$_be['event_name']."</td>
        <td>".$_be['number_of_people']."</td>
        <td>".$_be['price']."</td>
        <td>".date('d/m/Y',strtotime($_be['date_time']))."</td>
      </tr>";
        // $data[] = $_be['event_name'];
      }
      ?>
      
     </table>
     </center>
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
            ><span>Idyllic <b class="active"></b></span
          ></a>
        </div>
        <div class="address">
          <p>Waterloo, Ontario</p>
        </div>
        <div class="info">
          <p><a href="tel:1234567890">12.3456.7890</a></p>
          <p>
            <a href="mail:info@idyllicevents.com">info@idyllicevents.com</a>
          </p>
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
