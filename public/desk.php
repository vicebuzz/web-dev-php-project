<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FreeSpace</title>
  <link href="https://fonts.googleapis.com/css?family=Permanent+Marker" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Bubblegum+Sans" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="manage.css"> <!-- Include manage.css for dashboard styles -->
</head>
<body>
  <header>
    <div class="background-image"></div>
    <div class="title-bar">
      <h1 class="title">FreeSpace</h1>
      <nav>
        <ul>
          <li><a class="navbar-buttons" href="login.php">Logout</a></li>
        </ul>
      </nav>
    </div>
    <div class="welcome-section">
      <h2 class="welcome-heading">Welcome User</h2>
      <p class="welcome-text">Your local dashboard page for your activites, booking new activties and adjusting your personal information!</p>
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
        <p class="activities-description">
          Browse through a variety of activities and find the perfect one for you!
        </p>
        <div class="activity-cards">
          <!-- Activity cards dynamically added using PHP -->
          <div class="activity-card">
            <img src="gym.jpg" alt="Activity 1">
            <h3>Tread Marathon</h3>
            <p>Join our lead coach Joshua in the trek of a lifetime, on our treadmills!</p>
            <div class="activity-details">
              <p><strong>Date:</strong> January 15, 2024</p>
              <p><strong>Price:</strong> $10</p>
              <p><strong>Room:</strong> Gym Area</p>
              <!-- Add more activity details as needed -->
            </div>
            <button class="register-btn">Register</button>
          </div>

          <!-- Activity card 2 -->
          <div class="activity-card">
            <img src="swimming.jpg" alt="Activity 2">
            <h3>Swimming Session</h3>
            <p>Join a two-hour swimming session with family and friends!</p>
            <div class="activity-details">
              <p><strong>Date:</strong> January 20, 2024</p>
              <p><strong>Price:</strong> $8</p>
              <p><strong>Room:</strong> Pool Area</p>
              <!-- Add more activity details as needed -->
            </div>
            <button class="register-btn">Register</button>
          </div>

          <!-- Add more activity cards -->
          <!-- Activity card 3 -->
          <div class="activity-card">
            <img src="yoga.jpg" alt="Activity 3">
            <h3>Yoga Class</h3>
            <p>Relax your mind and body in our yoga session led by experienced instructors.</p>
            <div class="activity-details">
              <p><strong>Date:</strong> January 18, 2024</p>
              <p><strong>Price:</strong> $12</p>
              <p><strong>Room:</strong> Yoga Studio</p>
              <!-- Add more activity details as needed -->
            </div>
            <button class="register-btn">Register</button>
          </div>

          <!-- Activity card 4 -->
          <div class="activity-card">
            <img src="boxing.jpg" alt="Activity 4">
            <h3>Boxing Training</h3>
            <p>Get fit and learn boxing techniques in our intensive training sessions!</p>
            <div class="activity-details">
              <p><strong>Date:</strong> January 22, 2024</p>
              <p><strong>Price:</strong> $15</p>
              <p><strong>Room:</strong> Boxing Arena</p>
              <!-- Add more activity details as needed -->
            </div>
            <button class="register-btn">Register</button>
          </div>
          <!-- Add more activity cards as needed -->
        </div>
      </section>

      <section class="activities-section">
        <h2 class="activities-heading">Booked Activities</h2>
        <p class="activities-description">
          Your booked activities.
        </p>
        <div class="activity-cards">
          <!-- Booked activity cards dynamically added using PHP -->
          <div class="activity-card">
            <img src="boxing.jpg" alt="Activity 4">
            <h3>Boxing Training</h3>
            <p>Get fit and learn boxing techniques in our intensive training sessions!</p>
            <div class="activity-details">
              <p><strong>Date:</strong> January 22, 2024</p>
              <p><strong>Price:</strong> $15</p>
              <p><strong>Room:</strong> Boxing Arena</p>
              <!-- Add more activity details as needed -->
            </div>
            <button class="remove-btn">Remove</button>
          </div>
        </div>
      </section>
      <section class="activities-section user-account-section">
        <h2 class="activities-heading">Change Account Details</h2>
        <form class="profile-form" id="update-profile-form">
          <div class="form-group">
            <label for="original-email">Original Email Address:</label>
            <input type="email" id="original-email" name="original-email" placeholder="Enter your original email" required>
          </div>
          <div class="form-group">
            <label for="original-password">Original Password:</label>
            <input type="password" id="original-password" name="original-password" placeholder="Enter your original password" required>
          </div>
          <div class="form-group">
            <label for="new-email">New Email Address:</label>
            <input type="email" id="new-email" name="new-email" placeholder="Enter your new email" required>
          </div>
          <div class="form-group">
            <label for="new-password">New Password:</label>
            <input type="password" id="new-password" name="new-password" placeholder="Enter your new password" required>
          </div>
          <div class="form-group">
            <label for="confirm-password">Confirm New Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your new password" required>
          </div>
          <button type="submit" class="update-profile-btn">Update Profile</button>
        </form>
      </section>


    <div class="summary-section">
      <div class="summary-content">
        <p>&copy; 2023 FreeSpace. All rights reserved.</p>
        <!-- Additional summary content or copyright information -->
      </div>
    </div>
  </footer>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
  <script src="manage.js"></script>
</body>
</html>
