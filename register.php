<?php
include("config.php");

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $avatar_url = $_POST["avatar_url"];
    $phone_number = $_POST["phone_number"];
    $email = $_POST["email"];

    // Kiểm tra xem Tên đăng nhập hoặc email đã tồn tại chưa
    $check_existing_user = "SELECT * FROM Customers WHERE Username='$username' OR Email='$email'";
    $result = $conn->query($check_existing_user);

    if ($result->num_rows > 0) {
        $error_message = "Tên đăng nhập hoặc email đã tồn tại.";
    } else {
        // Thêm dữ liệu mới vào cơ sở dữ liệu
        $sql = "INSERT INTO Customers (FullName, Username, Password, AvatarURL, PhoneNumber, Email) VALUES ('$fullname', '$username', '$password', '$avatar_url', '$phone_number', '$email')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Đăng ký thành công!";
        } else {
            $error_message = "Đã có lỗi xảy ra trong quá trình đăng ký: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Đăng Ký</h2>
    <?php
    if (!empty($error_message)) {
        echo "<p class='error-message'>$error_message</p>";
    } elseif (!empty($success_message)) {
        echo "<script>alert('$success_message'); window.location.href='login.php';</script>";
    }
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="fullname">Họ và Tên:</label>
        <input type="text" name="fullname" required>

        <label for="username">Tên đăng nhập:</label>
        <input type="text" name="username" required>

        <label for="password">Mật khẩu:</label>
        <input type="password" name="password" required>

        <label for="avatar_url">URL ảnh đại diện:</label>
        <input type="text" name="avatar_url">

        <label for="phone_number">Số điện thoại:</label>
        <input type="text" name="phone_number">

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <button type="submit">Đăng Ký</button>
    </form>

    <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
</div>

</body>
</html>

