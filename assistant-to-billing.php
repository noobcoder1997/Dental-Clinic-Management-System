<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];

    if(isset($_SESSION['designation'])){
        $designation = $_SESSION['designation'];
    }
    else{
        $designation = $_SESSION['branch'];
    }
    $bill_id = $_POST['bill_id'];
    $patient = $_POST['patient'];
    $teeth = $_POST['tooth'];
    $appointment_id = $_POST['appointment_id'];
    $treatments = $_POST['treatments'];
    $products = $_POST['products'];
    $product_name = explode(',',$_POST['product_names']);
    $treatments_prices    = $_POST['treatments_prices'];
    $products_prices = $_POST['products_prices'];
    $treatments_qty = $_POST['treatments_qty'];
    $products_qty = $_POST['products_qty'];
    $total_payment = $_POST['total_payment'];
    $down_payment = $_POST['down_payment'];
    $remaining_balance = $_POST['remaining_balance'];
    $cash = $_POST['cash'];
    $change = $_POST['change'];
    $date = date('Y-m-d');
    $status='0';

    if($treatments!=null OR $treatments!=''){

        $index=0;
        $_treatments = explode(",", $treatments);
        $service_prices = explode(",",$treatments_prices);
        $qty = explode(",", $treatments_qty);
        $_tooth = explode(",",$teeth);

        foreach($_treatments as $service){
            $stmt0 = $conn->prepare("SELECT * FROM services WHERE service_id = ? AND branch_id = ? ");
            $stmt0->bind_param('ss', $service,$designation);
            $stmt0->execute();
            $result0=$stmt0->get_result();

            if($row0=$result0->fetch_assoc()){
                $service_prices[$index];
                $subtotal = $service_prices[$index]*$qty[$index];
                $stmt1=$conn->prepare("INSERT INTO service_transaction (branch_id, quantity, patient_id, service_id, billing_id,tooth_no,price,subtotal,date,status) VALUES (?,?,?,?,?,?,?,?,?,?) ");
                $stmt1->bind_param("ssssssssss", $designation,$qty[$index],$patient,$service, $bill_id,$_tooth[$index],$service_prices[$index],$subtotal,$date,$status);
                $stmt1->execute();
            }
            $index++;
        }
    }
    if($products!=null OR $products!=''){

        $index=0;
        $remaining_qty = 0;
        $_products = explode(',', $products);
        $qty = explode(",", $products_qty);

        foreach($_products as $product){

            $stmt0 = $conn->prepare("SELECT * FROM product WHERE product_id = ? AND product_name = ? AND branch_id = ? ");
            $stmt0->bind_param('sss', $product,$product_name[$index],$designation);
            $stmt0->execute();
            $result0=$stmt0->get_result();

            if(mysqli_num_rows($result0)>0){

                if($row0=$result0->fetch_assoc()){

                    $subtotal = $row0['product_price']*$qty[$index];
                    $stmt1=$conn->prepare("INSERT INTO product_transaction (branch_id, quantity, patient_id, product_id, billing_id,price,subtotal,date,status) VALUES (?,?,?,?,?,?,?,?,?) ");
                    $stmt1->bind_param("sssssssss", $designation,$qty[$index],$patient,$product, $bill_id,$row0['product_price'],$subtotal,$date,$status);
                    $stmt1->execute();

                    $remaining_qty = intval($row0['quantity'])-intval($qty[$index]);
                    
                    $stmt=$conn->prepare("UPDATE product SET quantity = ? WHERE product_id = ? AND product_name = ? AND branch_id = ?");
                    $stmt->bind_param('ssss',$remaining_qty,$row0['product_id'],$row0['product_name'],$designation);
                    $stmt->execute();

                    $stmt=$conn->prepare("SELECT * FROM inventory WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ?");
                    $stmt->bind_param('ssss', $row0['product_id'],$row0['product_name'],$date,$designation);
                    $stmt->execute();
                    $result1=$stmt->get_result();

                    if(mysqli_num_rows($result1)>0){

                        if($r1=$result1->fetch_assoc()){

                            $sold = intval($r1['sold'])+intval($qty[$index]);

                            $stmt=$conn->prepare("UPDATE inventory SET sold = ?, remaining_qty = ? WHERE product_id = ? AND product_name = ? AND branch_id = ? AND date = ?");
                            $stmt->bind_param('ssssss',$sold,$remaining_qty,$row0['product_id'],$row0['product_name'],$designation,$date);
                            $stmt->execute();
                        }
                    }
                    else{
                        $r1=$result1->fetch_assoc();

                        $stmt=$conn->prepare("INSERT INTO inventory (product_id,product_name,sold,remaining_qty,date,branch_id) VALUES (?,?,?,?,?,?) ");
                        $stmt->bind_param('ssssss',$row0['product_id'],$row0['product_name'],$qty[$index],$remaining_qty,$date,$designation);
                        $stmt->execute();
                    }
                }
            }
            else{
                $stmt0 = $conn->prepare("SELECT * FROM tools WHERE tool_id = ? AND tool_name = ? AND branch_id = ? ");
                $stmt0->bind_param('sss', $product,$product_name[$index],$designation);
                $stmt0->execute();
                $result0=$stmt0->get_result();

                if($row0=$result0->fetch_assoc()){
                    
                    $remaining_qty = intval($row0['quantity'])-intval($qty[$index]);
                    
                    $stmt=$conn->prepare("UPDATE tools SET quantity = ? WHERE tool_id = ? AND tool_name = ? AND branch_id = ?");
                    $stmt->bind_param('ssss',$remaining_qty,$row0['tool_id'],$row0['tool_name'],$designation);
                    $stmt->execute();

                    $stmt=$conn->prepare("SELECT * FROM inventory WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ?");
                    $stmt->bind_param('ssss', $row0['tool_id'],$row0['tool_name'],$date,$designation);
                    $stmt->execute();
                    $result1=$stmt->get_result();

                    if(mysqli_num_rows($result1)>0){

                        if($r1=$result1->fetch_assoc()){

                            $sold = intval($r1['sold'])+intval($qty[$index]);

                            $stmt=$conn->prepare("UPDATE inventory SET sold = ?, remaining_qty = ? WHERE product_id = ? AND product_name = ? AND branch_id = ? AND date = ?");
                            $stmt->bind_param('ssssss',$sold,$remaining_qty,$row0['tool_id'],$row0['tool_name'],$designation,$date);
                            $stmt->execute();
                        }
                    }
                    else{
                        $r1=$result1->fetch_assoc();

                        $stmt=$conn->prepare("INSERT INTO inventory (product_id,product_name,sold,remaining_qty,date,branch_id) VALUES (?,?,?,?,?,?) ");
                        $stmt->bind_param('ssssss',$row0['tool_id'],$row0['tool_name'],$qty[$index],$remaining_qty,$date,$designation);
                        $stmt->execute();
                    }                    
                }
            }

            $index++;
        }   
    }

    $status = '1';

    $query="UPDATE `billing` SET `patient_id`=?,`appointment_id`=?,`service_id`=?,`product_id`=?,`total_payment`=?,`down_payment`=?,`remaining_balance`=?,`cash`=?,`change`=?,`date`=?,`status`=? WHERE billing_id=? AND branch_id = ?";
    $stmt0=$conn->prepare($query);
    $stmt0->bind_param('sssssssssssss', $patient, $appointment_id, $treatments, $products, $total_payment, $down_payment, $remaining_balance, $cash, $change, $date, $status, $bill_id,$designation);
    $stmt0->execute();

    $status = '1';
    $query="INSERT INTO patient_records (patient_id, branch_id, service_id, tooth_no, product_id, fee, balance, date, status) VALUES (?,?,?,?,?,?,?,?,?)";
    $stmt1=$conn->prepare($query);
    $stmt1->bind_param("sssssssss",$patient, $designation, $treatments, $teeth, $products, $total_payment, $remaining_balance, $date, $status);
    $stmt1->execute();

    $status = 'x';
    $stmt0 = $conn->prepare("UPDATE appointment SET status = ? WHERE appointment_id = ? AND branch_id = ?");
    $stmt0->bind_param('sss', $status,$appointment_id,$designation);
    $stmt0->execute();

    # Input Sales
    mysqli_query($conn, "INSERT INTO sales (date) VALUES ($date) ");

    echo 'Billing Successfully Completed!';

    $query = "SELECT * FROM register_patient WHERE register_id = ?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param('s',$patient);
    $stmt->execute();
    $result0=$stmt->get_result();
    $row0=$result0->fetch_assoc();


    if($position == 'assistant'){
        $query = "SELECT * FROM assistant WHERE assistant_id = ?";
        $stmt=$conn->prepare($query);
        $stmt->bind_param('s',$_SESSION['id']);
        $stmt->execute();
        $result=$stmt->get_result();
        if($row=$result->fetch_assoc()){
            $date = date('Y-m-d');
            $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- Successful transaction with Patient name: $row0[first_name] $row0[last_name] on $date at".date('H:i:s A');
        }
        $status = '1';
        $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
        $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
        $stmt->execute();        
    }

?>  