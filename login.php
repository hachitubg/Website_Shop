<?php
include("config.php");

// Start the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] === true) {
    // Redirect to dashboard.php if already logged in
    header("Location: dashboard.php");
    exit;
}

// Check if the user clicked the "Login" button
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $remember = isset($_POST["remember"]) ? $_POST["remember"] : false;

    $sql = "SELECT * FROM Customers WHERE Username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["Password"])) {
            // Đăng nhập thành công, lưu thông tin người dùng vào session
            $_SESSION["is_logged_in"]   = true;
            $_SESSION["user_id"]        = $row["CustomerID"];
            $_SESSION["user_name"]      = $row["Username"];
            $_SESSION["name"]           = $row["FullName"];
            $_SESSION["avatar_url"]     = $row["AvatarURL"];

            // Nếu người dùng chọn "Ghi nhớ đăng nhập", tạo cookie
            if ($remember) {
                setcookie("user_id", $row["CustomerID"], time() + (86400 * 30), "/");
            }

            // Redirect to dashboard.php
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Tên đăng nhập hoặc mật khẩu không đúng.";
        }
    } else {
        $error_message = "Tên đăng nhập hoặc mật khẩu không đúng.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Đăng Nhập</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" name="username" required>

        <label for="password">Mật khẩu:</label>
        <input type="password" name="password" required>

        <div style="display:flex">
            <input style="width: 20px;" type="checkbox" name="remember"> 
            <div>Ghi nhớ đăng nhập</div>
        </div>
        

        <button type="submit">Đăng Nhập</button>
    </form>

    <?php
    if (isset($error_message)) {
        echo "<p class='error-message'>$error_message</p>";
    }
    ?>

    <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
</div>

</body>
</html>
