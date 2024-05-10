<?php
session_start();
// Database connection
include_once 'config.php'; 
include_once 'dbConnect.php'; 

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST["event_name"];
    $category_id = $_POST["category_id"];
    $description = $_POST["description"];
    
    // File upload
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["image_path"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image_path"]["tmp_name"]);
    if($check !== false) {
       
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            } else {
                // Save image to folder
                if (move_uploaded_file($_FILES["image_path"]["tmp_name"], $target_file)) {
                    // Insert new event
                    $sql = "INSERT INTO events (event_name, category_id, description, image_path) VALUES (?, ?, ?, ?)";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("siss", $event_name, $category_id, $description, $target_file);
                
                    if ($stmt->execute()) {
                        // Redirect to admin.php with success message
                        header("Location: admin.php?success=Event added successfully");
                        exit;
                    } else {
                        echo "Error: " . $sql . "<br>" . $db->error;
                    }
                
                    // Close statement
                    $stmt->close();
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        
    } else {
        echo "File is not an image.";
    }

    // Close connection
    $db->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    // Check if the user is logged in
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] === true) {
            // Admin navigation bar
            echo '<nav>
                    <span class="logo">
                        <a href="index.php"><span>Idyllic <b class="active"></b></span></a>
                    </span>
                    <ul>
                        <li><a class="active" href="index.php"> HOME</a></li>
                        <li><a href="product.php">EVENTS</a></li>
                        <li><a href="admin_panel.php">ADMIN PANEL</a></li>
                        <li><a href="contact.php">CONTACT US</a></li>
                        <li><a href="logout.php">LOG OUT</a></li>
                    </ul>
                </nav>';
        } else {
            // Regular user navigation bar
            echo '<nav>
                    <span class="logo">
                        <a href="index.php"><span>Idyllic <b class="active"></b></span></a>
                    </span>
                    <ul>
                        <li><a class="active" href="index.php"> HOME</a></li>
                        <li><a href="product.php">EVENTS</a></li>
                        <li><a href="contact.php">CONTACT US</a></li>
                        <li><a href="logout.php">LOG OUT</a></li>
                    </ul>
                </nav>';
        }
    } else {
        // Non-logged in user navigation bar
        echo '<nav>
                <span class="logo">
                    <a href="index.php"><span>Idyllic <b class="active"></b></span></a>
                </span>
                <ul>
                    <li><a class="active" href="index.php"> HOME</a></li>
                    <li><a href="product.php">EVENTS</a></li>
                    <li><a href="contact.php">CONTACT US</a></li>
                    <li><a href="login.php">LOG IN</a></li>
                </ul>
            </nav>';
    }
?>
     
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Create Event</h2>
                        <form action="add_event.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="event-name">Event Name:</label>
                                <input type="text" class="form-control" id="event-name" name="event_name" required>
                            </div>
                            <div class="form-group">
                                <label for="category-id">Category:</label>
                                <select class="form-control" id="category-id" name="category_id" required>
                                <option value="">Select categories</option>
                                    <?php
                                    
                                    // Fetch categories from the database
                                    $result = $db->query("SELECT * FROM categories");
                                    
                                    // Check if categories exist
                                    if ($result->num_rows > 0) {
                                        // Output each category as an option in the dropdown
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No categories found</option>';
                                    }
                                    
                                    // Close database connection
                                    $db->close();
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image-path">Image:</label>
                                <input type="file" class="form-control-file" id="image-path" name="image_path" required accept="image/*">
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Create Event</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
    
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
</body>
</html>
    


