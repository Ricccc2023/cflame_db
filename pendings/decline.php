<?php
require_once '../includes/config.php';
require_once '../includes/sms_textbee.php';

$id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM pending_orders WHERE id = $id");
$data = mysqli_fetch_assoc($result);

if ($data) {

    $name = $data['customer_name'];
    $contact = $data['contact'];
    $address = $data['address'];

    $customer_name = mysqli_real_escape_string($conn, $name);
    $contactEsc = mysqli_real_escape_string($conn, $contact);
    $addressEsc = mysqli_real_escape_string($conn, $address);
    $messageEsc = mysqli_real_escape_string($conn, $data['message']);

    $product_id = is_null($data['product_id']) ? "NULL" : intval($data['product_id']);
    $quantity = is_null($data['quantity']) ? "NULL" : intval($data['quantity']);

    mysqli_query($conn, "
    INSERT INTO declined_orders_archive
    (customer_name, contact, address, product_id, quantity, message)
    VALUES
    ('$customer_name', '$contactEsc', '$addressEsc', $product_id, $quantity, '$messageEsc')
    ");

    mysqli_query($conn, "DELETE FROM pending_orders WHERE id = $id");

    /* ===============================
    SEND SMS
    =============================== */

    $smsMessage = "Hi $name,

Your order has been DECLINED ❌

Address: $address

For questions, please contact us.

Thank you.";

    $sms = sms_textbee_send($contact, $smsMessage);

    $status = $sms['ok'] ? 'declined' : 'sms_failed';
}

header("Location: declined_archive.php?success=".$status);
exit;
?>