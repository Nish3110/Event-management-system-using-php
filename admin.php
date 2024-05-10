<?php
  session_start();

  // Check if success message is set
  $successMessage = isset($_GET['success']) ? $_GET['success'] : '';
  
  // Database connection
  $db = new mysqli('localhost', 'root', '', 'idyllic');

  // Check if form is submitted for deleting an event
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "delete" && isset($_POST["event_id"])) {
    $event_id = $_POST["event_id"];
    
    // Prepare and execute the delete query
    $sql = "DELETE FROM events WHERE event_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $event_id);
    if ($stmt->execute()) {
        // Redirect to admin.php with success message
        header("Location: admin.php?success=Event deleted successfully");
        exit;
    } else {
        echo "Error deleting event: " . $db->error;
    }
  }
  // Fetch all events
  $result = $db->query("SELECT * FROM events");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Management System</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <nav>
      <span class="logo">
        <a href="index.php"
          ><span>Idyllic <b class="active"></b></span
        ></a>
      </span>
      <ul>
        <li><a class="active" href="index.php"> HOME</a></li>
        
        <li><a href="all_users.php">USERS</a></li>
        <li><a href="booked_event.php">BOOKED EVENTS</a></li>
        <!-- <li><a href="contact.php">CONTACT US</a></li> -->
        <li>
          <?php
            if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] === 1) {
              echo '<a href="add_event.php">ADD EVENT</a>';
            }
          ?>
        </li>
        <li>
          <?php
          if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
              echo '<a href="logout.php">LOG OUT</a>';
          } else {
              echo '<a href="login.php">LOG IN</a>';
          }
          ?></li>
      </ul>
    </nav>
     
   

<div class="admin-panel">
    
    <?php if (!empty($successMessage)) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo $successMessage; ?>
        </div>
    <?php endif; ?>
    
    <h1>Idyllic Events - Admin Panel</h1>
    <div class="cards">
        <!-- Main content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <!-- Events Cards -->
            <div class="cards">
                <?php
                while ($row = $result->fetch_assoc()) {
                  echo '<section class="card w-33">';
                  echo '<img src="' . $row['image_path'] . '" class="card-img-top" alt="' . $row['event_name'] . '">';
                  echo '<div class="bottom">';
                  echo '<h5 class="category">' . $row['event_name'] . '</h5>';
                  echo '<p class="title">' . $row['description'] . '</p>';
                  echo '<a class="readmorepdp" href="update_event.php?event_id=' . $row['event_id'] . '">Edit</a>'; // Modify this line
                  echo '<form action="admin.php" method="post" style="display: inline-block;" onsubmit="return confirm(\'Are you sure you want to delete this event?\');">';
                  echo '<input type="hidden" name="action" value="delete">';
                  echo '<input type="hidden" name="event_id" value="' . $row['event_id'] . '">';
                  echo '<input type="submit" class="readmore pdp" value="Delete">';
                  echo '</form>';
                  echo '</div>';
                  echo '</section>';
                }              
                ?>
            </div>
        </main>
    </div>
</div>




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
    
  </body>
  </html>
