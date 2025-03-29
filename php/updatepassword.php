<?php
    //retrieving a value from the query string of a URL and storing it in a variable named $email.
    $email = $_GET['email'];
    //user is logged in
    if (isset($_POST['updatepass']))
    {
    //start changing password
    //check fields
    $newpassword = md5($_POST['newpassword']);
    $confirmpassword = md5($_POST['confirmpassword']);
    $conn = mysqli_connect("localhost:3306", "root", "", "flowershop");
    $sql ="SELECT password FROM users WHERE email='.$email'";
    $result = mysqli_query($conn, $sql);
    //check two new passwords
    if($newpassword==$confirmpassword){
    //successs
    //change password in db
    $querychange = "UPDATE users SET password='" .password_hash($_POST['newpassword'], PASSWORD_DEFAULT)."' WHERE email='" .$email."'";
    $change_result = mysqli_query($conn, $querychange);
    echo "<script>alert('Your password has been changed'); window.location.href = 'login.php';</script>";
    }
    else{
    echo "<script>alert('New password doesn\'t match!');</script>";
    }  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="bee.png"/>
    <title> UPDATE PASSWORD </title>
</head>
<body>
    <div class="content">
        <form method="POST">
            <label>New Password</label><br>
            <input type="password" name="newpassword" placeholder="************" required /><br> <br>
            <label>Confirm Password</label><br>
            <input type="password" name="confirmpassword" placeholder="************" required /> <br>
            <input type="submit" name="updatepass" value="Update Password">
        </form>
    </div>    
</body>
</html>