<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connection.php';
include('admin-nav.php');

$fileUploadedSuccessfully = false; // Initialize the variable

// Check if the form is submitted to create a new pots
if (isset($_POST["submit"])) {
    $name = $_POST["name"];

    // Handle image upload for creating a new pots
    if (isset($_FILES["image"])) {
        if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $fileName = $_FILES["image"]["name"];
            $fileSize = $_FILES["image"]["size"];
            $tmpName = $_FILES["image"]["tmp_name"];
    
            $validImageExtension = ['jpg', 'jpeg', 'png'];
            $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
            if (in_array($imageExtension, $validImageExtension) && $fileSize <= 1000000) {
                $newImageName = uniqid() . '.' . $imageExtension;
                $res = move_uploaded_file($tmpName, '../img/' . $newImageName);
                if ($res) {
                    $query = "INSERT INTO pots (pots, image) VALUES('$name', '$newImageName')";
                    mysqli_query($conn, $query);
                    echo "<script>alert('Successfully Added');</script>";
                    $fileUploadedSuccessfully = true; // Set to true after successful upload
                } else {
                    echo "Failed to upload";
                }
            } else {
                echo "<script>alert('Invalid Image Extension or Image Size Is Too Large');</script>";
            }
        } else {
            echo "<script>alert('Image Upload Error');</script>";
        }
    }
}

// Edit pots
if (isset($_POST["edit"])) {
    $editpotsId = $_POST["edit_id"];
    $editpotsName = mysqli_real_escape_string($conn, $_POST["edit_name"]);

    // Handle image upload for editing a pots
    if (isset($_FILES["edit_image"])) {
        if ($_FILES["edit_image"]["error"] === UPLOAD_ERR_OK) {
            $editFileName = $_FILES["edit_image"]["name"];
            $editFileSize = $_FILES["edit_image"]["size"];
            $editTmpName = $_FILES["edit_image"]["tmp_name"];

            $validEditImageExtension = ['jpg', 'jpeg', 'png'];
            $editImageExtension = strtolower(pathinfo($editFileName, PATHINFO_EXTENSION));

            if (in_array($editImageExtension, $validEditImageExtension) && $editFileSize <= 1000000) {
                $editNewImageName = uniqid() . '.' . $editImageExtension;
                $editRes = move_uploaded_file($editTmpName, '../img/' . $editNewImageName);
                if ($editRes) {
                    $editQuery = "UPDATE pots SET pots = '$editpotsName', image = '$editNewImageName' WHERE id = $editpotsId";
                    mysqli_query($conn, $editQuery);
                    echo "<script>alert('Pots Updated Successfully');</script>";
                } else {
                    echo "Failed to upload edited image";
                }
            } else {
                echo "<script>alert('Invalid Edited Image Extension or Image Size Is Too Large');</script>";
            }
        } else {
            echo "<script>alert('Edited Image Upload Error');</script>";
        }
    } else {
        // No new image uploaded, update pots name only
        $editQuery = "UPDATE pots SET pots = '$editpotsName' WHERE id = $editpotsId";
        mysqli_query($conn, $editQuery);
        echo "<script>alert('Pots Name Updated Successfully');</script>";
    }
}

// Delete Selected Categories
if (isset($_POST["delete_selected"])) {
    if (isset($_POST["selected_categories"]) && is_array($_POST["selected_categories"])) {
        foreach ($_POST["selected_categories"] as $selectedpotsId) {
            $deleteQuery = "DELETE FROM pots WHERE id = $selectedpotsId";
            mysqli_query($conn, $deleteQuery);
        }
        echo "<script>alert('Selected pots deleted successfully');</script>";
    } else {
        echo "<script>alert('No pots selected for deletion');</script>";
    }
}


// Define a variable for the search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchQuery = "SELECT * FROM pots WHERE pots LIKE '%$searchTerm%'";
$result = mysqli_query($conn, $searchQuery);

// Display alert if no categories match the search criteria
// if (mysqli_num_rows($result) == 0) {
//     echo "<script>alert('No categories match the search criteria.');</script>";
//     echo "<script>window.onload = function() { window.history.back(); document.getElementById('search').value = ''; }</script>";
// }
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo/logo2.png"/>
    <title>POTS MANAGEMENT</title>
    <link rel="stylesheet" href="pots-management.css">
</head>
<body>
    <h1 class="text1">POTS MANAGEMENT</h1>
    <div class="all">
        <div class="add">
            <form class="" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                <label for="name">Pots Name: </label>
                <input type="text" name="name" id="name" required value="" placeholder="Enter pots name"> <br> <br>
                <label for="image">Pots Image: </label>
                <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png, .webp, .avif" 
                autocomplete="file" onchange="previewImage(this);" value=""> <br> <br>
                <button type="submit" name="submit" class="btnSubmit">Submit</button>
            </form>
        </div> <!-- add -->

        <!-- Image Product Upload -->
        <div class="imageProd">
            <img src="no-image.webp" id="imagePreview" alt="Image Preview">
        </div>

        <div class="view">
            <h1 class="text4"> POTS LIST </h1>

            <!-- Search Form -->
            <form action="" method="get">
                <label for="search" class="text5">Search Pots:</label>
                <input type="text" name="search" class="searchtxt" id="search" placeholder="Enter pots name" required />
                <button type="submit" class="btnSearch">Search</button>
            </form> <br>

            <form action="" method="POST">
                <table border="1" cellspacing="0" cellpadding="10" class="viewTable">
                    <tr class="thView">
                        <th> ID</th>
                        <th> Name</th>
                        <th>Image</th>
                        <th>Update </th>
                        <th>Delete</th>
                    </tr>
                    <?php
                        // Modify the query based on the search input
                        $searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
                        $searchQuery = "SELECT * FROM pots WHERE pots LIKE '%$searchTerm%'";
                        $searchResult = mysqli_query($conn, $searchQuery);

                        while ($row = mysqli_fetch_assoc($searchResult)) :
                    ?>

                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['pots']; ?></td>
                        <td><img src="../img/<?php echo $row['image']; ?>" alt="pots Image" height="100"></td>
                        <td>
                            <!-- Edit Form -->
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                            <label for="edit_name">Pots Name:</label>
                            <input type="text" name="edit_name" class="potstxt" value="<?php echo $row['pots']; ?>" required><br><br>
                            <label for="edit_image">Pots Image: </label>
                            <input type="file" name="edit_image" accept=".jpg, .jpeg, .png">
                            <button type="submit" name="edit" class="editbtn"> <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i> <span> Edit </span></button>
                        </form>
                        </td>
                        <td>
                            <input type="checkbox" name="selected_categories[]" value="<?php echo $row['id']; ?>">
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
                <button type="submit" name="delete_selected" class="deletebtn">Delete</button>
            </form>
        </div> <!-- view -->
    </div> <!-- all -->

    <script>
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