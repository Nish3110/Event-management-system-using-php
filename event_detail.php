<?php


session_start();
include_once 'config.php'; 
include_once 'dbConnect.php'; 

// Check if 'event_id' is set in the URL as a GET parameter and is a number
if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
    $eventId = $_GET['event_id'];

    $bookedEventDateSelectQuery = "SELECT DATE(`date_time`) as date FROM booked_event GROUP BY DATE(date_time);";
    // Fetch transaction data from the database 
    $result= $db->query($bookedEventDateSelectQuery);
    $data = [];
    while ($dates = $result->fetch_row()) {
      $data[] = $dates[0];
    }
    $datesExclude = implode(',',$data); 
    
} else {
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $event_id = isset($_POST['event_id']) ?  $_POST['event_id'] : '';
    $event_name = isset($_POST['event_name']) ? $_POST['event_name'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $date_time = isset($_POST['date_time'])? date('Y-m-d',strtotime($_POST['date_time'])) : '';
    $number_of_people = isset($_POST['number_of_people']) ? $_POST['number_of_people']: '';
    $user_id = isset($_SESSION['user_id']) ?$_SESSION['user_id'] : '';
 
    // if(!$user_id){
    //   header('Location: login.php');
    //   return;
    // Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  echo "User is not logged in!";
  exit(); // stop script execution after debug message
}

    // }

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "idyllic";

    // Create connection

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    // Prepare and bind
    $stmt = $db->prepare("INSERT INTO booked_event (date_time,  event_name, event_id, number_of_people, user_id, price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssiiid',$date_time, $event_name, $event_id, $number_of_people, $user_id, $price);
    // Execute the statement
    $stmt->execute();
    header('Location: checkout.php?event_id='.$event_id);

    
    
    // Close the statement and the connection
    $stmt->close();
    $db->close();
  }else{
      // If 'event_id' is not set, or is not numeric, you can redirect to a default page or show an error
      die('Event ID is required and must be a number.');
  }
    
}



?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="e_detail.css">
  </head>
  <body class="event_detail">

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
    <main>
<?php

$host = 'localhost';
$db = 'idyllic';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}

// Prepare your SQL statement to include event image and price details

$stmt = $pdo->prepare("SELECT e.event_id, e.event_name, e.description, e.image_path, p.price, p.package_name, p.details FROM events e JOIN packages p ON e.event_id = p.package_id WHERE e.event_id = :event_id");

// Bind the dynamic eventId to the placeholder in the SQL statement
$stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);

// Execute the statement
$stmt->execute();

// Fetch the results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if we got any result
if ($results) {
    foreach ($results as $row) {
        echo '<div class="event_img">';
        echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="">';
        echo '</div>';
        echo '<div class="content">';
        echo '<div class="e_detail_main">';
        echo '<div class="head">';
        echo '<h1>' . htmlspecialchars($row['event_name']) . '</h1>';
        echo '<p class="desc"><b>Description: </b>' . htmlspecialchars($row['description']) . '</p>';
        echo '<p class="price"><b>Price: </b>$' . htmlspecialchars($row['price']) . '</p>';
        echo '</div>';
        echo '<div class="package_details">';
        echo '<h3>Package Name: ' . htmlspecialchars($row['package_name']) . '</h3>';
        echo '<p>Package Details: ' . htmlspecialchars($row['details']) . '</p>';
        echo '</div>';
        if (!empty($results)) { // Only show the button if event details are available
            echo '<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="post">';
            echo '    <input type="hidden" name="event_id" value="' . htmlspecialchars($eventId) . '">';
            echo '    <input type="hidden" name="event_name" value="' . htmlspecialchars($row['event_name']) . '">';
            echo '    <input type="hidden" name="price" value="' . htmlspecialchars($row['price']) . '">';
            echo '    <input type="text" name="date_time" id="datepicker"><br>';
            echo '    <input type="number" name="number_of_people" id=""><br>';
            echo '    <input class="addtocart" type="submit" value="Add to Cart">';
            echo '</form>';

        } else {
            echo 'No Event Information Available!';
        }
        echo '</div>';
        echo '</div>';
    }
} else {
    echo 'No package details found for this event.';
}

?>

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
    <script src="script.js"></script>
    <script>
  $(function() {
    var excludedDates = "<?php echo $datesExclude; ?>".split(',');

    $("#datepicker").datepicker({
        minDate: -20,
        maxDate: "+1M +10D",
        beforeShowDay: function(date) {
            var dateString = $.datepicker.formatDate('yy-mm-dd', date);
            return [excludedDates.indexOf(dateString) === -1]; // Return true for selectable dates, false for excluded dates
        }
    });
  });
  </script>
  </body>
        </html>

