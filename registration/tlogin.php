<?php
   include('../action.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log In Form</title>

    <!-- Favicons -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="main">
    <section class="signup">
        <div class="container">
            <div class="signup-content">
                <form method="POST" id="signup-form" class="signup-form">
                    <h2 class="form-title">CO-ORDINATOR LOGIN</h2>
                    
                    <!-- Username Field -->
                    <div class="form-group">
                        <input type="text" class="form-input" name="username" id="username" placeholder="Username (e.g., 1DT20IS058)" required />
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <input type="password" class="form-input" name="psw" id="password" placeholder="Password" required />
                        <span toggle="#password" class="zmdi zmdi-eye field-icon toggle-password"></span>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <input type="submit" name="tlogin" id="submit" class="form-submit" value="Login Now" />
                    </div>
                </form>

                <!-- Link to Sign Up -->
                <p class="loginhere">
                    Don't have an account? <a href="tsignup.php" class="loginhere-link">Sign Up Here</a>
                </p>
            </div>
        </div>
    </section>
</div>


    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>