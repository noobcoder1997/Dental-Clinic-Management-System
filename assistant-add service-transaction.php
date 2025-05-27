<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];

    if(!isset($_SESSION['branch'])){
        $designation = $_SESSION['designation'];        
    }
    else{
        $designation = $_SESSION['branch'];
    }

    $services = explode(",",$_POST['service']);
    $service_prices = explode(",",$_POST['service_prices']);
    $products = explode(",",$_POST['product']);
    $qty = explode(",",$_POST['service_qty']);
    $qty1 = explode(",",$_POST['product_qty']);
    $tooth = explode(",",$_POST['tooth']);
    $bill_id = $_POST['billing_id'];
    $patient = $_POST['patient'];
    $index=0;
    $index1=0;
    $date=date('Y-m-d');
    $status="0";

    if($services!=null OR $services!=''){
        foreach($services as $service){
            $stmt0 = $conn->prepare("SELECT * FROM services WHERE service_id = ? ");
            $stmt0->bind_param('s', $service);
            $stmt0->execute();
            $result0=$stmt0->get_result();
            if($row0=$result0->fetch_assoc()){
                $service_prices[$index];
                $subtotal = $service_prices[$index]*$qty[$index];
                $stmt1=$conn->prepare("INSERT INTO service_transaction (quantity, patient_id, service_id, billing_id,tooth_no,price,subtotal,date,status) VALUES (?,?,?,?,?,?,?,?,?) ");
                $stmt1->bind_param("sssssssss", $qty[$index],$patient,$service, $bill_id,$tooth[$index],$service_prices[$index],$subtotal,$date,$status);
                $stmt1->execute();
            }
            $index++;
        }
    }
    if($products!=null OR $products!=''){
        foreach($products as $product){
            $stmt0 = $conn->prepare("SELECT * FROM product WHERE product_id = ? ");
            $stmt0->bind_param('s', $product);
            $stmt0->execute();
            $result0=$stmt0->get_result();
            if($row0=$result0->fetch_assoc()){
                $subtotal = $row0['product_price']*$qty[$index1];
                $stmt1=$conn->prepare("INSERT INTO product_transaction (quantity, patient_id, product_id, billing_id,price,subtotal,date,status) VALUES (?,?,?,?,?,?,?,?) ");
                $stmt1->bind_param("ssssssss", $qty1[$index1],$patient,$product, $bill_id,$row0['product_price'],$subtotal,$date,$status);
                $stmt1->execute();
            }
            $index1++;
        }   
    }

?>