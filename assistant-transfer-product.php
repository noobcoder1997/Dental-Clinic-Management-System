<?php
    session_start();
    include 'config/connection.php';

    $asst_id = $_SESSION['id'];
    $position = $_SESSION['position'];

    if(!isset($_SESSION['branch'])){
        $designation = $_SESSION['designation'];        
    }
    else{
        $designation = $_SESSION['branch'];
    }

    $id = htmlentities($_POST['id']);
    $qty= htmlentities($_POST['qty']);
    $branch = htmlentities($_POST['branch']);
    $date = date('Y-m-d');
    $time = date('h:i:s A');

    $rowAsst="";
    $qry0 = mysqli_query($conn, "SELECT * FROM assistant WHERE assistant_id = '$asst_id' ");
    $row = mysqli_fetch_assoc($qry0);
    $AsstName = $row['first_name'];

    $query1 = mysqli_query($conn, "SELECT * FROM product WHERE product_id = '$id' LIMIT 1 ");
    while($row = mysqli_fetch_assoc($query1)){

        $stmt = $conn->prepare("INSERT INTO delivery (stock_no, description, quantity, product_name, branch_id, date) VALUES (?,?,?,?,?,?) ");
        $stmt->bind_param('ssssss', $id, $row['description'], $qty, $row['product_name'], $branch, $date);
        $stmt->execute();

        echo "Product successfully transferred!";

        $message = "Hi! A product/s has been successfully transferred to your branch. Product ID: $id, Product Name: $row[product_name] Quantity: $qty.";
        $showBtn=1;

        $stmt1 = $conn->prepare("INSERT INTO notifications (patient_id, message, showbtn, _from) VALUES (?, ?, ?, ?) ");
        $stmt1->bind_param('ssss',$branch, $message, $showBtn, $designation);
        $stmt1->execute();

        $qry = mysqli_query($conn, "SELECT * FROM branch WHERE branch_id = '$branch' ");
        $row1 = mysqli_fetch_assoc($qry);

        $message = "Hi! A product/s has been successfully transfered to branch ID: $row1[location]. Product ID: $id, Product Name: $row[product_name] Quantity: $qty.";

        $stmt1 = $conn->prepare("INSERT INTO notifications (patient_id, message) VALUES (?, ?) ");
        $stmt1->bind_param('ss',$designation, $message);
        $stmt1->execute();
        
        $transaction = "Assistant ID #$asst_id ($AsstName)- transfered a product/s to $row1[location] on $date at $time";
    }

    $stmt2 = $conn->prepare("UPDATE delivery SET quantity = quantity - ? WHERE stock_no = ? AND branch_id <> ? ORDER BY date ASC LIMIT 1");
    $stmt2->bind_param('iss', $qty, $id, $branch);
    $stmt2->execute();

    $stmt2 = $conn->prepare("UPDATE product SET quantity = quantity - ? WHERE product_id = ? LIMIT 1");
    $stmt2->bind_param('ss', $qty, $id);
    $stmt2->execute();

    $qty=0;

    $query2 = $conn->prepare("SELECT * FROM delivery WHERE stock_no = ? AND branch_id = ? GROUP BY stock_no ORDER BY date ASC");
    $query2->bind_param('ss', $id, $branch);
    $query2->execute();
    $result2 = $query2->get_result();
    
    while ($row2 = $result2->fetch_assoc()) {
        if($row2['quantity'] < 1){
            $qty = $row2['quantity'];

            $stmt2 = $conn->prepare("DELETE FROM delivery WHERE stock_no = ? AND branch_id = ?");
            $stmt2->bind_param('ss', $id, $branch);
            $stmt2->execute();

            $stmt3 = $conn->prepare("UPDATE product SET quantity = quantity + ? WHERE product_id = ? ORDER BY product_id ASC LIMIT 1");
            $stmt3->bind_param('is', $qty, $id);
            $stmt3->execute();
        }
    }
    mysqli_query($conn, "INSERT INTO logs (`transaction`, `branch_id`, `assistant_id` ) VALUES ('$transaction', '$designation', '$asst_id') ");
?>