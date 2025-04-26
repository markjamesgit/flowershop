<?php
include 'admin-change-password.php'; 
include('admin-nav.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo/logo2.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia&effect=neon|outline|emboss|shadow-multiple">
    <title> ADMIN SETTINGS </title>
</head>
<body>
<div class="settings">
    <form method="post">
        <!-- Current Password -->
        <label for="current_password">OLD PASSWORD</label> <br>
        <input type="password" id="current_password" name="current_password" placeholder="Enter your old password" required /> <br> <br>

        <label for="password">NEW PASSWORD</label> <br>
        <input type="password" id="password" name="password" placeholder="Enter your new password" value="" required /> <br> <br>

        <!-- Confirm New Password -->
        <label for="confirm_password">CONFIRM NEW PASSWORD</label> <br>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your new password" value="" required /> <br> <br>

        <!-- Submit Button -->
        <button class="change" type="submit" name="change_password">Change Password</button>
        </form>
    </div>
</body>
</html>