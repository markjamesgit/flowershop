<?php include('admin-nav.php');?>
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo/logo2.png"/>
    <title>ADMIN DASHBOARD</title>
</head>
<body>
    <div class="all-containers">    
    <div class="dashboard-container">
        <?php
            $dbConnectionOrders = mysqli_connect("localhost:3306", "root", "", "finalProject");

            if (!$dbConnectionOrders) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch total sales
            $selectTotalSalesQuery = "SELECT SUM(total_amount) AS total_sales FROM finalProject.orders";
            $totalSalesResult = mysqli_query($dbConnectionOrders, $selectTotalSalesQuery);
            $totalSalesData = mysqli_fetch_assoc($totalSalesResult);
            $totalSales = isset($totalSalesData['total_sales']) ? $totalSalesData['total_sales'] : 0;
        ?>
        <div class="dashboard-item">
            <h2>Total Sales</h2>
            <p>&#8369; <?php echo $totalSales; ?></p>
        </div>

        <?php
            // Fetch total items sold
            $selectTotalItemsSoldQuery = "SELECT COUNT(id) AS total_items_sold FROM finalProject.orders";
            $totalItemsSoldResult = mysqli_query($dbConnectionOrders, $selectTotalItemsSoldQuery);
            $totalItemsSoldData = mysqli_fetch_assoc($totalItemsSoldResult);
            $totalItemsSold = isset($totalItemsSoldData['total_items_sold']) ? $totalItemsSoldData['total_items_sold'] : 0;
        ?>
        <div class="dashboard-item">
            <h2>Items Sold</h2>
            <p><?php echo $totalItemsSold; ?></p>
        </div>

        <?php
            // Fetch total orders
            $selectTotalOrdersQuery = "SELECT COUNT(id) AS total_orders FROM finalProject.orders";
            $totalOrdersResult = mysqli_query($dbConnectionOrders, $selectTotalOrdersQuery);
            $totalOrdersData = mysqli_fetch_assoc($totalOrdersResult);
            $totalOrders = isset($totalOrdersData['total_orders']) ? $totalOrdersData['total_orders'] : 0;
        ?>
        <div class="dashboard-item">
            <h2>Total Orders</h2>
            <p><?php echo $totalOrders; ?></p>
        </div>

        <?php
            // Fetch total users
            $dbConnectionUsers = mysqli_connect("localhost:3306", "root", "", "finalProject");

            if (!$dbConnectionUsers) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $selectTotalUsersQuery = "SELECT COUNT(id) AS total_users FROM finalProject.users";
            $totalUsersResult = mysqli_query($dbConnectionUsers, $selectTotalUsersQuery);
            $totalUsersData = mysqli_fetch_assoc($totalUsersResult);
            $totalUsers = isset($totalUsersData['total_users']) ? $totalUsersData['total_users'] : 0;
        ?>
        <div class="dashboard-item">
            <h2>Total Users</h2>
            <p><?php echo $totalUsers; ?></p>
        </div>
    </div>
        <?php
            // Fetch best selling items
            $selectBestSellingItemsQuery = "SELECT p.name, p.image, SUM(o.quantity) AS total_quantity_sold
                                            FROM finalProject.orders o
                                            JOIN finalProject.product p ON o.product_id = p.id
                                            GROUP BY o.product_id
                                            HAVING total_quantity_sold >= 3
                                            ORDER BY total_quantity_sold
                                            ";
            $bestSellingItemsResult = mysqli_query($dbConnectionOrders, $selectBestSellingItemsQuery);
        ?>
        <div class="container-items">
            <div class="selling-item">
            <h1>BEST SELLING ITEMS</h1>
                <?php
                echo'<div class="rows">';
                while ($row = mysqli_fetch_assoc($bestSellingItemsResult)) {
                    echo '<div class="items">';
                    echo "<img src='img/{$row['image']}' alt='{$row['name']}' style='max-width: 100px; max-height: 100px;'>";
                    echo "<div>{$row['name']}</div>";
                    echo "<div>{$row['total_quantity_sold']} sold</div>";
                    echo '</div>';
                }
                echo '</div>';
                ?>
        </div>

        <?php
            // Fetch slow selling items
            $selectSlowSellingItemsQuery = "SELECT p.name, p.image, SUM(o.quantity) AS total_quantity_sold
                                            FROM finalProject.orders o
                                            JOIN finalProject.product p ON o.product_id = p.id
                                            GROUP BY o.product_id
                                            HAVING total_quantity_sold <= 2
                                            ORDER BY total_quantity_sold
                                            ";
            $slowSellingItemsResult = mysqli_query($dbConnectionOrders, $selectSlowSellingItemsQuery);
        ?>
        <div class="selling-item">
            <h1>SLOW SELLING ITEMS</h1>
                <?php
                echo'<div class="rows">';
                while ($row = mysqli_fetch_assoc($slowSellingItemsResult)) {
                    echo '<div class="items">';
                    echo "<img src='img/{$row['image']}' alt='{$row['name']}' style='max-width: 100px; max-height: 100px;'>";
                    echo "<div>{$row['name']}</div>";
                    echo "<div>{$row['total_quantity_sold']} sold</div>";
                    echo '</div>';
                }
                echo'</div>';
                ?>
        </div>
    </div>
    </div>
</body>
</html>