<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['admin_name'])) {
  header('Location: login_form.php');
  exit();
}

$adminName = $_SESSION['admin_name'];

// Logout functionality
if (isset($_POST['logout'])) {
  session_destroy();
  header('Location: home_page.php');
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Events List</title>
    <style>
      body {
        font-family: sans-serif;
      }

      .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
      }

      .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
      }

      .header h1 {
        font-size: 24px;
        font-weight: bold;
      }
      .events {
        list-style-type: none;
        margin: 0;
        padding: 0;
      }

      .event {
        border: 1px solid #ccc;
        margin-bottom: 20px;
        padding: 20px;
      }

      .event h2 {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
      }

      .event p {
        margin-bottom: 0;
      }

      .event a {
        display: block;
        text-align: center;
        padding: 10px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        margin-bottom: 5px;
      }

      .event a:hover {
        background-color: #0056b3;
        margin-bottom: 5px;
      }

      .event img {
      }
    </style>
  </head>
  <body>


   <div class="container">
      <div class="header">
         <h1>Events for approval</h1>
         <div class="user-info">
            <p>Welcome, <?php echo $adminName; ?></p>
            <form class="logout-form" method="post">
               <button type="submit" name="logout">Logout</button>
            </form>
         </div>
      </div>


      <ul class="events">
        <?php

        @include 'config.php';




        // Function to transfer event from event_form to new_event table
        function transferEvent($conn, $eventName) {
          $sql = "INSERT INTO new_event SELECT * FROM event_form WHERE eventName = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param('s', $eventName);
          if ($stmt->execute()) {
            return true;
          } else {
            return false;
          }
        }

        // Function to delete event from event_form table by event name
        function deleteEventByName($conn, $eventName) {
          $sql = "DELETE FROM event_form WHERE eventName = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param('s', $eventName);
          if ($stmt->execute()) {
            return true;
          } else {
            return false;
          }
        }

        // Check if the "Approve" button is clicked and transfer/delete the event
        if (isset($_GET['action']) && $_GET['action'] == 'approve' && isset($_GET['event_name'])) {
          $eventName = $_GET['event_name'];
          if (transferEvent($conn, $eventName) && deleteEventByName($conn, $eventName)) {
            echo "Event approved and transferred successfully.";
          } else {
            echo "Error approving event.";
          }
        }

        // Fetch events from the event_form table
        $sql = "SELECT * FROM event_form";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $eventName = $row["eventName"];
            $ticketPrice = $row["ticketPrices"];
            $date = $row["date"];
            $time = $row["time"];
            $eventImage = $row["eventImage"];
            $location = $row["location"];
            $eventCategory = $row["eventCategory"];
            $eventDescription = $row["eventDescription"];

            // Display event details in HTML
            echo '<li class="event">';
            echo '<h2>' . $eventName . '</h2>';
            echo '<p>Ticket Price: ' . $ticketPrice . '</p>';
            echo '<p>Date: ' . $date . '</p>';
            echo '<p>Time: ' . $time . '</p>';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($eventImage) . '" alt="Event Image" height="150px" width="150px">';
            echo '<p>Location: ' .$location . '</p>';
            echo '<a href="?action=approve&event_name=' . $eventName . '">Approve</a>';
            echo '<a href="?action=delete&event_name=' . $eventName . '">Delete</a>';
            echo '</li>';
          }
        } else {
          echo "No events found.";
        }

        // Close database connection
        $conn->close();
        ?>
      </ul>
    </div>
  </body>
</html>