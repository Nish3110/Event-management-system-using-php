<?php
session_start();

$servername = "localhost"; 
$dbusername = "root"; 
$dbpassword = ""; 
$dbname = "idyllic";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Server-side validation
    if (empty($email)) {
        echo "Email is required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else if (empty($password)) {
        echo "Password is required.";
    } else {
        // Check if the user exists in the database
        $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['hashed_password'])) {
                echo "Login successful!";
                // After successful login
                $_SESSION["loggedin"] = true;
                $_SESSION["isAdmin"] = $user['isAdmin']; // Store user role
                $_SESSION["user_id"] = $user['user_id']; // Set user ID

                if ($_SESSION["isAdmin"] == 1) {
                    // Redirect admin to admin.php
                    header('Location: admin.php');
                } else {
                    // Redirect normal users to index.php
                    header('Location: index.php');
                }
                exit;
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "User does not exist.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="register.css">
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
      <li><a href="index.php"> HOME</a></li>
      <li><a href="product.php">EVENTS</a></li>
      <!-- <li><a href="webpages/faq.php">FAQs</a></li> -->
      <li><a href="contact.php">CONTACT US</a></li>
      <li> <?php
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
            echo '<a href="logout.php">LOG OUT</a>';
        } else {
            echo '<a href="login.php">LOG IN</a>';
        }
        ?></li>
    </ul>
  </nav>
  <main>
    <div class="container_">
      <div class="card">
        <div class="card_title">
          <h1>Log In</h1>
        </div>
        <div class="form">
        <form action="login.php" method="post">
          <input type="email" name="email" placeholder="Email or Username" id="email" />
          <input type="password" name="password" placeholder="Password" id="password" />
        <div class="mt-3 pl-3"><a href="forgot_pass.php">Forgot Password?</a></div>
          <button class="register_btn">Sign In</button>
          </form>
        </div>
        <div class="card_terms">
            <input type="checkbox" name="" id="terms"> <span>I have read and agree to the <a href="">Terms of Service</a></span>
        </div>
        <span class="text-center">Don't have an account? <a href="register.php">Sign Up</a></span>
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