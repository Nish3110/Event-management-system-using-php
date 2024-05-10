<?php

session_start();
require_once 'config.php';
require_once 'dbConnect.php';

$userId = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
if(!$userId){
  header('Location: login.php');
  return;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
    $eventId = $_GET['event_id'];
    
} else {
    die('Invalid request or event ID missing.');
}

$sql = "SELECT username, email FROM users WHERE `user_id` = ".$userId;
$result= $db->query($sql);
$result = $result->fetch_assoc();
$username = isset($result['username']) ? $result['username'] : "";
$email = isset($result['email']) ? $result['email'] : "";
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <script
      src="https://code.jquery.com/jquery-3.6.4.js"
      integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
      crossorigin="anonymous"
    ></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="js/checkout.js" STRIPE_PUBLISHABLE_KEY="<?php echo STRIPE_PUBLISHABLE_KEY; ?>" defer></script>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="e_detail.css" />
  </head>
  <body class="event_detail">
    <nav>
      <span class="logo">
        <a href="index.php"
          ><span>Idyllic <b class="active"></b></span
        ></a>
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
      <div class="e_detail_main">
        <div class="col-md-7 container bg-default pt-5">
            <h1>Checkout</h1>

            <div class="panel">
    <div class="panel-heading">
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

// The event ID should be validated to ensure it's a proper integer
$eventId = filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT);

if ($eventId === null || $eventId === false) {
    die('Invalid Event ID');
}

// Prepare and execute the query to fetch package details
$stmt = $pdo->prepare("SELECT package_name, price FROM packages WHERE package_id = ?");
$stmt->execute([$eventId]);
$package = $stmt->fetch();
if (!$package) {
    die('Package not found.');
}

// Now $package['name'] and $package['price'] hold the event details
?>

<input type="hidden" id = "eventName" name="eventName"  value="<?php echo htmlspecialchars($package['package_name']); ?>">
<input type="hidden" id= "eventPrice" name="eventPrice" value="<?php echo htmlspecialchars($package['price']); ?>">

        <!-- Product Info -->
        <p><b>Event Name:</b> <?php echo $package['package_name']; ?></p>
        <p><b>Price:</b> <?php echo '$' . $package['price'] . ' ' . $currency; ?></p>
    </div>
    <div class="panel-body">
        <!-- Display status message -->
        <div id="paymentResponse" class="hidden"></div>
        <h4 class="my-4">Billing Address</h4>
        <!-- Display a payment form -->
        <form id="paymentFrm" class="hidden">
        <div class="form-row">
              <div class="col-md-6 form-group">
                <label for="firstname">First Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="firstname"
                  name="firstname"
                  placeholder="First Name"
                />
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>

              <div class="col-md-6 form-group">
                <label for="lastname">Last Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="lastname"
                  name="lastname"
                  placeholder="Last Name"
                />
                <div class="invalid-feedback">Valid last name is required.</div>
              </div>
            </div>

            <div class="form-group">
              <label for="username">Username</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">@</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="username"
                  name="username"
                  placeholder="Username"
                  required
                  value="<?php echo $username ?>"
                  <?php echo ($username) ? 'disabled': ''; ?>
                />
                <div class="invalid-feedback">Your username is required.</div>
              </div>
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                placeholder="you@example.com"
                required
                value="<?php echo $email ?>"
                <?php echo ($email) ? 'disabled': ''; ?>
              />
            </div>

            <div class="form-group">
              <label for="address_line">Address</label>
              <input
                type="text"
                class="form-control"
                id="address_line"
                name="address_line"
                placeholder="1234 Main Street"
                required
              />
              <div class="invalid-feedback">
                Please enter your shipping address.
              </div>
            </div>

            <div class="form-group">
              <label for="address_line2"
                >Address 2
                <span class="text-muted">(Optional)</span>
              </label>
              <input
                type="text"
                class="form-control"
                id="address_line2"
                name="address_line2"
                placeholder="Flat No"
              />
            </div>

            <div class="form-check">
              <input
                type="checkbox"
                class="form-check-input"
                id="same-adress"
              />
              Save this information for next time
              <label for="same-adress" class="form-check-label"></label>
            </div>
            <!-- <div class="form-group">
                <label>NAME</label>
                <input type="text" id="name" class="field" placeholder="Enter name" required="" autofocus="">
            </div>
            <div class="form-group">
                <label>EMAIL</label>
                <input type="email" id="email" class="field" placeholder="Enter email" required="">
            </div> -->

            <div id="paymentElement">
                <!--Stripe.js injects the Payment Element-->
            </div>

            <!-- Form submit button -->
            <button id="submitBtn" class="btn btn-success">
                <div class="spinner hidden" id="spinner"></div>
                <span id="buttonText">Pay Now</span>
            </button>
        </form>

        <!-- Display processing notification -->
        <div id="frmProcess" class="hidden" style="display: none;">
            <span class="ring"></span> Processing...
        </div>

        <!-- Display re-initiate button -->
        <div id="payReinit" class="hidden" style="display: none;">
            <button class="btn btn-primary" onClick="window.location.href=window.location.href.split('?')[0]"><i class="rload"></i>Re-initiate Payment</button>
        </div>
    </div>
</div>







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
    <script src="script.js"></script>

  </body>
  </html>
