<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    header("Location: http://localhost/flowershop/customer-landing-page.php");
    exit;
}

$conn = mysqli_connect("localhost:3306", "root", "", "flowershop");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//settings for customer-design-settings
$sqlGetSettings = "SELECT * FROM design_settings WHERE id = 1"; // Id 1 assumes there's only one record for design settings
$resultSettings = $conn->query($sqlGetSettings);

if ($resultSettings->num_rows > 0) {
    // Output data ng bawat row
    while ($row = $resultSettings->fetch_assoc()) {
        $bgColor = $row["background_color"];
        $fontColor = $row["font_color"];
        $shopName = $row["shop_name"];
        $logoPath = $row["logo_path"];
        $imageOnePath = $row["image_one_path"];
        $imageTwoPath = $row["image_two_path"];
        $imageThreePath = $row["image_three_path"];
        $bannerOnePath = $row["banner_one_path"];
        $bannerTwoPath = $row["banner_two_path"];
        $endorseOnePath = $row["endorse_one_path"];
        $endorseTwoPath = $row["endorse_two_path"];
        $endorseThreePath = $row["endorse_three_path"];
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
    </head>

    <body>
        <!-- Header Black -->
        <div class="header"></div>

        <!-- Header Content -->
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

        <!-- Image Slider -->
        <div class="container-imageSlider">
            <div class="mySlides fade">
                <img class="imageOne" src="img/<?php echo basename($imageOnePath); ?>" alt="Image One">
            </div>

            <div class="mySlides fade">
                <img class="imageTwo" src="img/<?php echo basename($imageTwoPath); ?>" alt="Image Two">
            </div>

            <div class="mySlides fade">
                <img class="imageThree" src="img/<?php echo basename($imageThreePath); ?>" alt="Image Three">
            </div>
        </div>

        <div class="DOT" style="text-align: center">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>

        <!-- Banner -->
        <div class="firstBanner">
            <img class="bannerOne" src="img/<?php echo basename($bannerOnePath); ?>" alt="Banner One">
        </div>

        <div class="twoBanner">
            <img class="bannerTwo" src="img/<?php echo basename($bannerTwoPath); ?>" alt="Banner Two">
        </div>

        <!-- Categories -->

        <div class="content-categories">
            <div>
                <div class="categories-title">
                    <p>CATEGORIES</p>
                </div>

                <div class="containers-category">
                    <?php
                    // Database connection
                    $dbConnection = mysqli_connect("localhost:3306", "root", "", "flowershop");

                    // Check connection
                    if (!$dbConnection) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Fetch categories from the database
                    $query = "SELECT * FROM category";
                    $result = mysqli_query($dbConnection, $query);

                    // Check if the query was successful
                    if (!$result) {
                        die("Error in SQL query: " . mysqli_error($dbConnection));
                    }

                    // Loop through the categories and display them
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Create a link for each category that points to a different file
                        $categoryLink = "product-category.php?category=" . urlencode($row['category']) . "&user=" . urlencode($userName);
                        echo "<a href='$categoryLink'>";
                        echo "<div class='categories'>";
                        echo "<div class='category-img'>";
                        $imagePath = "img/" . $row['image'];
                    
                        if (file_exists($imagePath)) {
                            echo "<img src='$imagePath' alt='{$row['category']}' />";
                        } else {
                            echo "<span style='color: red;'>Image not found</span>";
                        }
                        echo "</div>";
                        echo "<div class='category-title'>";
                        echo "<span>{$row['category']}</span>";
                        echo "</div>";
                        echo "</div>";
                        echo "</a>";
                    }
                    ?>            
                </div>
            </div>
        </div>

        <!-- TOP PRODUCT, NEW ARRIVAL -->
        <div class="content-topProduct">
            <div>
                <div id="topproductstxt">
                    <p>TOP PRODUCTS</p>
                </div>

                <div class="containers-topProduct">
                    <?php
                    // Fetch top sales products
                    $topSalesQuery = "SELECT p.* 
                                    FROM product p
                                    JOIN (
                                        SELECT product_id, SUM(quantity) AS total_sales
                                        FROM orders
                                        GROUP BY product_id
                                        ORDER BY total_sales DESC
                                        LIMIT 4
                                    ) o ON p.id = o.product_id";

                    $topSalesResult = mysqli_query($dbConnection, $topSalesQuery);

                    if ($topSalesResult) {
                        while ($product = mysqli_fetch_assoc($topSalesResult)) {
                            echo "<a href='product-details.php?id={$product['id']}'>";
                            echo "<div class='topProduct'>";
                            echo "<div class='prod-img'>";
                            echo "<img src='img/{$product['image']}' alt='{$product['name']}' />";
                            echo "</div>";
                            echo "<div class='prod-title'>";
                            echo "<span>{$product['name']}</span>";
                            echo "</div>";
                            echo "</div>";
                            echo "</a>";
                        }
                    } else {
                        echo "Error fetching top sales products: " . mysqli_error($dbConnection);
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- NEW ARRIVAL -->
        <div class="content-newArrival">
            <div >
                <div id="newarrivalstxt">
                    <p>NEW ARRIVALS</p>
                </div>

                <div class="containers-newArrival">
                    <?php
                    // Fetch new arrival products
                    $newArrivalQuery = "SELECT * FROM product ORDER BY id DESC LIMIT 3";

                    $newArrivalResult = mysqli_query($dbConnection, $newArrivalQuery);

                    if ($newArrivalResult) {
                        while ($product = mysqli_fetch_assoc($newArrivalResult)) {
                            echo "<a href='product-details.php?id={$product['id']}'>";
                            echo "<div class='newArrivalProduct'>";
                            echo "<div class='prod-img'>";
                            echo "<img src='img/{$product['image']}' alt='{$product['name']}' />";
                            echo "</div>";
                            echo "<div class='newArrival-description'>";
                            echo "<p> SHOP NOW </p>";
                            echo "<span>{$product['name']}</span>";
                            echo "</div>";
                            echo "</div>";
                            echo "</a>";
                        }
                    } else {
                        echo "Error fetching new arrival products: " . mysqli_error($dbConnection);
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Daily discover content -->
        <div class="daily-discover-content" id="product">
            <!-- Title -->
            <div class="daily-discover-title">
                <h3> DAILY DISCOVERY </h3>
            </div>

            <!-- Items container -->
            <div class="daily-discover-container">
                <!-- Grid items -->
                <div class="grid-items">
                    <?php
                    // Database connection
                    $dbConnection = mysqli_connect("localhost:3306", "root", "", "flowershop");

                    // Check connection
                    if (!$dbConnection) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Pagination settings
                    $itemsPerPage = 18;
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $offset = ($page - 1) * $itemsPerPage;

                    // Fetch products with pagination
                    $query = "SELECT * FROM product ORDER BY RAND() LIMIT $offset, $itemsPerPage";
                    $result = mysqli_query($dbConnection, $query);

                    if (!$result) {
                        die("Error in SQL query: " . mysqli_error($dbConnection));
                    }

                    // Display products
                    while ($product = mysqli_fetch_assoc($result)) {
                        echo "<a href='product-details.php?id={$product['id']}'>";
                        echo "<div class='items'>";
                        echo "<img src='img/{$product['image']}' alt='{$product['name']}' height='195' width='200' />";
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

                    // Close the main result set
                    mysqli_free_result($result);
                    ?>
                </div>
            </div>
            <?php
                // Pagination links
                echo "<br> <div class='page'>";
                $totalProductsQuery = "SELECT COUNT(*) AS total FROM product";
                $totalResult = mysqli_query($dbConnection, $totalProductsQuery);

                if (!$totalResult) {
                    die("Error in SQL query: " . mysqli_error($dbConnection));
                }

                $totalProducts = mysqli_fetch_assoc($totalResult)['total'];
                $totalPages = ceil($totalProducts / $itemsPerPage);

                for ($i = 1; $i <= $totalPages; $i++) {
                    echo "<a href='?page=$i' class='pagination-link'>" . $i . "</a>";
                }

                echo "</div>";

                // Close the database connection
                mysqli_close($dbConnection);
            ?>
        </div>

        <!-- Footer -->
        <footer class="footer" id="paa">
        <div class="footer-container">
            <!--Row Container-->
            <div class="row-container">
            <!--About-->
            <div class="footer-about">
                <h3>About Us</h3>
                <p>
                    We're buzzing with excitement to bring you a world of e-commerce wonders. 
                    With a commitment to convenience, quality, and customer satisfaction, 
                    we aim to be your go-to destination for all your online shopping needs.
                </p>
                <div class="footer-social">
                <a href=""><i class="fab fa-twitter"></i></a>
                <a href=""><i class="fab fa-facebook-f"></i></a>
                <a href=""><i class="fab fa-youtube"></i></a>
                <a href=""><i class="fab fa-instagram"></i></a>
                <a href=""><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!--Contact-->
            <div class="footer-contact">
                <h3>Get In Touch</h3>
                <p><i class="fa fa-phone-alt"></i>+012 345 67890</p>
                <p><i class="fa fa-envelope"></i>shopbee800@gmail.com</p>
                <p><i class="fa-solid fa-warehouse"></i> Bustos, Bulacan Philippines</p>
            </div>

            <!--Project-->
            <div class="footer-project">
                <h3>ShopBee Logo</h3>
                <a href=""><img src="bee.png" alt="" /></a>
                <a href=""><img src="swarm.png" alt="" /></a>
            </div>
            </div>
        </div>

        <!--Copyright-->
        <div class="copyright">
            <div class="copyright-container">
            <div class="row-items">
                <div class="copy-text">
                <p>&copy; <a href="file:///Users/maikaordonez/Documents/HTML/FINAL%20PROJECT%20(3A)/index.html">
                2023 ShopBee</a>. All Rights Reserved</p>
                </div>
                <div class="copy-menu">
                <a href="">Terms & Conditions</a>
                <a href="">Privacy Policy</a>
                <a href="https://www.facebook.com/maika.ordonez.3">Designer</a>
                </div>
            </div>
            </div>
        </div>
        </footer>

        <script>
            // Image Slider
            let slideIndex = 0;
            showSlides();

            function showSlides() {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");

            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }

            slideIndex++;

            if (slideIndex > slides.length) {
                slideIndex = 1; // Reset slideIndex to 1 for continuous loop
            }

            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }

            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
            setTimeout(showSlides, 2000); // Change slide every 2000 milliseconds (2 seconds)
            }

        </script>
    </body>
</html>