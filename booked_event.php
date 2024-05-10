<?php
session_start();

// Check if success message is set
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';

// Database connection
$db = new mysqli('localhost', 'root', '', 'idyllic');

function getUsername($db, $user_id) {
    // Fetch username from users table based on user_id
    $sql = "SELECT username FROM users WHERE user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data of each row
        $row = $result->fetch_assoc();
        return $row['username'];
    } else {
        return "N/A"; // Return "Not Available" if username is not found
    }
}

// Function to delete booked event
function deleteBookedEvent($eventId, $db) {
    $sql = "DELETE FROM booked_event WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $eventId);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Check if delete request is made
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $eventId = $_POST['id'];
    if (deleteBookedEvent($eventId, $db)) {
        $successMessage = "Event booking deleted successfully";
    } else {
        $errorMessage = "Failed to delete event booking";
    }
}

// Fetch all booked events
$result = $db->query("SELECT * FROM booked_event");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Booked Events - Admin Panel</title>
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
    <?php if (!empty($successMessage)) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo $successMessage; ?>
        </div>
    <?php endif; ?>

    <h1>Booked Events - Admin Panel</h1>

    <table class="event-table">
        <tr>
            <th>ID</th>
            <th>Event Name</th>
            <th>Number of People</th>
            <th>Price</th>
            <th>Username</th>
            <th>Action</th>
        </tr>
        <?php
        $index = 1; 
        while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $index++; ?></td>
                <td><?php echo $row['event_name']; ?></td>
                <td><?php echo $row['number_of_people']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo getUsername($db, $row['user_id']); ?></td> <!-- Display Username -->
                <td class="action-links">
                <a href="edit_booked_event.php?id=<?php echo $row['id']; ?>" target="_blank"><button>Edit</button></a>
                   
                    <br/>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    
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
<script>
// Function to redirect to edit user page with user ID as query parameter
function showEditForm(id) {
    window.location.href = "edit_booked_event.php?id=" + id;
}
</script>
</body>
</html>
