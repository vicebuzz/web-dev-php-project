<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sports Center</title>
    <style>
        /* Navigation Bar Styling */
        .navbar {
            background: transparent; /* Black to slightly blueish gradient */
            overflow: hidden;
            font-family: "Arial Rounded MT Bold", sans-serif; /* Set the font for the navigation bar */
            font-weight: bold;
            position: relative; /* Required for z-index */
            z-index: 2; /* Place the navigation bar above the content and gradient overlay */
            display: flex; /* Make the navigation bar a flex container */
            align-items: center; /* Vertically center the items in the navigation bar */
        }
        
        .navbar a {
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s; /* Smooth hover transition */
        }
        
        .navbar a:hover {
            background-color: white; /* White background on hover */
            color: black; /* Black text on hover */
        }

        /* Logo Styling */
        .logo {
            padding: 0 20px; /* Add spacing between logo and links */
        }

        .logo img {
            height: 40px; /* Adjust the height of the logo image as needed */
        }
        
        /* Body Styling */
        body {
            margin: 0;
            padding: 0;
            background-image: url('sports_center_image.jpg'); /* Set the image as background for the body */
            background-size: cover; /* Cover the entire body with the image */
            background-position: center; /* Center the image */
            color: white;
            position: relative; /* Required for the pseudo-element */
        }
        
        /* Gradient Overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), transparent); /* Black to transparent gradient */
            z-index: 1; /* Place the gradient overlay below the navigation bar */
        }
        
        /* Main Content Styling */
        .content {
            text-align: center;
            padding: 50px;
            position: relative;
            z-index: 2; /* Place the content above the gradient overlay */
        }
        
        .content h1 {
            font-size: 48px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            font-family: "Arial Rounded MT Bold", sans-serif; /* Set the font for the website name */
        }
        .content p {
            font-size: 18px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            font-family: "Arial Rounded MT Bold", sans-serif; /* Set the font for the website name */
            padding-bottom: 50px;
        }
        
        /* Login Box Styling */
        .login-box {
            padding: 50px;
            background-color: rgba(29, 31, 36, 0.9); /* White with transparency */
            border-radius: 10px; /* Rounded square */
            margin: 0 auto; /* Center horizontally */
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center items horizontally */
            transition: background-color 0.3s
        }

        .login-box:hover {
            opacity: 100%;
            background-color: black;
        }

        .login-box h2 {
          color: #ff;
          font-family: "Arial Rounded MT Bold", sans-serif;
          width: 100%;
        }

      
        .login-box input[type="text"],
        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 80%;
            padding: 10px;
            border: none;
            border-radius: 25px;
            text-align: center; /* Center text in input fields */
            margin-bottom: 10px; /* Add spacing between input fields */
        }
        .form {
            padding-bottom: 20px;
        }
        .login-box button {
            background-color: #0074D9; /* Light blue login button */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
        <a href="">Home</a>
        <a href="#">Sports</a>
        <a href="#">Facilities</a>
        <a href="#">Events</a>
        <a href="#">Contact</a>
    </div>
    
    <div class="content">
        <h1>Welcome to SportsLtd</h1>
        <p>Login to access your portal for bookings and sign--ons for our latest events</p></p>
        
        <div class="login-box">
            <h2>Enter details</h2>
            <form action="login.php" method="POST">
                <input type="text" placeholder="Username" required>
                <input type="password" placeholder="Password" required>
                <div class ="button">
                  <button type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
