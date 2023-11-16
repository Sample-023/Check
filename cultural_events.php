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

      .header select {
        padding: 5px;
        border: 1px solid #ccc;
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
      }

      .event a:hover {
        background-color: #0056b3;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h1>Cultural</h1>
        <select id="myDropdown">
          <option value="music" >Music</option>
          <option value="cultural" selected>Cultural</option>
          <option value="education">Education</option>
          <option value="sports">Sports</option>
          <option value="political">Political</option>
          <option value="party">Party</option>
        </select>
      </div>

      <ul class="events">
        <?php
        session_start();

        @include 'config.php';
            


          // Fetch events with type "music" from the database
          $sql = "SELECT * FROM new_event WHERE eventCategory='cultural'";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $eventName = $row["eventName"];
              $date = $row["date"];
              $location = $row["location"];
              $time = $row["time"];

              // Display event details in HTML
              echo '<li class="event">';
              echo '<h2>' . $eventName . '</h2>';
              echo '<p>Date: ' . $date . '</p>';
              echo '<p>Location: ' . $location . '</p>';
              echo '<p>Time: ' . $time . '</p>';
echo '<a href="event_details.php?event_name=' . urlencode($eventName) . '">More Details</a>';
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