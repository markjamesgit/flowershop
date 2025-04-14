<?php
require 'connection.php';
include('admin-nav.php');

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $stocks = $_POST["stocks"];
    $price = $_POST["price"];

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $fileName = $_FILES["image"]["name"];
        $fileSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];
        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($imageExtension, $validImageExtension) && $fileSize <= 1000000) {
            $newImageName = uniqid() . '.' . $imageExtension;
            if (move_uploaded_file($tmpName, '../img/' . $newImageName)) {
                $query = "INSERT INTO flower (flower, image, stocks, price) VALUES('$name', '$newImageName', '$stocks', '$price')";
                mysqli_query($conn, $query);
                echo "<script>alert('Successfully Added');</script>";
            } else {
                echo "<script>alert('Failed to upload image');</script>";
            }
        } else {
            echo "<script>alert('Invalid image or too large');</script>";
        }
    }
}

if (isset($_POST["delete_selected"])) {
    if (isset($_POST["selected_categories"])) {
        foreach ($_POST["selected_categories"] as $id) {
            mysqli_query($conn, "DELETE FROM flower WHERE id = $id");
        }
        echo "<script>alert('Selected flower(s) deleted');</script>";
    }
}

$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$searchResult = mysqli_query($conn, "SELECT * FROM flower WHERE flower LIKE '%$searchTerm%'");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo/logo2.png"/>
    <title>FLOWER MANAGEMENT</title>
</head>
<body>
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

    <h1> FLOWER MANAGEMENT</h1>
    
    <form action="" method="post" enctype="multipart/form-data">
        <label for="name">Flower Name:</label>
        <input type="text" name="name" placeholder="Flower name" required><br><br>
        
        <label for="stocks">Stocks:</label>
        <input type="text" name="stocks" placeholder="Stocks" required><br><br>
        
        <label for="price">Price:</label>
        <input type="text" name="price" placeholder="Price" required><br><br>
        
        <label for="image">Image:</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png" required><br><br>
        
        <button type="submit" name="submit">Add Flower</button>
    </form>

    <h1>FLOWER LIST</h1>
    <form method="get">
        <input type="text" name="search" placeholder="Search flower..." value="<?= $searchTerm ?>">
        <button type="submit">Search</button>
    </form>

    <form method="post">
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Stocks</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
                <th>Select</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($searchResult)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['flower'] ?></td>
                <td><?= $row['stocks'] ?></td>
                <td><?= $row['price'] ?></td>
                <td><img src="../img/<?= $row['image'] ?>" height="80"></td>
                <td><a href="edit-flower.php?id=<?= $row['id'] ?>">Edit</a></td>
                <td><input type="checkbox" name="selected_categories[]" value="<?= $row['id'] ?>"></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <button type="submit" name="delete_selected">Delete</button>
    </form>
</body>
</html>