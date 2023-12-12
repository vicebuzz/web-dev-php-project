<?php
require_once "../resources/crud/activityCrud.php";
$activityCRUD = new ActivityCRUD();
$activities = $activityCRUD->getAllActivities();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FreeSpace</title>
  <link href="https://fonts.googleapis.com/css?family=Permanent+Marker" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Bubblegum+Sans" rel="stylesheet">
  <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/manage.css">
</head>
<body>
  <header>
    <div class="background-image"></div>
    <div class="title-bar">
      <h1 class="title">FreeSpace</h1>
      <nav>
        <ul>
          <li><a class="navbar-buttons" href="login.php">Login</a></li>
          <li><a  class="navbar-buttons" href="register.php">Signup</a></li>
        </ul>
      </nav>
    </div>
    <div class="welcome-section">
      <h2 class="welcome-heading">Welcome to FreeSpace</h2>
      <p class="welcome-text">Your local gym for freedom, exploration and personal gain</p>
      <div class="centered-icons">
        <!-- Place your centered icons here -->
        <!-- Example: -->
        <!-- <img src="icon1.png" alt="Icon 1">
             <img src="icon2.png" alt="Icon 2"> -->
      </div>
      <a href="login.php" class="get-started-button">Get Started</a>
    </div>
  </header>
  <main>
    <div class="about-us-section"> <!-- Updated class name -->
      <h2 class="about-us-heading">About Us</h2> <!-- Updated class name -->
      <p class="about-us-description"> <!-- Updated class name -->
        Here at FreeSpace, we believe that everyone deserves to feel good and healthy. That's why we offer a wide range of activities to suit every fitness level. Our goal is to provide a safe and enjoyable environment for everyone, regardless of their fitness level or experience. Safe to explore and strengthen your resolve, join FreeSpace today to find your greater you.
      </p>
      <!-- Existing code for activity cards remains unchanged -->
    </div>
      <section class="activities-section">
          <h2 class="activities-heading">Browse Activities</h2>
          <form class="padding-below" method="GET" action="">
              <input type="text" name="search" placeholder="Search activities...">
              <button class="register-btn" type="submit">Search</button>
          </form>
          <div></div>
          <p class="activities-description">
              Browse through a variety of activities and find the perfect one for you!
          </p>
          <div class="activity-cards">
              <?php
              // Check if the $activities array is set and is an array
              if (isset($activities) && is_array($activities)) {
                  // Check if a search query exists
                  if (isset($_GET['search']) && !empty($_GET['search'])) {
                      $search = strtolower($_GET['search']);
                      // Filter activities based on the search query
                      $filteredActivities = array_filter($activities, function ($activity) use ($search) {
                          return strpos(strtolower($activity['activityName']), $search) !== false;
                      });
                      // Display filtered activities
                      foreach ($filteredActivities as $activity) {
                          echo '<div class="activity-card">';
                          echo '<img src="./img/' . $activity['image'] . '" alt="' . $activity['activityName'] . '">';
                          echo '<h3>' . $activity['activityName'] . '</h3>';
                          echo '<p>' . $activity['activityDescription'] . '</p>';
                          echo '<div class="activity-details">';
                          echo '<p><strong>Date: </strong> &nbsp ' . $activity['activityDate'] . '</p>';
                          echo '<p><strong>Price: </strong> &nbsp£' . $activity['price'] . '</p>';
                          echo '<p><strong>Room: </strong> &nbsp' . $activity['room'] . '</p>';
                          // Add more activity details if necessary
                          echo '</div>';
                          echo '<form action="../resources/user-inputs/create-booking.php" method="post">';
                          echo '<input type="hidden" name="activityID" value="' . $activity['activityID'] . '">';
                          echo '</form>';
                          echo '</div>';
                      }
                  } else {
                      // Display all activities if no search query is present
                      foreach ($activities as $activity) {
                          echo '<div class="activity-card">';
                          echo '<img src="./img/' . $activity['image'] . '" alt="' . $activity['activityName'] . '">';
                          echo '<h3>' . $activity['activityName'] . '</h3>';
                          echo '<p>' . $activity['activityDescription'] . '</p>';
                          echo '<div class="activity-details">';
                          echo '<p><strong>Date: </strong> &nbsp ' . $activity['activityDate'] . '</p>';
                          echo '<p><strong>Price: </strong> &nbsp£' . $activity['price'] . '</p>';
                          echo '<p><strong>Room: </strong> &nbsp' . $activity['room'] . '</p>';
                          // Add more activity details if necessary
                          echo '</div>';
                          echo '<form action="../resources/user-inputs/create-booking.php" method="post">';
                          echo '<input type="hidden" name="activityID" value="' . $activity['activityID'] . '">';
                          echo '</form>';
                          echo '</div>';
                      }
                  }
              } else {
                  echo '<p>No activities found.</p>';
              }
              ?>
          </div>
      </section>

    <section class="summary-section">
      <div class="summary-content">
        <p>&copy; 2023 FreeSpace. All rights reserved.</p>
        <!-- Additional summary content or copyright information -->
      </div>
  </main>
</body>
</html>
