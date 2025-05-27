<?php
session_start();
include 'config/connection.php';

$id=$_SESSION['id'];
$position=$_SESSION['position'];

if(!isset( $_SESSION['designation'])){
    $designation = $_SESSION['branch'];
}
else{
    $designation = $_SESSION['designation'];
}

$product = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['product'])));
$product_name = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['product_name'])));
// $price = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['price'])));
// $desc = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['description'])));
// $qty = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['quantity'])));
$query="SELECT * FROM product WHERE product_id = ? AND product_name = ?";
$stmt=$conn->prepare($query);
$stmt->bind_param('ss',$product,$product_name);
$stmt->execute();
$result=$stmt->get_result();
if(mysqli_num_rows($result)>0){
   while($row=$result->fetch_assoc()){
        if($row['quantity'] < 1){
            echo '0';
        }
        else{
            echo $row['quantity'];
        }
    } 
}
else{
    $query="SELECT * FROM tools WHERE tool_id = ? AND tool_name = ?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param('ss',$product,$product_name);
    $stmt->execute();
    $result=$stmt->get_result();
    while($row=$result->fetch_assoc()){
        
        if($row['quantity'] < 1){
            echo '0';
        }
        else{
            echo $row['quantity'];
        }
    }
}

?>