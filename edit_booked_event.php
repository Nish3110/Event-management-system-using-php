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
    $id = $_POST["id"];
    $event_name = $_POST["event_name"];
    $number_of_people = $_POST["number_of_people"];
    $price = $_POST["price"];

    // Update user details in the database
    $query = "UPDATE booked_event SET event_name=?, number_of_people=?, price=? WHERE id=?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssii", $event_name, $number_of_people, $price, $id);
    
    if ($stmt->execute()) {
        // Redirect to the same page with success message
        header("Location: booked_event.php?success=Event details updated successfully");
        exit;
    } else {
        $errorMessage = "Error updating event details: " . $db->error;
    }
}

// Check if user ID is provided in the query parameter
if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch user details from the database
    $query = "SELECT * FROM booked_event WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if($result->num_rows > 0) {
        // Fetch user details
        $event = $result->fetch_assoc();
    } else {
        // User not found
        $errorMessage = "Event not found";
    }
} else {
    // User ID not provided in the query parameter
    $errorMessage = "Event ID not provided";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Event Management - Admin Panel</title>
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
        <h1>Edit Booked Event - Admin Panel</h1>

        <?php if (!empty($errorMessage)) : ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Edit User Form -->
        <div id="editFormContainer">
            <form class="edit-form" id="editForm" action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                <div class="form-group">
                    <label for="name">Event Name:</label>
                    <input type="text" id="event_name" name="event_name" value="<?php echo $event['event_name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="number_of_people">No. of People:</label>
                    <input type="number" id="number_of_people" name="number_of_people" value="<?php echo $event['number_of_people']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" value="<?php echo $event['price']; ?>" required>
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
