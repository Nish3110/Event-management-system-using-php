<?php
session_start();

// Check if success message is set
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';

// Database connection
$db = new mysqli('localhost', 'root', '', 'idyllic');

// Function to delete user
function deleteUser($userId, $db) {
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Check if delete request is made
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $userId = $_POST['user_id'];
    if (deleteUser($userId, $db)) {
        $successMessage = "User deleted successfully";
    } else {
        $errorMessage = "Failed to delete user";
    }
}

// Fetch all users
$result = $db->query("SELECT * FROM users");
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
    <?php if (!empty($successMessage)) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo $successMessage; ?>
        </div>
    <?php endif; ?>

    <h1>User Management - Admin Panel</h1>

    <table class="user-table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php
        $index = 1; 
        while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $index++; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo ($row['isAdmin'] == 1) ? 'Admin' : 'User'; ?></td>
                <td class="action-links">
                <a href="edit_user.php?userId=<?php echo $row['user_id']; ?>" target="_blank"><button>Edit</button></a>

                    <br/>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
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
function showEditForm(userId) {
    window.location.href = "edit_user.php?userId=" + userId;
}
</script>
</body>
</html>
