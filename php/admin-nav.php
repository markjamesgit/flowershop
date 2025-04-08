<?php
$conn = mysqli_connect("localhost:3306", "root", "", "flowershop");

// Check if the newUsername query parameter is set
if (isset($_GET["newUsername"])) {
    // Retrieve the updated username from the query parameter
    $newAdminName = $_GET["newUsername"];
} else {
    // Check if the username is stored in the session
    if (isset($_SESSION['adminUsername'])) {
        // Retrieve the username from the session
        $newAdminName = $_SESSION['adminUsername'];
    } else {
        // Fetch the actual admin username from the database
        $result = mysqli_query($conn, "SELECT username FROM admin WHERE email = 'admin@gmail.com'");
        
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $newAdminName = $row['username'];
        } else {
            // If fetching from the database fails, use a default value
            $newAdminName = "ADMIN";
        }
    }
}

// Fetch the profile picture path from the database based on the retrieved username
$result = mysqli_query($conn, "SELECT image FROM admin WHERE username = '$newAdminName'");
if ($result && $row = mysqli_fetch_assoc($result)) {
    $profile_picture = $row['image'];
} else {
    // If fetching from the database fails or no image is found, provide a default path
    $profile_picture = "default_image.jpg"; // Update this with your default image path
}

// Check if the logout form is submitted
if (isset($_POST['logout'])) {
    // Perform logout logic
    session_destroy();
    header("Location: login.php"); // Redirect to the login page after logout
    exit();
}

mysqli_close($conn);
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
    <title>ADMIN PAGE</title>
</head>

<body>
    <div class="sidebar">
        <div class="content">
            <a href="admin-account.php?newUsername=<?php echo urlencode($newAdminName); ?>">
                <button class="admin-btn">
                    <img src="<?php echo $profile_picture; ?>" alt="">
                    <label> <?php echo $newAdminName; ?> </label>
                </button>
            </a>
            <div class="btn">
                <a href="php/admin-dashboard.php"><button class="dashboard"> <i class="fa-solid fa-house-chimney" style="color: #ffffff;"></i> <span> DASHBOARD </span> </button></a>
                <a href="php/add-product.php"> <button class="product"> <i class="fa-solid fa-box-open" style="color: #ffffff;"></i> <span> PRODUCT </span> </button> </a>
                <a href="php/category-management.php"> <button class="category"> <i class="fa-solid fa-list" style="color: #ffffff;"></i> <span> CATEGORY </span> </button> </a>
                <a href="php/product-inventory.php"> <button class="inventory"> <i class="fa-solid fa-clipboard-list" style="color: #ffffff;"></i> <span> INVENTORY </span></button> </a>
                <a href="php/orders.php"> <button class="orders"> <i class="fa-solid fa-cart-shopping" style="color: #ffffff;"></i> <span> ORDERS </span></button> </a>
                <a href="php/sales.php"> <button class="reports"> <i class="fa-solid fa-chart-simple" style="color: #ffffff;"></i> <span> SALES </span> </button> </a>
                <a href="php/unlock-user.php"><button> <i class="fa-solid fa-user-group" style="color: #ffffff;"></i> <span> CUSTOMER</span></button></a>
                <a href="php/customer-design-setting.php"> <button><i class="fa-solid fa-gears" style="color: #ffffff;"></i> <span> DESIGN SETTING </span></button></a>
                <a href="php/customer-landing-page.php"><button class="logout"><i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i> <span> LOG OUT</span></button></a>
            </div>
        </div>
    </div>
</body>
</html>