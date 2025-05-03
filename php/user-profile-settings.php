<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('footer.php');
require 'connection.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (isset($_SESSION['user_name'])) {
    $userName = $_SESSION['user_name'];
} else {
    // Redirect to the login page or handle accordingly
    header("Location: http://localhost/flowershop/php/customer-landing-page.php");
    exit;
}

// If you want to log out, you can add a condition to check for a logout action
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    // Clear all session variables
    session_unset();
    // Destroy the session
    session_destroy();
    // Redirect to the login page or handle accordingly
    header("Location: http://localhost/flowershop/php/customer-landing-page.php");
    exit;
}

// Retrieve user information from the database
$query = "SELECT image_path, address, first_name, middle_name, last_name, contact_number FROM users WHERE name='$userName'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    // Save the user information in session variables
    $_SESSION['image_path'] = $row['image_path'];
    $_SESSION['address'] = $row['address'];
    $_SESSION['first_name'] = $row['first_name'];
    $_SESSION['middle_name'] = $row['middle_name'];
    $_SESSION['last_name'] = $row['last_name'];
    $_SESSION['contact_number'] = $row['contact_number'];
}

// Settings for customer-design-settings
$sqlGetSettings = "SELECT * FROM design_settings WHERE id = 1";
$resultSettings = $conn->query($sqlGetSettings);

if ($resultSettings->num_rows > 0) {
    // Output data ng bawat row
    while ($row = $resultSettings->fetch_assoc()) {
        $bgColor = $row["background_color"];
        $fontColor = $row["font_color"];
        $shopName = $row["shop_name"];
        $logoPath = $row["logo_path"];
    }
} else {
    echo "0 results";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="icon" type="image/png" href="../assets/logo/logo2.png"/>
    <title>Sunny Blooms</title>
</head>

<body>

    <a href="customer-dashboard.php?user=<?php echo $userName; ?>">
        <div class="container-header">
            <img class="logo" src="img/<?php echo basename($logoPath); ?>" alt="Logo">
            <label class="shop"><?php echo $shopName; ?></label>
        </div>
    </a>

    <!-- Search Bar -->
    <div class="content-search">
        <input type="text" class="search-bar" />
        <button class="search-button">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>   

        <!-- Cart Buttons -->
        <a href="cart.php?user=<?php echo $userName; ?>">
            <button class="cart-button">
                <i class="fas fa-shopping-cart"></i>
                <?php
                require 'connection.php';

                $userQuery = "SELECT id FROM users WHERE name = ?";
                $userStatement = mysqli_prepare($conn, $userQuery);
                mysqli_stmt_bind_param($userStatement, "s", $userName);
                mysqli_stmt_execute($userStatement);
                $userResult = mysqli_stmt_get_result($userStatement);
                        
                if (!$userResult) {
                    die("Error in SQL query: " . mysqli_error($conn));
                }
                
                $userRow = mysqli_fetch_assoc($userResult);
                $user_id = isset($userRow['id']) ? $userRow['id'] : 0;

                // Fetch the cart count for the current user
                $cartCountQuery = "SELECT COUNT(*) AS count FROM cart WHERE user_id = ?";
                $cartCountStatement = mysqli_prepare($conn, $cartCountQuery);

                if ($cartCountStatement) {
                    mysqli_stmt_bind_param($cartCountStatement, "i", $user_id);
                    mysqli_stmt_execute($cartCountStatement);
                    $cartCountResult = mysqli_stmt_get_result($cartCountStatement);

                    if ($cartCountResult) {
                        $cartCountRow = mysqli_fetch_assoc($cartCountResult);
                        $cartCount = isset($cartCountRow['count']) ? $cartCountRow['count'] : "0";

                        // Display the cart number
                        echo "<span class='cart-number'>$cartCount</span>";
                    }

                    mysqli_stmt_close($cartCountStatement);  // Close the prepared statement
                }
                ?>
            </button>
        </a>

            <!-- Navigation Links with Dropdown -->
    <nav class="nav-right">
        <div class="dropdown">
            <button class="dropbtn">Welcome, <?php echo $userName; ?> &#9662;</button>
            <div class="dropdown-content">
                <a href="user-profile-settings.php">Profile Settings</a>
                <a href="users-change-password.php">Password</a>
                <a href="purchases.php">My Purchases</a>
                <a href="?logout=1">Logout</a>
            </div>
        </div>
    </nav>

        <script>
            // Add the function definition for updateProfileImage
            function updateProfileImage(newImagePath) {
            document.getElementById('profileImage').src = newImagePath;
            }
        </script>
    
            <!-- Profile Settings Form -->
<div class="settings">
        <h1>ACCOUNT SETTINGS</h1>
        <p>Manage and protect your account</p> <br>
        <form action="update-profile.php" class="forms" method="post" enctype="multipart/form-data">
        <div class="profile-con">
            <!-- New Profile Picture -->
            <div id="profilePicturePreviewContainer"></div>
            <img id="profileImage" class="profile-image" src="<?php echo isset($_SESSION['image_path']) ? $_SESSION['image_path'] . '?' . time() : ''; ?>" alt="Profile Picture">
            <label for="profile_picture" class="profile-txt">Profile Picture</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
        </div>

        <div class="forms-content">
        <!-- Display username -->
        <label for="new_username">Username:</label> <br>
        <input type="text" id="new_username" name="new_username" value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" required /> <br> <br>

        <!-- First Name -->
        <label for="first_name">First Name:</label> <br>
        <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" value="<?php echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : ''; ?>" required /> <br> <br>

        <!-- Middle Name -->
        <label for="middle_name">Middle Name:</label> <br>
        <input type="text" id="middle_name" name="middle_name" placeholder="Enter your middle name" value="<?php echo isset($_SESSION['middle_name']) ? htmlspecialchars($_SESSION['middle_name']) : ''; ?>" required /> <br> <br>

        <!-- Last Name -->
        <label for="last_name">Last Name:</label> <br>
        <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" value="<?php echo isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : ''; ?>" required /> <br> <br>

        <!-- Address --> 
        <label for="address">Address:</label> <br>
        <input type="text" id="address" name="address" placeholder="Enter your address" value="<?php echo isset($_SESSION['address']) ? htmlspecialchars($_SESSION['address']) : ''; ?>" required /> <br> <br>

        <!-- Contact Number -->
        <label for="contact_number">Contact Number:</label><br>
        <input type="text" id="contact_number" name="contact_number" pattern="[0-9]{11}" value="<?php echo $_SESSION['contact_number']; ?>"><br>

        <!-- Submit Button -->
        <button type="submit" class="save-btn">Save Changes</button>
        </div>
    </form>
    </div>

    <script>
        function validateForm() {
            var contactNumber = document.getElementById("contact_number").value;
            var pattern = /^[0-9]{11}$/;
            if (!pattern.test(contactNumber)) {
                alert("Please enter a valid 11-digit contact number.");
                return false;
            }
            return true;
        }
    </script>

</body>
</html>