<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connection.php';
include ('footer.php');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$userName = isset($_GET['user']) ? $_GET['user'] : '';

if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    $_SESSION = array();

    if (session_status() == PHP_SESSION_ACTIVE) {
        session_destroy();
    }

    header("Location: http://localhost/flowershop/php/customer-landing-page.php");
    exit;
}

// Fetch product information from the URL parameters
$productId = isset($_GET['id']) ? $_GET['id'] : 0;
$productName = isset($_GET['name']) ? $_GET['name'] : '';
$variant = isset($_GET['variant']) ? $_GET['variant'] : '';
$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : 1;

// Check if the product is already in the cart using prepared statement
$cartQuery = "SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND variant = ?";
$cartStatement = mysqli_prepare($conn, $cartQuery);
mysqli_stmt_bind_param($cartStatement, "iis", $user_id, $productId, $variant);
mysqli_stmt_execute($cartStatement);
$cartResult = mysqli_stmt_get_result($cartStatement);

if (!$cartResult) {
    die("Error in SQL query: " . mysqli_error($conn));
}

// If the product is not in the cart, add it using prepared statement
if (mysqli_num_rows($cartResult) == 0) {
    $quantity = 1; // Set quantity to 1 if the product is not in the cart
}

// Get the user_id based on the user_name
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

// Get product_image and price based on the selected variant
$productInfoQuery = "SELECT p.image, pv.price FROM product_variant pv
                    JOIN product p ON pv.product_id = p.id
                    WHERE pv.product_id = ? AND pv.variant = ?";
$productInfoStatement = mysqli_prepare($conn, $productInfoQuery);
mysqli_stmt_bind_param($productInfoStatement, "is", $productId, $variant);
mysqli_stmt_execute($productInfoStatement);
$productInfoResult = mysqli_stmt_get_result($productInfoStatement);

if (!$productInfoResult) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$productInfoRow = mysqli_fetch_assoc($productInfoResult);
$productImage = isset($productInfoRow['image']) ? $productInfoRow['image'] : '';
$price = isset($productInfoRow['price']) ? $productInfoRow['price'] : 0.0;

// Check if the product is already in the cart using prepared statement
$cartQuery = "SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND variant = ?";
$cartStatement = mysqli_prepare($conn, $cartQuery);
mysqli_stmt_bind_param($cartStatement, "iis", $user_id, $productId, $variant);
mysqli_stmt_execute($cartStatement);
$cartResult = mysqli_stmt_get_result($cartStatement);

if (!$cartResult) {
    die("Error in SQL query: " . mysqli_error($conn));
}

// If the product is not in the cart, add it using prepared statement
if (mysqli_num_rows($cartResult) == 0) {
    // Start a transaction
    mysqli_begin_transaction($conn);

    // Insert into cart
    $insertQuery = "INSERT INTO cart (user_id, product_id, product_name, variant, quantity, product_image, price) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $insertStatement = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($insertStatement, "iissssd", $user_id, $productId, $productName, $variant, $quantity, $productImage, $price);
    $insertResult = mysqli_stmt_execute($insertStatement);

    // Check for errors and commit or rollback the transaction
    if ($insertResult) {
        mysqli_commit($conn);
        echo '<script>';
        echo 'alert("Product quantity updated successfully!");';
        echo 'window.history.back();'; // This will navigate back to the previous page
        echo '</script>';
    } else {
        mysqli_rollback($conn);
        die("Error in SQL query: " . mysqli_error($conn));
    }
} else {
    // Product is already in the cart, update the quantity
    $updateQuery = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ? AND variant = ?";
    $updateStatement = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($updateStatement, "iiis", $quantity, $user_id, $productId, $variant);
    $updateResult = mysqli_stmt_execute($updateStatement);

    if ($updateResult) {
        echo '<script>';
        echo 'alert("Product quantity updated successfully!");';
        echo 'window.history.back();'; // This will navigate back to the previous page
        echo '</script>';
    } else {
        die("Error in SQL query: " . mysqli_error($conn));
    }
}

mysqli_close($conn);
?>