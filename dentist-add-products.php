<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    $designation = $_SESSION['branch'];

    $name = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['name'])));
    $price = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['price'])));
    $desc = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['description'])));
    // $date=date('Y-m-d');

    $stmt = $conn->prepare('SELECT * FROM product WHERE product_name = ? AND branch_id = ? ');
    $stmt->bind_param("ss",$name,$designation);
    $stmt->execute();
    $result=$stmt->get_result();
    if(mysqli_num_rows($result) < 1){
        $stmt = $conn->prepare('INSERT product (product_name,product_price,description,branch_id) VALUES (?,?,?,?)');
        $stmt->bind_param("ssss",$name,$price,$desc,$designation);
        $stmt->execute();
        echo "Successfully Inserted!";
    }
    else{
        echo "Product already listed!";
    }

?>