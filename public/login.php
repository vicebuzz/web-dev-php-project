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
      <h2 class="login-heading">Enter Details</h2>
        <?php
        $message = '';
        $success = false;

        if (isset($_GET['message'])) {
            $message = $_GET['message'];
            $success = $_GET['success'] === 'true' ? true : false;
        }
        ?>
        <!-- Display alert message -->
        <?php if (!empty($message)): ?>
            <p style="font-family: 'Bubblegum Sans', cursive; margin-bottom: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 12px; border-radius: 5px; color: <?php echo $success ? '#059af0' : '#ff0000'; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <form class="login-form" action="../resources/user-inputs/login_process.php" method="POST">
        <input type="text" class="input-field" placeholder="Username" name="username" required>
        <input type="password" class="input-field" placeholder="Password" name="password" required>
        <button type="submit" class="login-button">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
