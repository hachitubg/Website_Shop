<?php
// addToCart.php

// Include the database configuration file
include("config.php");

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["is_logged_in"]) || !$_SESSION["is_logged_in"]) {
    // You may want to handle this case appropriately, e.g., redirect to login page
    exit("User not logged in");
}

// Get the product ID and quantity from the AJAX request
$productId = isset($_POST['productId']) ? intval($_POST['productId']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

// Placeholder for UserID (replace with actual logic to get user ID)
$userId = $_SESSION["user_id"];

// Check if the product is already in the shopping cart
$checkQuery = "SELECT * FROM ShoppingCart WHERE UserID = $userId AND ProductID = $productId";
$checkResult = mysqli_query($conn, $checkQuery);

if ($checkResult) {
    if (mysqli_num_rows($checkResult) > 0) {
        // Product already exists in the shopping cart, update the quantity
        $updateQuery = "UPDATE ShoppingCart SET Quantity = Quantity + $quantity WHERE UserID = $userId AND ProductID = $productId";
        $updateResult = mysqli_query($conn, $updateQuery);

        if (!$updateResult) {
            die('Error updating quantity: ' . mysqli_error($conn));
        }

        echo "Product quantity updated successfully";
    } else {
        // Product doesn't exist in the shopping cart, insert a new entry
        $insertQuery = "INSERT INTO ShoppingCart (UserID, ProductID, Quantity) VALUES ($userId, $productId, $quantity)";
        $insertResult = mysqli_query($conn, $insertQuery);

        if (!$insertResult) {
            die('Error inserting product: ' . mysqli_error($conn));
        }

        echo "Product added to cart successfully";
    }
} else {
    die('Error checking product: ' . mysqli_error($conn));
}

// Close the database connection
mysqli_close($conn);
?>
