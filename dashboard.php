<?php
// dashboard.php

// Start the session
session_start();

// Include the database configuration file
include("config.php");

// Check if the user is not logged in
if (!isset($_SESSION["is_logged_in"]) || !$_SESSION["is_logged_in"]) {
    header("Location: login.php");
    exit;
}

// Placeholder user data, replace with actual function to retrieve user data from the database
$user = [
    'user_id' => $_SESSION["user_id"],
    'username' => $_SESSION["user_name"],
    'name' => $_SESSION["name"],
    'avatar_url' => $_SESSION["avatar_url"], // Replace with actual avatar URL
];

// Placeholder product data, replace with actual function to retrieve product data from the database
$products = [];
$query = "SELECT p.*, c.CategoryName, pi.ImageURL
            FROM products p
            LEFT JOIN categories c ON p.CategoryID = c.CategoryID
            LEFT JOIN productimages pi ON p.ProductID = pi.ProductID";

// Search by product name
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
    $query .= " WHERE p.ProductName LIKE '%$searchTerm%'";
}

// Filter by category
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $categoryFilter = mysqli_real_escape_string($conn, $_GET['category']);
    if (strpos($query, 'WHERE') !== false) {
        $query .= " AND p.CategoryID = '$categoryFilter'";
    } else {
        $query .= " WHERE p.CategoryID = '$categoryFilter'";
    }
}

$query .= " GROUP BY p.ProductID";

$result = mysqli_query($conn, $query);

// Fetch data only if there are results
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}

// Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_dashboard.css">
    <script src="common.js" defer></script>
    <title>Ka Long Shop</title>
</head>
<body>
    <header>
        <a href="dashboard.php">
            <img src="https://bongbantoiyeu.com/wp-content/uploads/2018/11/VOT.png" alt="Logo">
        </a>
            <h1>KA LONG SHOP</h1>
        
        <div class="user-info">
            <img src="<?php echo $user['avatar_url']; ?>" alt="Avatar" onclick="toggleLogoutDropdown()">
            <span><?php echo $user['username']; ?></span>
            <div class="logout-dropdown">
                <button onclick="logout()">Đăng xuất</button>
                <button onclick="openCartPopup()">Giỏ hàng</button>
                <button onclick="openOrders()">Lịch sử mua hàng</button>
            </div>
        </div>
    </header>

    <main>
        <div class="search-1">
            <h2>Xin chào, <?php echo $user['name']; ?>!</h2>

            <form method="get" action="">
                <label for="search">Tìm kiếm:</label>
                <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                
                <label for="category">Danh mục:</label>
                <select name="category" id="category">
                    <option value="">Tất cả</option>
                    <?php
                    // Fetch categories from the database
                    $categoriesQuery = "SELECT * FROM categories";
                    $categoriesResult = mysqli_query($conn, $categoriesQuery);

                    while ($category = mysqli_fetch_assoc($categoriesResult)) {
                        echo "<option value='{$category['CategoryID']}'";
                        if (isset($_GET['category']) && $_GET['category'] == $category['CategoryID']) {
                            echo ' selected';
                        }
                        echo ">{$category['CategoryName']}</option>";
                    }
                    ?>
                </select>

                <button type="submit">Tìm kiếm</button>
            </form>
        </div>
        

        <div class="products-container">
            <?php if ($products): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product">
                        <img src="<?php echo $product['ImageURL']; ?>" alt="<?php echo $product['ProductName']; ?>">
                        <h2><?php echo $product['ProductName']; ?></h2>
                        <p><?php echo $product['Description']; ?></p>
                        <p>Giá: <?php echo number_format($product['Price'], 0, ',', '.'); ?> VNĐ</p>
                        <p>Số lượng: <?php echo $product['Quantity']; ?></p>
                        <p>Danh mục: <?php echo $product['CategoryName']; ?></p>
                        <?php if ($product['Quantity'] == 0): ?>
                            <p class="out-of-stock">Sản phẩm đã hết hàng</p>
                        <?php else: ?>
                            <a href="product_detail.php?product_id=<?php echo $product['ProductID']; ?>">Chi tiết sản phẩm</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không có sản phẩm nào.</p>
            <?php endif; ?>
        </div>

        <?php
            // Close the database connection here after querying
            mysqli_close($conn);
        ?>
    </main>
</body>
</html>
