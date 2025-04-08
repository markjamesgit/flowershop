<?php include('admin-nav.php');?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connection.php';

$fileUploadedSuccessfully = false; // Initialize the variable

// Check if the form is submitted to create a new category
if (isset($_POST["submit"])) {
    $name = $_POST["name"];

    // Handle image upload for creating a new category
    if (isset($_FILES["image"])) {
        if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $fileName = $_FILES["image"]["name"];
            $fileSize = $_FILES["image"]["size"];
            $tmpName = $_FILES["image"]["tmp_name"];
    
            $validImageExtension = ['jpg', 'jpeg', 'png'];
            $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
            if (in_array($imageExtension, $validImageExtension) && $fileSize <= 1000000) {
                $newImageName = uniqid() . '.' . $imageExtension;
                $res = move_uploaded_file($tmpName, 'img/' . $newImageName);
                if ($res) {
                    $query = "INSERT INTO category (category, image) VALUES('$name', '$newImageName')";
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

// Edit Category
if (isset($_POST["edit"])) {
    $editCategoryId = $_POST["edit_id"];
    $editCategoryName = mysqli_real_escape_string($conn, $_POST["edit_name"]);

    // Handle image upload for editing a category
    if (isset($_FILES["edit_image"])) {
        if ($_FILES["edit_image"]["error"] === UPLOAD_ERR_OK) {
            $editFileName = $_FILES["edit_image"]["name"];
            $editFileSize = $_FILES["edit_image"]["size"];
            $editTmpName = $_FILES["edit_image"]["tmp_name"];

            $validEditImageExtension = ['jpg', 'jpeg', 'png'];
            $editImageExtension = strtolower(pathinfo($editFileName, PATHINFO_EXTENSION));

            if (in_array($editImageExtension, $validEditImageExtension) && $editFileSize <= 1000000) {
                $editNewImageName = uniqid() . '.' . $editImageExtension;
                $editRes = move_uploaded_file($editTmpName, 'img/' . $editNewImageName);
                if ($editRes) {
                    $editQuery = "UPDATE category SET category = '$editCategoryName', image = '$editNewImageName' WHERE id = $editCategoryId";
                    mysqli_query($conn, $editQuery);
                    echo "<script>alert('Category Updated Successfully');</script>";
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
        // No new image uploaded, update category name only
        $editQuery = "UPDATE category SET category = '$editCategoryName' WHERE id = $editCategoryId";
        mysqli_query($conn, $editQuery);
        echo "<script>alert('Category Name Updated Successfully');</script>";
    }
}

// Delete Selected Categories
if (isset($_POST["delete_selected"])) {
    if (isset($_POST["selected_categories"]) && is_array($_POST["selected_categories"])) {
        foreach ($_POST["selected_categories"] as $selectedCategoryId) {
            $deleteQuery = "DELETE FROM category WHERE id = $selectedCategoryId";
            mysqli_query($conn, $deleteQuery);
        }
        echo "<script>alert('Selected Categories Deleted Successfully');</script>";
    } else {
        echo "<script>alert('No categories selected for deletion');</script>";
    }
}


// Define a variable for the search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchQuery = "SELECT * FROM category WHERE category LIKE '%$searchTerm%'";
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
    <meta charset="utf-8">
    <title>CATEGORY</title>
    <style>
        body{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        .all{
            position: absolute;
            left: 290px;
            display: grid;
            grid-template-rows: auto auto;
            gap: 15px;
            width: 78.7vw;
        }

        .add{
            display: grid;
            grid-template-columns: auto auto;
            margin-top: 20px;  
            overflow: auto; 
            padding: 10px;     
            background-color: white;
            border-radius: 5px;
            width: 45vw;
            height: vh;
        }

        .view{
            background-color: white;
            height: max-content;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .text1{
            position: relative;
            top: 15px;
            left: 290px;
            margin:0;
            padding: 0;
            font-size: 30px;
            color: black;
            letter-spacing: 5px;
            width: max-content;
        }

        .text2{
            margin: 0;
            padding: 0;
            margin-top: 15px;
            margin-left: 25px;
            margin-bottom: 15px;
            font-family: Arial;
            letter-spacing: 3px;
            height: max-content;
        }

        .text4{
            margin: 10px 0 10px 20px;
            letter-spacing: 5px;
        }

        .text5{
            margin-left: 20px;
            letter-spacing: 2px;
            font-family: arial; 
            font-size: 20px;
        }

        .add label{
            padding: 0;
            margin: 0;
            margin-left: 25px;
            font-family: Arial;
            font-size: 20px;
        }

        .add input[type="text"]{
            width: 25vw;
            height: 20px;
            font-size: 14px;
            padding-left: 7px;
        }

        .add input[type="file"]{
            font-size: 16px;
        }

        .imageProd{
            position: absolute;
            left: 60%;
            bottom: 87.79%;
            background-color: white;
            width: 31.5vw;
            height: 31.9vh;
            border-radius: 5px;
        }

        .imageProd img{
            padding: 20px;
            object-fit: contain;
            width: 28.6vw;
            height: 26.5vh;
            border-radius: 20px;
        }

        .btnSearch{
            margin-left: 5px;
            margin-top: 3px;
            padding: 5px 40px 5px 40px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 3px;
        }

        .btnSearch:hover{
            background-color: #ffca00;
            color: black;
        }

        .btnSubmit{
            margin-left: 84.5%; 
            padding: 7px 50px 7px 50px;
            background-color: black;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border:none;
        }

        .btnSubmit:hover{
            background-color: #ffca00;
            color: black;
        }

        .deletebtn{
            margin-top: 10px;
            margin-left: 137vh;
            margin-bottom: 15px;
            padding: 7px 45px 7px 45px;
            background-color: black;
            color: white;
            border: none;
        }

        .deletebtn:hover{
            background-color: #ffca00;
            color: black;
        }

        .searchtxt{
            width: 35vw;
            height: 20px;
            font-size: 14px;
            padding-left: 7px;
        }

        .categorytxt{
            width: 21vw;
            height: 18px;
            font-size: 14px;
            padding-left: 7px;
        }

        .view input[type="checkbox"]{
            height: 20px;
            width: 20px;
        }

        .viewTable{
            text-align: center;
            margin-left: 20px;
            width: 76vw;
            font-size: 14px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .thView{
            background-color: black;
            color: white;
            font-family: arial;
        }

        .thView th{
            font-size: 14px;
            padding: 10px 32px 10px 32px;
            font-weight: 100;
        }

        .thView td{
            font-size: 16px;
            padding: 10px 32px 10px 32px;
        }

        table img{
            height: 140px;
            width: 140px;
            object-fit: cover;
        }

        .editbtn{
            padding: 5px 10px 5px 10px;
            background-color: black;
            border: none;
            width: max-content;
            font-size: white;
            border-radius: 5px;
            width: max-content;
            height: 25px;
        }

        .editbtn span{
            padding: 0;
            margin: 0;
            color: white;
        }

        .editbtn i{
            padding: 0;
            margin: 0;
        }

        .editbtn:hover{
            color: black;
            background-color: #ffca00;
        }
    </style>
</head>
<body>
    <h1 class="text1" >CATEGORY MANAGEMENT</h1>
    <div class="all">
        <div class="add">
            <h2 class="text2" >ADD CATEGORY</h2> <br>
            <form class="" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                <label for="name">Category Name: </label>
                <input type="text" name="name" id="name" required value="" placeholder="Enter category name"> <br> <br>
                <label for="image">Product Image: </label>
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
            <h1 class="text4"> CATEGORY LIST </h1>

            <!-- Search Form -->
            <form action="" method="get">
                <label for="search" class="text5">Search Category:</label>
                <input type="text" name="search" class="searchtxt" id="search" placeholder="Enter category name" required />
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
                        $searchQuery = "SELECT * FROM category WHERE category LIKE '%$searchTerm%'";
                        $searchResult = mysqli_query($conn, $searchQuery);

                        while ($row = mysqli_fetch_assoc($searchResult)) :
                    ?>

                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['category']; ?></td>
                        <td><img src="img/<?php echo $row['image']; ?>" alt="Category Image" height="100"></td>
                        <td>
                            <!-- Edit Form -->
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                            <label for="edit_name">Category Name:</label>
                            <input type="text" name="edit_name" class="categorytxt" value="<?php echo $row['category']; ?>" required><br><br>
                            <label for="edit_image">Category Image: </label>
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