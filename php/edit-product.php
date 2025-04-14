<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('admin-nav.php');
require 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve product details using prepared statements
    $selectProductQuery = "SELECT * FROM product WHERE id = ?";
    $stmtProduct = mysqli_prepare($conn, $selectProductQuery);
    mysqli_stmt_bind_param($stmtProduct, 'i', $id);
    if (mysqli_stmt_execute($stmtProduct)) {
        $result = mysqli_stmt_get_result($stmtProduct);
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "Error fetching product details: " . mysqli_stmt_error($stmtProduct);
    }
    mysqli_stmt_close($stmtProduct);

    // Check if a product with the specified ID exists
    if ($product) {
        if (isset($_POST['submit'])) {
            // Handle the form submission
            $newName = mysqli_real_escape_string($conn, $_POST['name']);
            $newCategory = mysqli_real_escape_string($conn, $_POST['category']);
            $newPrice = mysqli_real_escape_string($conn, $_POST['prices']);
            $newQty = mysqli_real_escape_string($conn, $_POST['qtys']);

            // Check if a new image is being uploaded
            if (!empty($_FILES['image']['name'])) {
                $newImage = $_FILES['image']['name'];

                // Specify the directory where you want to save the uploaded image
                $uploadDir = '../img/';

                // Get the temporary file name
                $tempName = $_FILES['image']['tmp_name'];

                // Create a unique name for the image
                $newImageName = time() . '_' . $newImage;

                // Move the uploaded image to the destination directory
                if (move_uploaded_file($tempName, $uploadDir . $newImageName)) {
                    // Update the product details, including the new image path
                    $updateProductQuery = "UPDATE product SET name = ?, category = ?, image = ?, price = ?, qty = ? WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $updateProductQuery);
                    mysqli_stmt_bind_param($stmt, 'ssssii', $newName, $newCategory, $newImageName, $newPrice, $newQty, $id);
                    mysqli_stmt_execute($stmt);
                }
            } else {
                // No new image uploaded, update product details without changing the image
                $updateProductQuery = "UPDATE product SET name = ?, category = ?, price = ?, qty = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $updateProductQuery);
                mysqli_stmt_bind_param($stmt, 'ssssi', $newName, $newCategory, $newPrice, $newQty, $id);
                mysqli_stmt_execute($stmt);
            }

            echo "<script>alert('Product Updated Successfully'); document.location.href = 'add-product.php';</script>";
        }
    } else {
        echo '<script>alert("Product not found with ID: ' . $id . '");</script>';
    }
} else {
    echo '<script>alert("Product ID not provided");</script>';
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo/logo2.png"/>
    <title>UPDATE PACKAGE DEAL</title>
</head>
<body>
    <h1> UPDATE PACKAGE DEAL </h1>
    <div class="all">
        <form action="" method="post" name="product_form" autocomplete="off" enctype="multipart/form-data">
            <label for="name" class="label">Package Name: </label>
            <input type="text" class="input" name="name" id="name" required autocomplete="name" value="<?php echo $product['name']; ?>"> <br> <br>
            <label for="image" class="label">Package Image: </label>
            <input type="file" class="input" name="image" id="image" accept=".jpg, .jpeg, .png" autocomplete="file" value="">
            <br><br>
            <?php
            // Display the existing image if it exists
            if (!empty($product['image'])) { 
                echo '<img class="prod-image" src="../img/' . $product['image'] . '" title="' . $product['image'] . '"><br>';
            }
            ?>
            <br>

            <label for="category" class="cate" >Category: </label>
            <select name="category" id="category" required>
                <?php
                $categoryQuery = mysqli_query($conn, "SELECT DISTINCT category FROM category");
                while ($categoryRow = mysqli_fetch_assoc($categoryQuery)) {
                    $selected = ($categoryRow['category'] == $product['category']) ? "selected" : "";
                    echo "<option value='" . $categoryRow['category'] . "' $selected>" . $categoryRow['category'] . "</option>";
                }
                ?>
            </select>
            <br> <br>

            <label for="prices" class="label">Price: </label>
            <input type="text" class="input" name="prices" id="prices" value="<?php echo $product['price']; ?>" required autocomplete="number"> <br> <br>

            <label for="qtys" class="label">Quantity: </label>
            <input type="text" class="input" name="qtys" id="qtys" value="<?php echo $product['qty']; ?>" required autocomplete="number"> <br> <br>

            <button class="edit" type="submit" name="submit">UPDATE</button>
        </form> <br>
    </div>
</body>
</html>