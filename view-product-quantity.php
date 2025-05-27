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
    $stmt=$conn->prepare("SELECT quantity FROM product WHERE product_id = ? AND product_name = ?");
    $stmt->bind_param('ss', $product,$product_name);
    $stmt->execute();
    $result=$stmt->get_result();
    $row=$result->fetch_assoc();
    if(mysqli_num_rows($result)>0){
        $total = $row['quantity'];
    }
    else{
        $stmt=$conn->prepare("SELECT quantity FROM tools WHERE tool_id = ? AND tool_name = ?");
        $stmt->bind_param('ss', $product,$product_name);
        $stmt->execute();
        $result=$stmt->get_result();
        $row=$result->fetch_assoc();
        $total = $row['quantity'];        
    }


    echo $total;
    // $total=0;
    // $total_=0;

    // $qry=mysqli_query($conn, "SELECT SUM(quantity) AS qty FROM delivery WHERE stock_no = '$product' AND product_name = '$product_name' "); 
    // $r=mysqli_fetch_assoc($qry);
    // $total = intval($r['qty']);
    
    // $qry=mysqli_query($conn, "SELECT SUM(quantity) AS qty FROM damage_items WHERE stock_no = '$product' AND product_name = '$product_name' "); 
    // $r=mysqli_fetch_assoc($qry);
    // $total_ = intval($r['qty']);
    
    // echo $total-$total_; 
?>