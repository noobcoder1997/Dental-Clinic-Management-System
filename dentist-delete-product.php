<?php
    session_start();
    include 'config/connection.php';
    $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));

    $stmt = $conn->prepare("DELETE FROM product WHERE product_id = ?");
    $stmt->bind_param('s', $id);
    $stmt->execute();

    echo 'Product was deleted successfuly!';
?>