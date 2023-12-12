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

// Lấy ra thông tin sản phẩm dựa vào product ID được truyền sang từ URL
if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    // Thực hiện truy vấn để lấy thông tin chi tiết sản phẩm từ bảng products
    $productQuery = "SELECT * FROM products WHERE ProductId = $productId";
    $productResult = mysqli_query($conn, $productQuery);

    // Thực hiện truy vấn để lấy thông tin hình ảnh từ bảng productimages
    $imagesQuery = "SELECT * FROM productimages WHERE ProductId = $productId";
    $imagesResult = mysqli_query($conn, $imagesQuery);

} else {
    echo "Product ID not provided.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_dashboard.css">
    <script src="common.js" defer></script>
    <title>Ka Long Shop</title>
    <style>
        /* Add your custom styles here */
        .image-container {
            display: flex;
            height: 500px;
            flex-direction: column;
            align-items: center;
        }

        .image-container img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            display: none;
        }

        .image-container img.active {
            display: block;
        }

        .product-info {
            margin-left: 30px;
        }
    </style>
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

    <main style="display: flex; justify-content: center;">
        <?php
        if (isset($productResult) && mysqli_num_rows($productResult) > 0) {
            $productData = mysqli_fetch_assoc($productResult);
            ?>

            <div class="image-container">
                <?php
                if ($imagesResult && mysqli_num_rows($imagesResult) > 0) {
                    while ($imageData = mysqli_fetch_assoc($imagesResult)) {
                        echo "<img src='{$imageData['ImageURL']}' alt='Product Image'>";
                    }
                    ?>
                    <button onclick="showNextImage()">Next Image</button>
                    <?php
                    // Đưa con trỏ về đầu dữ liệu
                    mysqli_data_seek($imagesResult, 0);
                } else {
                    echo "No images found for this product.";
                }
                ?>
            </div>

            <div class="product-info">
                <h2>Tên sản phẩm: <?php echo $productData['ProductName']; ?></h2>
                <hr>
                <p>Mô tả sản phẩm: <?php echo $productData['Description']; ?></p>
                <p>Giá sản phẩm: <?php echo number_format($productData['Price'], 0, ',', '.'); ?> VNĐ</p>
                <p>Số sản phẩm còn lại trong kho: <?php echo $productData['Quantity']; ?></p>

                <!-- Thêm input để nhập số lượng sản phẩm muốn mua -->
                <label for="quantity">Số lượng:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1">

                <!-- Button thêm vào giỏ hàng vào đây -->
                <button onclick="addToCart(<?php echo $productId; ?>,<?php echo $productData['Quantity']; ?>)">Thêm vào giỏ hàng</button>

                <!--Thông báo thêm sản phẩm vào giỏ hàng thành công -->
                <p id="add-to-cart-message" style="display: none; color: green;">Đã thêm vào giỏ hàng ! </br> Click vào Avatar và click vào giỏ hàng để kiểm tra đơn hàng của bạn</p>
                
                <!--Thông báo lỗi -->
                <p id="add-to-cart-message-error" style="display: none; color: red;">Số lượng đặt hàng vượt quá số lượng hàng còn trong kho</p>
            </div>

            <script>
                var imageUrls = [
                    <?php
                    $imageUrls = [];
                    while ($imageData = mysqli_fetch_assoc($imagesResult)) {
                        $imageUrls[] = $imageData['ImageURL'];
                    }
                    echo "'" . implode("', '", $imageUrls) . "'";
                    ?>
                ];

                var currentImageIndex = 0;
                var images = document.querySelectorAll('.image-container img');

                function showNextImage() {
                    if (imageUrls.length > 0) {
                        currentImageIndex = (currentImageIndex + 1) % imageUrls.length;
                        updateImageDisplay();
                    }
                }

                function updateImageDisplay() {
                    // Hide all images
                    images.forEach(function (image) {
                        image.classList.remove('active');
                    });

                    // Show the current image
                    images[currentImageIndex].classList.add('active');
                }

                // Call updateImageDisplay to initially show the first image
                updateImageDisplay();

                function addToCart(productId, soluongtrongkho) {
                    // Retrieve the quantity from the input field
                    var quantity = document.getElementById('quantity').value;

                    // Check số lượng
                    var messageElementEror = document.getElementById('add-to-cart-message-error');
                    if (quantity > soluongtrongkho) {
                        messageElementEror.style.display = 'block';
                        // You may want to hide the message after a certain duration
                        setTimeout(function () {
                            messageElementEror.style.display = 'none';
                        }, 2000);
                        return;
                    }

                    // Perform AJAX request to addToCart.php
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'addToCart.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            console.log(xhr.responseText);

                            var messageElement = document.getElementById('add-to-cart-message');
                            messageElement.style.display = 'block';

                            // You may want to hide the message after a certain duration
                            setTimeout(function () {
                                messageElement.style.display = 'none';
                            }, 3000);
                        }
                    };

                    // Send the productId and quantity to addToCart.php
                    xhr.send('productId=' + productId + '&quantity=' + quantity);
                }
            </script>

            <?php
        } else {
            echo "Product not found.";
        }

        // Đóng kết nối database
        mysqli_close($conn);
        ?>
    </main>
</body>
</html>
