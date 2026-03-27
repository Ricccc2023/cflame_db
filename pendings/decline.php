<?php
require_once '../includes/config.php';

$id = intval($_GET['id']);

// Kunin pending order
$result = mysqli_query($conn, "SELECT * FROM pending_orders WHERE id = $id");
$data = mysqli_fetch_assoc($result);

if ($data) {

    // Escape strings
    $customer_name = mysqli_real_escape_string($conn, $data['customer_name']);
    $contact = mysqli_real_escape_string($conn, $data['contact']);
    $address = mysqli_real_escape_string($conn, $data['address']);
    $message = mysqli_real_escape_string($conn, $data['message']);

    // Handle NULL properly
    $product_id = is_null($data['product_id']) ? "NULL" : intval($data['product_id']);
    $quantity = is_null($data['quantity']) ? "NULL" : intval($data['quantity']);

    // Insert to archive
    $sql = "
    INSERT INTO declined_orders_archive
    (customer_name, contact, address, product_id, quantity, message)
    VALUES
    ('$customer_name', '$contact', '$address', $product_id, $quantity, '$message')
    ";

    mysqli_query($conn, $sql) or die(mysqli_error($conn));

    // Delete from pending
    mysqli_query($conn, "DELETE FROM pending_orders WHERE id = $id");
}

header("Location: declined_archive.php");
exit;