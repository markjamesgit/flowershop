<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connection.php';

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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
        $imageOnePath = $row["image_one_path"];
        $imageTwoPath = $row["image_two_path"];
        $imageThreePath = $row["image_three_path"];
    }
} else {
    echo "0 results";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sunny Bloom</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="../css/customer-dashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="icon" type="image/png" href="../assets/logo/logo2.png" />
</head>

<body>
<header class="header">
  <a href="customer-dashboard.php?user=<?= htmlspecialchars($userName) ?>" class="container-header">
    <img class="logo" src="../img/<?= htmlspecialchars(basename($logoPath)) ?>" alt="Sunny Blooms Logo" />
    <label class="shop"><?= htmlspecialchars($shopName) ?></label>
  </a>

  <!-- Search Bar -->
  <div class="content-search">
    <input type="text" class="search-bar" placeholder="Search products..." />
    <button class="search-button">
      <i class="fa-solid fa-magnifying-glass"></i>
    </button>
  </div>

  <!-- Right Side: Cart and Profile Settings -->
  <div class="header-right">
    <!-- Cart Button -->
    <a href="cart.php?user=<?= urlencode($userName) ?>" class="cart-link">
      <button class="cart-button">
        <i class="fas fa-shopping-cart"></i>
        <?php
          $userQuery = "SELECT id FROM users WHERE name = ?";
          $stmt = mysqli_prepare($conn, $userQuery);
          mysqli_stmt_bind_param($stmt, "s", $userName);
          mysqli_stmt_execute($stmt);
          $userResult = mysqli_stmt_get_result($stmt);
          $userRow = mysqli_fetch_assoc($userResult);
          $user_id = $userRow['id'] ?? 0;

          $cartQuery = "SELECT COUNT(*) AS count FROM cart WHERE user_id = ?";
          $cartStmt = mysqli_prepare($conn, $cartQuery);
          mysqli_stmt_bind_param($cartStmt, "i", $user_id);
          mysqli_stmt_execute($cartStmt);
          $cartResult = mysqli_stmt_get_result($cartStmt);
          $cartCount = mysqli_fetch_assoc($cartResult)['count'] ?? 0;
          echo "<span class='cart-number'>$cartCount</span>";
          mysqli_stmt_close($cartStmt);
        ?>
      </button>
    </a>

    <!-- User Dropdown -->
    <nav class="nav-right">
      <div class="dropdown">
        <button class="dropbtn">Welcome, <?= htmlspecialchars($userName) ?> &#9662;</button>
        <div class="dropdown-content">
          <a href="user-profile-settings.php">Profile Settings</a>
          <a href="users-change-password.php">Password</a>
          <a href="purchases.php">My Purchases</a>
          <a href="?logout=1">Logout</a>
        </div>
      </div>
    </nav>
  </div>
</header>

  <!-- Image Slider -->
  <section class="image-slider container-imageSlider">
    <?php
      $sliderImages = [$imageOnePath, $imageTwoPath, $imageThreePath];
      foreach ($sliderImages as $index => $path):
    ?>
      <div class="mySlides fade">
        <img class="slider-image" src="../img/<?= htmlspecialchars(basename($path)) ?>" alt="Slide <?= $index + 1 ?>" />
      </div>
    <?php endforeach; ?>
    <div class="DOT" style="text-align: center">
      <span class="dot"></span>
      <span class="dot"></span>
      <span class="dot"></span>
    </div>
  </section>

  <!-- Categories Section -->
  <section class="content-categories">
    <div class="categories-title"><p>CATEGORIES</p></div>
    <div class="containers-category">
      <?php
        require 'connection.php';
        $categoryQuery = "SELECT * FROM category";
        $categoryResult = mysqli_query($conn, $categoryQuery);
        while ($row = mysqli_fetch_assoc($categoryResult)):
          $categoryName = htmlspecialchars($row['category']);
          $categoryLink = "product-category.php?category=" . urlencode($categoryName) . "&user=" . urlencode($userName);
      ?>
        <a class="category-link" href="<?= $categoryLink ?>">
          <div class="categories">
            <div class="category-title"><span><?= $categoryName ?></span></div>
          </div>
        </a>
      <?php endwhile; ?>
    </div>
  </section>

  <!-- Product Grid Section -->
  <section class="daily-discover-content" id="product">
    <div class="daily-discover-title"><h3>PACKAGE BUNDLE</h3></div>
    <div class="daily-discover-container">
      <div class="grid-items">
        <?php
          $conn = mysqli_connect("localhost:3306", "root", "", "flowershop");
          $itemsPerPage = 18;
          $page = $_GET['page'] ?? 1;
          $offset = ($page - 1) * $itemsPerPage;

          $productQuery = "SELECT * FROM product ORDER BY RAND() LIMIT $offset, $itemsPerPage";
          $productResult = mysqli_query($conn, $productQuery);

          while ($product = mysqli_fetch_assoc($productResult)):
            $productName = htmlspecialchars($product['name']);
            $productPrice = number_format($product['price'], 2);
            $productImage = htmlspecialchars($product['image']);
        ?>
          <a class="product-link" href="product-details.php?id=<?= $product['id'] ?>">
            <div class="items">
              <img src="../img/<?= $productImage ?>" alt="<?= $productName ?>" />
              <div class="discover-description"><span><?= $productName ?></span></div>
              <div class="discover-price"><p>₱<?= $productPrice ?></p></div>
              <div class="shopnow-button"><p>SHOP NOW</p></div>
            </div>
          </a>
        <?php endwhile; ?>
        <?php mysqli_free_result($productResult); ?>
      </div>
    </div>

    <!-- Pagination -->
    <div class="page">
      <?php
        $totalQuery = "SELECT COUNT(*) AS total FROM product";
        $totalResult = mysqli_query($conn, $totalQuery);
        $total = mysqli_fetch_assoc($totalResult)['total'];
        $totalPages = ceil($total / $itemsPerPage);

        for ($i = 1; $i <= $totalPages; $i++):
          $activeClass = ($i == $page) ? 'active-page' : '';
      ?>
        <a href="?page=<?= $i ?>" class="pagination-link <?= $activeClass ?>"><?= $i ?></a>
      <?php endfor; ?>
      <?php mysqli_close($conn); ?>
    </div>
  </section>

  <!-- Image Slider Script -->
  <script>
    let slideIndex = 0;
    function showSlides() {
      const slides = document.getElementsByClassName("mySlides");
      const dots = document.getElementsByClassName("dot");
      for (let slide of slides) slide.style.display = "none";
      slideIndex++;
      if (slideIndex > slides.length) slideIndex = 1;
      for (let dot of dots) dot.classList.remove("active");
      slides[slideIndex - 1].style.display = "block";
      dots[slideIndex - 1].classList.add("active");
      setTimeout(showSlides, 2000);
    }
    showSlides();
  </script>
</body>
</html>
<?php
include 'footer.php';
?>
