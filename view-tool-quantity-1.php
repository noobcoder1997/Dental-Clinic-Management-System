<?php
    session_start();
    include 'config/connection.php';

    $id=$_SESSION['id'];
    $position=$_SESSION['position'];
    
    if(!isset($_SESSION['designation'])){
        $designation = $_SESSION['branch'];
    }
    else{
        $designation = $_SESSION['designation'];
    }


    $product = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['product'])));
    $product_name = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['product_name'])));

    $total=0;

    $qry=mysqli_query($conn, "SELECT * FROM delivery WHERE delivery_id = '$product' AND product_name = '$product_name' "); 
    while($r=mysqli_fetch_assoc($qry)){
        $total += intval($r['quantity']);
    }
    
    echo $total; 
?>