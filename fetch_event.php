<?php
require_once 'config.php'; 
require_once 'dbConnect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $event_id = $_POST["event_id"];
  // Fetch event details
  $result = $db->query("SELECT * FROM events WHERE event_id = $event_id");

  if ($result->num_rows > 0) {
    // Output data of the event
    $event = $result->fetch_assoc();
    echo json_encode($event);
  } else {
    echo "No event found with id $event_id";
  }
}
?>
