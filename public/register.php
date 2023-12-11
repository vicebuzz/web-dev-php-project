<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>FreeSpace - Login</title>
  <link href="https://fonts.googleapis.com/css?family=Permanent+Marker" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Bubblegum+Sans" rel="stylesheet">
  <link rel="stylesheet" href="styles/login.css">
</head>
<body>
  <div class="background-container">
    <div class="background-overlay"></div>
  </div>
  <div class="login-container">
    <h1 class="website-title">FreeSpace</h1>
    <div class="login-section">
      <h2 class="login-heading">Sign Up</h2>

      <form class="login-form" action="../resources/user-inputs/register_process.php" method="post">
        <input type="email" class="input-field" placeholder="Email" name="email" required>

        <input type="text" class="input-field" placeholder="Username" name="username" required>

        <input type="password" class="input-field" placeholder="Password" name="password" required>

        <input type="tel" class="input-field" placeholder="Phone Number" name="phone" required>

        <button type="submit" class="login-button"">Create Account</button>


        <p class="activity-details">Already got an account? <a href="login.php">Login here</a></p>
      </form>
    </div>
  </div>
</body>
</html>
