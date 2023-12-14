<?php

require_once "../resources/crud/bookingCrud.php";
require_once "../resources/crud/activityCrud.php";
session_start();
$bookingCRUD = new BookingCRUD();
$activityCRUD = new ActivityCRUD();

// Retrieve the username from the session
$username = $_SESSION['username'];

// Retrieve bookings
$bookings = $bookingCRUD->retrieveUserBookings(json_encode(array("userID"=>$_SESSION['userID'])));

// Retrieve bookings
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
  <link rel="stylesheet" href="styles/manage.css"> <!-- Include manage.css for dashboard styles -->
</head>
<body>
  <header>
    <div class="background-image"></div>
    <div class="title-bar">
      <h1 class="title">FreeSpace</h1>
      <nav>
        <ul>
          <li>
            <form action="logout.php" action = '' method="post">
              <div class="form-group">
                <button class="navbar-buttons" type="submit">Logout</button>
              </div>
            </form>
          </li>
        </ul>
      </nav>
    </div>
    <div class="welcome-section">
      <h2 class="welcome-heading">Welcome <?= $username ?>! </h2>
      <p class="welcome-text">Your local dashboard page for your activities, booking new activities and adjusting your personal information!</p>
      <div class="centered-icons">
        <!-- Place your centered icons here -->
      </div>
    </div>
  </header>
  <main>
    <div class="my-desk-section"> 
      <h2 class="my-desk-heading">My Dashboard</h2> 
      <p class="welcome-text">Below is your dashboard for updating and selecting your personal details, registered classes and other information.</p>

        <section class="activities-section">
            <h2 class="activities-heading">Browse Activities</h2>
            <form class="padding-below" method="GET" action="">
                <input type="text" name="search" placeholder="Search for Activities (leave blank to search for all activities)">
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

                        if ($filteredActivities){
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
                            echo '<button type="submit" class ="register-btn" name="add-booking" >JOIN</button>';
                            echo '</form>';
                            echo '</div>';
                          }
                        } else{
                          echo "You search query returned no result";
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
                            echo '<button type="submit" class ="register-btn" name="add-booking" >JOIN</button>';
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

      <section class="activities-section">
        <h2 class="activities-heading">Booked Activities</h2>
        <p class="activities-description">
          Your booked activities.
        </p>
        <div class="activity-cards">
          <!-- Booked activity cards dynamically added using PHP -->

            <?php
              // Loop through each activity to display editable forms
                  foreach ($bookings as $booking) {
                      echo '<div class="activity-card">';
                      echo '<img src="./img/' . $booking['image'] . '" alt="' . $booking['activityName'] . '">';
                      echo '<h3>' . $booking['activityName'] . '</h3>';
                      echo '<p>' . $booking['activityDescription'] . '</p>';
                      echo '<div class="activity-details">';
                      echo '<p><strong>Date: </strong> &nbsp ' . $booking['activityDate'] . '</p>';
                      echo '<p><strong>Price: </strong> &nbsp£' . $booking['price'] . '</p>';
                      echo '<p><strong>Room: </strong> &nbsp' .  $booking['room'] . '</p>';
                      echo '</div>';
                      // Add more activity details if necessary

                      echo '<form action="../resources/user-inputs/remove-booking.php" method="post">';
                      echo '<input type="hidden" name="activityID" value="' . $booking['activityID'].'">';
                      echo '<input type="hidden" name="bookingID" value="' . $booking['bookingID'].'">';
                      echo '<button type="submit" class ="remove-btn" name="remove-booking" >Remove</button>';
                      echo '</form>';
                      echo '</div>';
              };
            ?>

        </div>
      </section>



        <section class="activities-section user-account-section">
            <h2 class="activities-heading">Account Details</h2>
            <form class="profile-form" id="update-profile-form" action="../resources/user-inputs/update_user_info_process.php" method="post">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Change Username:
                    </label>
                    <input type="text" id="username" name="username" value="" >
                    <button type="submit" class="register-btn" name="update-type" value="update-username">Submit</button>
                </div>
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Change Email Address:
                    </label>
                    <input type="email" id="email" name="email" value="">
                    <button type="submit" class="register-btn" name="update-type" value="update-email">Submit</button>
                </div>
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Change Password:
                    </label>
                    <input type="password" id="password" name="password" value="">
                    <button type="submit" class="register-btn" name="update-type" value="update-password">Submit</button>
                </div>
                <div class="form-group">
                    <label for="phone">
                        <i class="fas fa-phone"></i> Change Phone Number:
                    </label>
                    <input type="tel" id="phone" name="phone" value="">
                    <button type="submit" class="register-btn" name="update-type" value="update-phone">Submit</button>
                </div>
            </form>
        </section>
    </div>
  </main>


    <div class="summary-section">
      <div class="summary-content">
        <p>&copy; 2023 FreeSpace. All rights reserved.</p>
        <!-- Additional summary content or copyright information -->
      </div>
    </div>
  </footer>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
  <script src="js/manage.js"></script>
</body>
</html>
