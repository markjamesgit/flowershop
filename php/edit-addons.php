<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connection.php';
include('admin-nav.php');

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid request'); window.location.href='add-addons.php';</script>";
    exit;
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM addons WHERE id = $id");

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Add-on not found'); window.location.href='add-addons.php';</script>";
    exit;
}

$row = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $name = $_POST["name"];
    $stocks = $_POST["stocks"];
    $price = $_POST["price"];
    $existingImage = $_POST["existing_image"];
    $imageToSave = $existingImage;

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $fileName = $_FILES["image"]["name"];
        $fileSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];
        $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($imageExtension, ['jpg', 'jpeg', 'png']) && $fileSize <= 1000000) {
            $newImageName = uniqid() . '.' . $imageExtension;
            move_uploaded_file($tmpName, '../img/' . $newImageName);

            if (file_exists('../img/' . $existingImage)) {
                unlink('../img/' . $existingImage);
            }

            $imageToSave = $newImageName;
        } else {
            echo "<script>alert('Invalid image or too large');</script>";
        }
    }

    mysqli_query($conn, "UPDATE addons SET addons='$name', stocks='$stocks', price='$price', image='$imageToSave' WHERE id=$id");
    echo "<script>alert('Add-ons updated successfully'); window.location.href='add-addons.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo/logo2.png"/>
    <title>UPDATE ADD-ONS</title>
</head>
<body>
    <h2>Edit Add-Ons</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" value="<?= $row['addons'] ?>" required><br><br>
        <input type="text" name="stocks" value="<?= $row['stocks'] ?>" required><br><br>
        <input type="text" name="price" value="<?= $row['price'] ?>" required><br><br>
        <img src="../img/<?= $row['image'] ?>" height="100"><br><br>
        <input type="hidden" name="existing_image" value="<?= $row['image'] ?>">
        <input type="file" name="image" accept=".jpg,.jpeg,.png"><br><br>
        <button type="submit" name="update">Update Add-On</button>
    </form>
</body>
</html>
