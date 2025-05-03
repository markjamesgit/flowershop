<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('footer.php');
require 'connection.php';

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

//settings for customer-design-settings
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
    <link rel="icon" type="image/png" href="../assets/logo/logo2.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="customer-dashboard.css" />
    <title>PRODUCT CATEGORY</title>
</head>

<body>

    <!-- Header Content -->
    <a href="customer-dashboard.php?user=<?php echo $userName; ?>">
            <div class="container-header">
                <img class="logo" src="../img/<?php echo basename($logoPath); ?>" alt="Logo">
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

        <!-- Categories -->
        <div class="content-categories">
            <div>
                <div class="containers-category">
                    <?php
                    require 'connection.php';

                    // Check connection
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Fetch categories from the database
                    $query = "SELECT * FROM category";
                    $result = mysqli_query($conn, $query);

                    // Check if the query was successful
                    if (!$result) {
                        die("Error in SQL query: " . mysqli_error($conn));
                    }

                    // Loop through the categories and display only the title
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Create a link for each category that points to the customer dashboard
                        $categoryLink = "product-category.php?category=" . urlencode($row['category']) . "&user=" . urlencode($userName);
                        echo "<a href='$categoryLink'>";
                        echo "<div class='categories'>";
                        echo "<div class='category-title'>";
                        echo "<span>{$row['category']}</span>";
                        echo "</div>";
                        echo "</div>";
                        echo "</a>";
                    }

                    // Close the database connection
                    mysqli_close($conn);
                    ?>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="daily-discover-title">
            <label> FILTER BY</label>
            <a href="product-category.php?category=<?php echo urlencode($_GET['category']); ?>&filter=all&user=<?php echo $userName; ?>"><button name="all">ALL</button></a>
            <a href="product-category.php?category=<?php echo urlencode($_GET['category']); ?>&filter=latest&user=<?php echo $userName; ?>"><button name="latest">LATEST</button></a>
            <a href="product-category.php?category=<?php echo urlencode($_GET['category']); ?>&filter=topsale&user=<?php echo $userName; ?>"><button name="topsale">TOP SALES</button></a>
        </div>

        <!-- Daily discover content -->
        <div class="daily-discover-content" id="product">
            <!-- Items container -->
            <div class="daily-discover-container">
                <!-- Grid items -->
                <div class="grid-items">
                    <?php
                    require 'connection.php';

                    // Check connection
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Default value for itemsPerPage
                    $itemsPerPage = 6;

                    // Fetch filterType if it's set
                    $filterType = isset($_GET['filter']) ? $_GET['filter'] : 'all';

                    // Fetch products from the database based on the filter type
                    switch ($filterType) {
                        case 'all':
                            // Show all products from the selected category with pagination
                            $category = isset($_GET['category']) ? urldecode($_GET['category']) : '';
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $offset = ($page - 1) * $itemsPerPage;
                            $query = "SELECT * FROM product WHERE category = '$category' LIMIT $offset, $itemsPerPage";
                            break;

                        case 'latest':
                            // Show the latest 6 products from the selected category without pagination
                            $category = isset($_GET['category']) ? urldecode($_GET['category']) : '';
                            $query = "SELECT * FROM product WHERE category = '$category' ORDER BY id DESC LIMIT 6";
                            break;

                        case 'topsale':
                            // Show top sales based on total_amount without pagination and specific category
                            $category = isset($_GET['category']) ? urldecode($_GET['category']) : '';
                            $query = "SELECT p.* 
                                        FROM product p
                                        JOIN (
                                            SELECT product_id, SUM(quantity) AS total_sales
                                            FROM orders o
                                            JOIN product p ON o.product_id = p.id
                                            WHERE p.category = '$category'
                                            GROUP BY product_id
                                            ORDER BY total_sales DESC
                                            LIMIT 6
                                      ) o ON p.id = o.product_id";
                                break;

                        default:
                            // Default case for handling invalid filter types
                            $category = '';
                            $query = '';
                            break;
                    }

                    $result = mysqli_query($conn, $query);

                    if (!$result) {
                    die("Error in SQL query: " . mysqli_error($conn));
                    }

                    // Display products
                    while ($product = mysqli_fetch_assoc($result)) {
                        echo "<a href='product-details.php?id={$product['id']}'>";
                        echo "<div class='items'>";
                        echo "<img src='../img/{$product['image']}' alt='{$product['name']}' height='195' width='200' />";
                        echo "<div class='discover-description'>";
                        echo "<span>{$product['name']}</span>";
                        echo "</div>";
                        echo "<div class='discover-price'>";
                        echo "<p>₱{$product['price']}</p>";
                        echo "</div>";
                        echo "<div class='shopnow-button'>";
                        echo "<p>SHOP NOW</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</a>";
                    }

                    ?>
                </div>
            </div>
                <?php
                    // Pagination links for "ALL" filter
                    if ($filterType == 'all') {
                        echo "<br> <div class='page'>";
                        $totalProductsQuery = "SELECT COUNT(*) AS total FROM product WHERE category = '$category'";
                        $totalResult = mysqli_query($conn, $totalProductsQuery);
                    
                        if (!$totalResult) {
                            die("Error in SQL query: " . mysqli_error($conn));
                        }
                    
                        $totalProducts = mysqli_fetch_assoc($totalResult)['total'];
                        $totalPages = ceil($totalProducts / $itemsPerPage);
                    
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo "<a href='?filter=$filterType&category=$category&page=$i&user=$userName' class='pagination-link'>$i</a>";
                        }
                    
                        echo "</div>";
                    }

                    // Close the database connection
                    mysqli_close($conn);
                ?>
        </div>
</body>
</html>