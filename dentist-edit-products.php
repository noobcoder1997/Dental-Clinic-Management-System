<?php
    session_start();
    include 'config/connection.php';

    $designation = $_SESSION['branch'];

    $name = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['name'])));
    $price = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['price'])));
    $desc = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['description'])));
    $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));

    $stmt = $conn->prepare("UPDATE product SET product_name = ?, product_price = ?, description = ? WHERE product_id = ? AND branch_id = ? ");
    $stmt->bind_param("sssss", $name,$price,$desc,$id,$designation);
    $stmt->execute();


    echo "Successfully Updated!";

?>