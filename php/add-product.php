<?php include('admin-nav.php');?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connection.php';

function getProductVariants($conn, $productId) {
    $variants = array();
    $variantResult = mysqli_query($conn, "SELECT * FROM product_variant WHERE product_id = $productId");
    while ($variant = mysqli_fetch_assoc($variantResult)) {
        $variants[] = $variant;
    }
    return $variants;
}

function getPriceRange($variants) {
    $minPrice = PHP_FLOAT_MAX;
    $maxPrice = -1;

    foreach ($variants as $variant) {
        $price = $variant['price'];
        if ($price < $minPrice) {
            $minPrice = $price;
        }
        if ($price > $maxPrice) {
            $maxPrice = $price;
        }
    }

    return $minPrice . '-' . $maxPrice;
}

function getVariantString($variants) {
    $variantString = '';

    foreach ($variants as $variant) {
        $variantString .= $variant['variant'] . ': ' . $variant['qty'] . ' ';
    }

    return $variantString;
}

// Process the form submission
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $category = $_POST["category"];
    $prices = $_POST["prices"];
    $totalQty = 0; // Initialize total quantity to 0

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

            $res = move_uploaded_file($tmpName, 'img/' . $newImageName);
            if ($res) {
                $minPrice = min($prices);
                $maxPrice = max($prices);
                $priceRange = $minPrice . '-' . $maxPrice;

                // Create a string of variants and quantities
                $variantsAndQtys = [];

                if (isset($_POST['variants'])) {
                    $variants = $_POST['variants'];
                    $qtys = $_POST['qtys'];

                    foreach ($variants as $key => $variant) {
                        $variant = mysqli_real_escape_string($conn, $variant);
                        $qty = mysqli_real_escape_string($conn, $qtys[$key]);

                        // Use the corresponding price from the array
                        $variantPrice = $prices[$key];

                        $variantsAndQtys[] = "$variant - $qty";
                        $totalQty += $qty;
                    }
                }
                // Concatenate variants and quantities with a delimiter
                $variantsAndQtysString = implode('; ', $variantsAndQtys);

                // Insert data into the product table
                $query = "INSERT INTO product (name, image, category, qty, price, variant, category_id) VALUES ('$name', '$newImageName', '$category', '$totalQty', '$priceRange', '$variantsAndQtysString', (SELECT id FROM category WHERE category = '$category'))";
                if (mysqli_query($conn, $query)) {
                    $productID = mysqli_insert_id($conn); // Get the last inserted product ID

                    // Increment product_count
                    mysqli_query($conn, "UPDATE category SET product_count = product_count + 1 WHERE id = (SELECT id FROM category WHERE category = '$category')");

                    // Insert product variants into the product_variant table
                    if (isset($_POST['variants'])) {
                        $variants = $_POST['variants'];
                        $qtys = $_POST['qtys'];

                        foreach ($variants as $key => $variant) {
                            $variant = mysqli_real_escape_string($conn, $variant);
                            $qty = mysqli_real_escape_string($conn, $qtys[$key]);

                            // Use the corresponding price from the array
                            $variantPrice = $prices[$key];

                            $variantQuery = "INSERT INTO product_variant (product_id, variant, qty, price) VALUES ('$productID', '$variant', '$qty', '$variantPrice')";
                            mysqli_query($conn, $variantQuery);
                        }
                    }
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
    <title>Product Management</title>
</head>
<body>

<div class="all">
    <!-- Add Product Section -->
    <h1 class="text1">PRODUCT MANAGEMENT</h1>
    <div class="add">       
            <h3 class="text2"> ADD PRODUCTS </h3> <br>
            <form class="" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                <label for="name">Product Name: </label>
                <input type="text" name="name" id="name" required autocomplete="name" value=""> <br> <br>
                <label for="image">Product Image: </label>
                <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png, .webp, .avif" 
                autocomplete="file" onchange="previewImage(this);" value="" required /> <br> <br>


            <label for="category">Category: </label>
            <select name="category" id="category" required>
            <?php
            $categoryQuery = mysqli_query($conn, "SELECT DISTINCT category FROM category");
            while ($categoryRow = mysqli_fetch_assoc($categoryQuery)) {
                echo "<option value='" . $categoryRow['category'] . "'>" . $categoryRow['category'] . "</option>";
            }
            ?>
            </select> <br> <br>

        <h3 class="text3">PRODUCT VARIANTS</h3>
        <table>
            <thead>
                <tr class="prodVar">
                    <th>Variant</th>
                    <th>Price</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" class="varTF" name="variants[]" required autocomplete="text"></td>
                    <td><input type="text" name="prices[]" required autocomplete="number"></td>
                    <td><input type="text" name="qtys[]" required autocomplete="number"></td>
                </tr>
            </tbody>
        </table> <br>
        <button type="button" id="addVariant" class="buttonVariant">ADD VARIANT</button>
        <button type="submit" name="submit" class="buttonProduct">ADD PRODUCT</button>
    </form>
</div> <!-- add -->

<!-- Image Product Upload -->
<div class="imageProd">
    <img src="no-image.webp" id="imagePreview" alt="Image Preview">
</div>

<!-- Product List Section -->
<div class="view">
    <h1 class="text4">PRODUCT LIST</h1>
    
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
            <th>Variants</th>
            <th>Qty</th>
            <th>Price Range</th>
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
                    $variants = getProductVariants($conn, $row['id']);
                    $qtyTotal = array_sum(array_column($variants, 'qty'));

                    // Output product row
                    echo '<tr>';
                    echo '<td>' . $i++ . '</td>';
                    echo '<td>' . $row["name"] . '</td>';
                    echo '<td> <img src="img/' . $row["image"] . '" width="200" title="' . $row['image'] . '"> </td>';
                    echo '<td>' . $row["category"] . '</td>';
                    echo '<td>' . getVariantString($variants) . '</td>';
                    echo '<td>' . $qtyTotal . '</td>';
                    echo '<td>' . getPriceRange($variants) . '</td>';
                    echo '<td>';
                    echo '<button class="editbtn" onclick="editProduct(' . $row['id'] . ');"><i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i> <span> Edit </span></button> '; // Edit button
                    echo '</td>';
                    echo '<td><input type="checkbox" name="delete[]" value="' . $row['id'] . '"></td>'; // Checkbox for deletion
                    echo '</tr>';
                }
            } else {
                // No products found
                echo '<tr><td colspan="9">No products found.</td></tr>';
            }
        } else {
            // Display all products if search is not submitted
            $rows = mysqli_query($conn, "SELECT * FROM product ORDER BY id DESC");

            if ($rows && mysqli_num_rows($rows) > 0) {
                foreach ($rows as $row) {
                    $variants = getProductVariants($conn, $row['id']);
                    $qtyTotal = array_sum(array_column($variants, 'qty'));

                    // Output product row
                    echo '<tr>';
                    echo '<td>' . $i++ . '</td>';
                    echo '<td>' . $row["name"] . '</td>';
                    echo '<td> <img src="img/' . $row["image"] . '" width="200" title="' . $row['image'] . '"> </td>';
                    echo '<td>' . $row["category"] . '</td>';
                    echo '<td>' . getVariantString($variants) . '</td>';
                    echo '<td>' . $qtyTotal . '</td>';
                    echo '<td>' . getPriceRange($variants) . '</td>';
                    echo '<td>';
                    echo '<button class="editbtn" onclick="editProduct(' . $row['id'] . ');"><i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i><span> Edit</span></button> '; // Edit button
                    echo '</td>';
                    echo '<td><input type="checkbox" name="delete[]" value="' . $row['id'] . '"></td>'; // Checkbox for deletion
                    echo '</tr>';
                }
            } else {
                // No products found
                echo '<tr><td colspan="9">No products found.</td></tr>';
            }
        }
        ?>
    </table>
    <!-- Delete button -->
    <form action="delete-multiple.php" method="post" id="deleteForm">
        <button type="submit" class="deletebtn" onclick="deleteProducts();"> Delete</button>
    </form>
    </div> <!-- view -->
</div> <!-- all div -->


<script>
    document.getElementById('addVariant').addEventListener('click', function () {
        var newRow = document.createElement('tr');
        newRow.innerHTML = `
                <td><input type="text" class="varTF" name="variants[]" required autocomplete="text"></td>
                <td><input type="text" name="prices[]" required autocomplete="number"></td>
                <td><input type="text" name="qtys[]" required autocomplete="number"></td>
            `;
        document.querySelector('table tbody').appendChild(newRow);
    });

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