<?php
require_once "../resources/crud/userCrud.php";
require_once "../resources/crud/bookingCrud.php";
require_once "../resources/crud/activityCrud.php";
require_once "../resources/crud/userCrud.php";
session_start();
$bookingCRUD = new BookingCRUD();
$activityCRUD = new ActivityCRUD();
$userCRUD = new UserCRUD();

// Retrieve the username from the session
$username = $_SESSION['username'];

$user = $userCRUD->getUser(json_encode(array('userID'=>$_SESSION['userID'])));

if (!$user[0]['isAdmin']){
    header("location: login.php");
}

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
                    <form action="../resources/user-inputs/logout.php" method="post">
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

        <section id="browse-activities" class="activities-section">
            <h2 class="activities-heading">Browse Activities</h2>
            <p class="activities-description">
                Browse through a variety of activities and find the perfect one for you!
            </p>
            <form class="padding-below" method="GET" action="">
                <input type="text" name="search" placeholder="Search activities (keep the search field empty and submit to view all activities)">
                <button class="register-btn" type="submit">Search</button>
            </form>
            <div></div>
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
                            echo '<button type="submit" class ="register-btn" name="add-booking" >JOIN</button>';
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

        <section id="booked-activities" class="activities-section">
            <h2 class="activities-heading">Booked Activities</h2>
            <p class="activities-description">
                Your booked activities, if you have joined any, will appear here.
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



        <section id="launch-activities" class="activities-section">
            <h2 class="activities-heading">Launch Activity</h2>
            <p class="activities-description">Launch an activity to the live database by filling in the form below</p>
            <div class="activity-card admin-form-card">
                <form class="profile-form" id="create-activity-form" action="../resources/admin-options/create-activity.php" method="post">
                    <div class="form-group">
                        <label for="activity-image">Activity Image:</label>
                        <input type="file" id="activity-image" name="activity-image" accept="image/*" required>
                        <img id="image-preview" class="activity-card" src="#" alt="Selected Image" style="display: none; max-width: 100%; margin-top: 10px;">

                    </div>

                    <div class="form-group">

                        <label for="activity-name">Activity Name:</label>
                        <input type="text" id="activity-name" name="activity-name" placeholder="Enter activity name" required>
                    </div>

                    <div class="form-group">
                        <label for="activity-description">Activity Description:</label>
                        <textarea id="activity-description" name="activity-description" placeholder="Enter activity description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="activity-date">Activity Date:</label>
                        <input type="date" id="activity-date" name="activity-date" required>
                    </div>
                    <div class="form-group">
                        <label for="activity-price">Activity Price:</label>
                        <input type="number" id="activity-price" name="activity-price" placeholder="Enter activity price" required>
                    </div>
                    <div class="form-group">
                        <label for="activity-room">Activity Room:</label>
                        <input type="text" id="activity-room" name="activity-room" placeholder="Enter activity room" required>
                    </div>
                    <button type="submit" class="register-btn">Publish</button>
                </form>
            </div>
        </section>

        <section id="revise-activities-section" class="activities-section">
            <h2 class="activities-heading">Revise Activities</h2>
            <p class="activities-description">View all activities within the database below. Replace the form fields and hit "update" to update the details of the activity, or "remove" the activity entirely with the remove button.</p>

            <!-- Search Form -->
            <form class="padding-below" method="GET" action="">
                <input type="text" name="revise_search" placeholder="Search activities (keep the search field empty and submit to view all activities)">
                <button class="register-btn" type="submit">Search</button>
            </form>

            <!-- View and Edit Existing Activities based on Search -->
            <div class="activity-cards-container">
                <?php
                // Check if the $activities array is set and is an array
                if (isset($activities) && is_array($activities)) {
                    // Check if a search query exists
                    if (isset($_GET['revise_search']) && !empty($_GET['revise_search'])) {
                        $search = strtolower($_GET['revise_search']);
                        // Filter activities based on the search query
                        $filteredActivities = array_filter($activities, function ($activity) use ($search) {
                            return strpos(strtolower($activity['activityName']), $search) !== false;
                        });
                        // Display filtered activities
                        foreach ($filteredActivities as $key => $activity) {
                            echo '<div class="activity-card">';
                            echo '<form class="edit-activity-form" action="../resources/admin-options/update-activities.php" method="post">';

                            // Activity image edit section
                            echo '<div class="activity-image">';
                            echo '<label for="edit-activity-image">Activity Image:</label>';
                            echo '<input type="file" id="edit-activity-image" name="edit_activity_image" accept="image/*">';
                            echo '<img class="activity-image-preview" src="./img/' . $activity['image'] . '" alt="' . $activity['activityName'] . '">';
                            echo '</div>';

                            // Activity details edit section
                            echo '<div class="activity-details-edit">';
                            echo '<div class="form-group">';
                            echo '<label for="edit-activity-name">Activity Name:</label>';
                            echo '<input type="text" id="edit-activity-name" name="edit_activity_name" value="' . $activity['activityName'] . '" required>';
                            echo '</div>';
                            echo '<div class="form-group">';
                            echo '<label for="edit-activity-description">Activity Description:</label>';
                            echo '<textarea id="edit-activity-description" name="edit_activity_description" required>' . $activity['activityDescription'] . '</textarea>';
                            echo '</div>';
                            echo '<div class="form-group">';
                            echo '<label for="edit-activity-date">Activity Date:</label>';
                            echo '<input type="date" id="edit-activity-date" name="edit_activity_date" value="' . $activity['activityDate'] . '" required>';
                            echo '</div>';
                            echo '<div class="form-group">';
                            echo '<label for="edit-activity-price">Activity Price:</label>';
                            echo '<input type="number" id="edit-activity-price" name="edit_activity_price" value="' . $activity['price'] . '" placeholder="Enter activity price" required>';
                            echo '</div>';
                            echo '<div class="form-group">';
                            echo '<label for="edit-activity-room">Activity Room:</label>';
                            echo '<input type="text" id="edit-activity-room" name="edit_activity_room" value="' . $activity['room'] . '" placeholder="Enter activity room" required>';
                            echo '<input type="hidden" name="activityID" value="' . $activity['activityID'] . '">';
                            echo '</div>';

                            // add hidden element to identify the action performed
                            echo '<input type="hidden" name="action_type" value="update">';

                            // Add update and delete buttons
                            echo '<div class="form-button-group">';
                            echo '<button type="submit" name="action" value="update_activity" class="register-btn">Update</button>';
                            echo '</div>';
                            echo '</div>';
                            echo '</form>';
                            echo '<form class="edit-activity-form" action="../resources/admin-options/delete-activity.php" method="post">';
                            echo '<div class="form-button-group">';
                            echo '<button type="submit" name="action" value="delete_activity" class="remove-btn">Delete</button>';
                            echo '</div>';
                            echo '<input type="hidden" name="activityID" value="' . $activity['activityID'] . '">';
                            echo '</form>';
                            echo '</div>';
                        }
                    } else {
                        // Display all activities if no search query is present
                        foreach ($activities as $key => $activity) {
                            echo '<div class="activity-card">';
                            echo '<form class="edit-activity-form" action="../resources/admin-options/update-activities.php" method="post">';
                            // Display activity card HTML here based on the $activities array
                            // Modify the HTML structure based on your requirements
                            // Activity image edit section
                            echo '<div class="activity-image">';
                            echo '<label for="edit-activity-image">Activity Image:</label>';
                            echo '<input type="file" id="edit-activity-image" name="edit_activity_image" accept="image/*">';
                            echo '<img class="activity-image-preview" src="./img/' . $activity['image'] . '" alt="' . $activity['activityName'] . '">';
                            echo '</div>';

                            // Activity details edit section
                            echo '<div class="activity-details-edit">';
                            echo '<div class="form-group">';
                            echo '<label for="edit-activity-name">Activity Name:</label>';
                            echo '<input type="text" id="edit-activity-name" name="edit_activity_name" value="' . $activity['activityName'] . '" required>';
                            echo '</div>';
                            echo '<div class="form-group">';
                            echo '<label for="edit-activity-description">Activity Description:</label>';
                            echo '<textarea id="edit-activity-description" name="edit_activity_description" required>' . $activity['activityDescription'] . '</textarea>';
                            echo '</div>';
                            echo '<div class="form-group">';
                            echo '<label for="edit-activity-date">Activity Date:</label>';
                            echo '<input type="date" id="edit-activity-date" name="edit_activity_date" value="' . $activity['activityDate'] . '" required>';
                            echo '</div>';
                            echo '<div class="form-group">';
                            echo '<label for="edit-activity-price">Activity Price:</label>';
                            echo '<input type="number" id="edit-activity-price" name="edit_activity_price" value="' . $activity['price'] . '" placeholder="Enter activity price" required>';
                            echo '</div>';
                            echo '<div class="form-group">';
                            echo '<label for="edit-activity-room">Activity Room:</label>';
                            echo '<input type="text" id="edit-activity-room" name="edit_activity_room" value="' . $activity['room'] . '" placeholder="Enter activity room" required>';
                            echo '<input type="hidden" name="activityID" value="' . $activity['activityID'] . '">';
                            echo '</div>';

                            // add hidden element to identify the action performed
                            echo '<input type="hidden" name="action_type" value="update">';

                            // Add update button
                            echo '<div class="form-button-group">';
                            echo '<button type="submit" name="action" value="update_activity" class="register-btn">Update</button>';
                            echo '</div>';
                            echo '</div>';
                            echo '</form>';

                            // Delete activity form
                            echo '<form class="delete-activity-form" action="../resources/admin-options/delete-activity.php" method="post">';
                            echo '<div class="form-button-group">';
                            echo '<button type="submit" name="action" value="delete_activity" class="remove-btn">Delete</button>';
                            echo '</div>';
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
    </div>
</main>


<div class="summary-section">
    <div class="summary-content">
        <p>&copy; 2023 FreeSpace. All rights reserved.</p>
        <!-- Additional summary content or copyright information -->
    </div>
</div>
</footer>
<script src="js/manage.js"></script>
</body>
</html>
