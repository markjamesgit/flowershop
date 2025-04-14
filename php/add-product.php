<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('admin-nav.php');
require 'connection.php';

// Process the form submission
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $category = $_POST["category"];
    $price = $_POST["prices"];
    $totalQty = $_POST["qtys"];

    try {
        $fileName = $_FILES["image"]["name"];
        $fileSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];

        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));
        if (!in_array($imageExtension, $validImageExtension)) {
            echo "<script> alert('Invalid Image Extension'); </script>";
        } else if ($fileSize > 5000000) {
            echo "<script> alert('Image Size Is Too Large'); </script>";
        } else {
            $newImageName = uniqid();
            $newImageName .= '.' . $imageExtension;

            $res = move_uploaded_file($tmpName, '../img/' . $newImageName);
            if ($res) {
                // Insert product data without variants
                $query = "INSERT INTO product (name, image, category, qty, price, category_id) 
                          VALUES ('$name', '$newImageName', '$category', '$totalQty', '$price', 
                                  (SELECT id FROM category WHERE category = '$category'))";

                if (mysqli_query($conn, $query)) {
                    $productID = mysqli_insert_id($conn);

                    // Increment product_count
                    mysqli_query($conn, "UPDATE category SET product_count = product_count + 1 
                                         WHERE id = (SELECT id FROM category WHERE category = '$category')");

                    echo "<script>alert('Successfully Added'); document.location.href = 'add-product.php';</script>";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            } else {
                echo "Failed to upload";
            }
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo/logo2.png"/>
    <title>PACKAGE DEAL</title>
</head>
<body>

<div class="all">

    <div class="tab-container">
        <div class="product-tab">
            <a href="add-product.php">Product</a>
        </div>
        <div class="flower-tab">
            <a href="add-flower.php">Flower</a>
        </div>
        <div class="add-ons-tab">
            <a href="add-addons.php">Add-Ons</a>
        </div>
        <div class="pots-tab">
            <a href="add-pots.php">Pots</a>
        </div>
    </div>

    <!-- Add Product Section -->
    <h1 class="text1">PACKAGE DEAL</h1>
    <div class="add">       
            <form class="" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                <label for="name">Package Name: </label>
                <input type="text" name="name" id="name" required autocomplete="name" value=""> <br> <br>
                <label for="image">Package Image: </label>
                <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png, .webp, .avif" 
                autocomplete="file" onchange="previewImage(this);" value="" required /> <br> <br>


            <label for="category">Category: </label>
            <select name="category" required>
                <option value="" disabled selected>Select Category</option>
                <?php
                $categories = mysqli_query($conn, "SELECT category FROM category");
                while ($row = mysqli_fetch_assoc($categories)) {
                    echo "<option value='" . $row['category'] . "'>" . $row['category'] . "</option>";
                }
                ?>
            </select><br><br>

        <label for="prices">Price: </label>
        <input type="text" name="prices" id="prices" required autocomplete="number"> <br> <br>

        <label for="qtys">Quantity: </label>
        <input type="text" name="qtys" id="qtys" required autocomplete="number"> <br> <br>

        <button type="submit" name="submit" class="buttonProduct">ADD PACKAGE</button>
    </form>
</div> <!-- add -->

<!-- Image Product Upload -->
<div class="imageProd">
    <img src="no-image.webp" id="imagePreview" alt="Image Preview">
</div>

<!-- Product List Section -->
<div class="view">
    <h1 class="text4">PACKAGE DEAL LIST</h1>
    
    <!-- Search Product -->
    <form action="" method="post">
        <label for="search" class="text5">Search Product:</label>
        <input type="text" name="search" id="search" placeholder="Enter product name" required>
        <button type="submit" name="search_submit" class="btnSearch">Search</button>
    </form> <br>

    <table border="1" cellspacing="0" cellpadding="10" class="viewTable">
        <tr class="thView">
            <th>ID</th>
            <th>Name</th>
            <th>Image</th>
            <th>Category</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Action</th>
            <th>Delete</th>
        </tr>

        <?php
        $i = 1;

        // Check if search is submitted
        if (isset($_POST["search_submit"])) {
            $searchTerm = mysqli_real_escape_string($conn, $_POST["search"]);
            $searchQuery = "SELECT * FROM product WHERE name LIKE '%$searchTerm%' OR category LIKE '%$searchTerm%' ORDER BY id DESC";
            $rows = mysqli_query($conn, $searchQuery);

            // Check if $rows is not empty
            if ($rows && mysqli_num_rows($rows) > 0) {
                foreach ($rows as $row) {
                    // Output product row
                    echo '<tr>';
                    echo '<td>' . $i++ . '</td>';
                    echo '<td>' . $row["name"] . '</td>';
                    echo '<td> <img src="../img/' . $row["image"] . '" width="200" title="' . $row['image'] . '"> </td>';
                    echo '<td>' . $row["category"] . '</td>';
                    echo '<td>' . $row["price"] . '</td>';
                    echo '<td>' . $row["qty"] . '</td>';
                    echo '<td>';
                    echo '<button class="editbtn" onclick="editProduct(' . $row['id'] . ');"><i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i> <span> Edit </span></button> '; // Edit button
                    echo '</td>';
                    echo '<td><input type="checkbox" name="delete[]" value="' . $row['id'] . '"></td>';
                    echo '</tr>';
                }
            } else {
                // No products found
                echo '<tr><td colspan="8">No products found.</td></tr>';
            }
        } else {
            // Display all products if search is not submitted
            $rows = mysqli_query($conn, "SELECT * FROM product ORDER BY id DESC");

            if ($rows && mysqli_num_rows($rows) > 0) {
                foreach ($rows as $row) {
                    // Output product row
                    echo '<tr>';
                    echo '<td>' . $i++ . '</td>';
                    echo '<td>' . $row["name"] . '</td>';
                    echo '<td> <img src="../img/' . $row["image"] . '" width="200" title="' . $row['image'] . '"> </td>';
                    echo '<td>' . $row["category"] . '</td>';
                    echo '<td>' . $row["price"] . '</td>';
                    echo '<td>' . $row["qty"] . '</td>';
                    echo '<td>';
                    echo '<button class="editbtn" onclick="editProduct(' . $row['id'] . ');"><i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i><span> Edit</span></button> '; // Edit button
                    echo '</td>';
                    echo '<td><input type="checkbox" name="delete[]" value="' . $row['id'] . '"></td>';
                    echo '</tr>';
                }
            } else {
                // No products found
                echo '<tr><td colspan="8">No products found.</td></tr>';
            }
        }
        ?>
    </table>
    <!-- Delete button -->
    <form action="delete-multiple.php" method="post" id="deleteForm">
        <button type="submit" class="deletebtn" onclick="deleteProducts();"> Delete </button>
    </form>
    </div> <!-- view -->
</div> <!-- all div -->

<script>
    function editProduct(productId) {
        window.open('edit-product.php?id=' + productId, '_self');
    }

    function deleteProducts() {
        var selectedProducts = document.querySelectorAll('input[name="delete[]"]:checked');
        var selectedIds = Array.from(selectedProducts).map(function (product) {
            return product.value;
        });
        if (selectedIds.length > 0) {
            if (confirm("Are you sure you want to delete these products? Items you delete can't be restored")) {
            document.getElementById('deleteForm').action = 'delete-product.php?ids=' + selectedIds.join(',');
        } else {
            return false;
        }

        } else {
            alert("Please select at least one product to delete.");
            return false;
        }
    }

    function previewImage(input) {
    var preview = document.getElementById('imagePreview');
    var file = input.files[0];
    var reader = new FileReader();

    reader.onload = function (e) {
        preview.src = e.target.result;
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "no-image.webp";
    }
}
</script>
</body>
</html>