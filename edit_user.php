<?php
session_start();

// Check if user is logged in and has admin privileges
if (!isset($_SESSION["loggedin"]) || $_SESSION["isAdmin"] !== 1) {
    // Redirect to login page or display an error message
    header("Location: login.php");
    exit;
}

// Database connection
$db = new mysqli('localhost', 'root', '', 'idyllic');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract form data
    $userId = $_POST["userId"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $role = $_POST["role"];

    // Update user details in the database
    $query = "UPDATE users SET username=?, email=?, isAdmin=? WHERE user_id=?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssii", $name, $email, $role, $userId);
    
    if ($stmt->execute()) {
        // Redirect to the same page with success message
        header("Location: all_users.php?success=User details updated successfully");
        exit;
    } else {
        $errorMessage = "Error updating user details: " . $db->error;
    }
}

// Check if user ID is provided in the query parameter
if(isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    // Fetch user details from the database
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if($result->num_rows > 0) {
        // Fetch user details
        $user = $result->fetch_assoc();
    } else {
        // User not found
        $errorMessage = "User not found";
    }
} else {
    // User ID not provided in the query parameter
    $errorMessage = "User ID not provided";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>User Management - Admin Panel</title>
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
    <li><a href="product.php">EVENTS</a></li>
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
        ?>
    </li>
    </ul>
</nav>
     
<div class="admin-panel">
        <h1>Edit User - Admin Panel</h1>

        <?php if (!empty($errorMessage)) : ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Edit User Form -->
        <div id="editFormContainer">
            <form class="edit-form" id="editForm" action="" method="POST">
                <input type="hidden" name="userId" value="<?php echo $user['user_id']; ?>">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $user['username']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="0" <?php echo $user['isAdmin'] == 0 ? 'selected' : ''; ?>>User</option>
                        <option value="1" <?php echo $user['isAdmin'] == 1 ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
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
