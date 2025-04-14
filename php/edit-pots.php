<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connection.php';
include('admin-nav.php');

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid request'); window.location.href='add-pots.php';</script>";
    exit;
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM pots WHERE id = $id");

if (mysqli_num_rows($result) === 0) {
    echo "<script>alert('Pots not found'); window.location.href='add-pots.php';</script>";
    exit;
}

$row = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $stocks = $_POST['stocks'];
    $price = $_POST['price'];
    $existingImage = $_POST['existing_image'];
    $imageToSave = $existingImage;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES["image"]["name"];
        $fileSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];
        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($imageExtension, $validImageExtension) && $fileSize <= 1000000) {
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

    $updateQuery = "UPDATE pots SET pots='$name', stocks='$stocks', price='$price', image='$imageToSave' WHERE id=$id";
    mysqli_query($conn, $updateQuery);
    echo "<script>alert('Pots updated successfully'); window.location.href='add-pots.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo/logo2.png"/>
    <title>UPDATE POTS</title>
</head>
<body>
    <h2>Edit Pots</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $row['pots']; ?>" required><br><br>

        <label>Stocks:</label>
        <input type="text" name="stocks" value="<?php echo $row['stocks']; ?>" required><br><br>

        <label>Price:</label>
        <input type="text" name="price" value="<?php echo $row['price']; ?>" required><br><br>

        <label>Current Image:</label><br>
        <img src="../img/<?php echo $row['image']; ?>" height="100"><br><br>

        <input type="hidden" name="existing_image" value="<?php echo $row['image']; ?>">

        <label>Change Image (optional):</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png"><br><br>

        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
