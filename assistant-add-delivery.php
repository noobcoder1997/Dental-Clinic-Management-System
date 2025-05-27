<?php
    session_start();
    include 'config/connection.php';
    
    $id = $_SESSION['id'];
    $position = $_SESSION['position'];

    if(!isset($_SESSION['designation'])){
        $designation = $_SESSION['branch'];

        $product = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['product'])));
        $product_name = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['product_name'])));
        $product_description = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['product_description'])));
        $product_qty = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['product_qty'])));
        // $tool = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['tool'])));
        // $tool_description = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['tool_description'])));
        // $tool_qty = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['tool_qty'])));
        $date = htmlentities(stripslashes(mysqli_real_escape_string($conn, date('Y-m-d'))));
        // $get_qty='';
    
     
            // $stmt = $conn->prepare("SELECT * FROM delivery WHERE stock_no = ? AND branch_id = ? AND product_name = ? ");
            // $stmt->bind_param('sss',$product,$designation,$product_name);
            // $stmt->execute();
            // $result=$stmt->get_result();
            // $row = $result->fetch_assoc();
    
            // if(isset($row['delivery_id'])){
                
            //     $get_qty = $row['quantity'];
            //     $qty=intval($get_qty)+intval($product_qty);
            
    
            // }
            // else{
                $stmt = $conn->prepare("INSERT INTO delivery (stock_no, description, quantity, product_name, branch_id, date) VALUES (?,?,?,?,?,?) ");
                $stmt->bind_param('ssssss',$product,$product_description,$product_qty,$product_name,$designation,$date);
                $stmt->execute();
            // }
                $product = explode(",", $product);
                $product_name = explode(",", $product_name);
                $product_qty = explode(",", $product_qty);
    
                $inc=0;
                $inc1=0;
                $total=0;
                foreach($product as $p){
                    
                    $stmt=$conn->prepare("SELECT * FROM product WHERE product_id = ? AND product_name = ? AND branch_id = ?");
                    $stmt->bind_param("sss",$p,$product_name[$inc],$designation);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if(mysqli_num_rows($result)>0){
                        $row = $result->fetch_assoc();
                        if(isset($row['product_id'])){ 
    
                            $product_qty[$inc] += intval($row['quantity']);
    
                            $stmt=$conn->prepare("UPDATE product SET quantity  = ? WHERE product_id  = ? AND branch_id = ?");
                            $stmt->bind_param("sss",$product_qty[$inc],$row['product_id'],$designation);
                            $stmt->execute();
                            
                            $stmt=$conn->prepare("SELECT * FROM inventory WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ?");
                            $stmt->bind_param('ssss', $p,$row['product_name'],$date,$designation);
                            $stmt->execute();
                            $result=$stmt->get_result();
                            if(mysqli_num_rows($result)>0){
                                if($row=$result->fetch_assoc()){
                                    
                                    $stmt=$conn->prepare("UPDATE inventory SET remaining_qty = ? WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ?");
                                    $stmt->bind_param('sssss',$product_qty[$inc],$p,$product_name[$inc],$date,$designation);
                                    $stmt->execute();                                
                                }                            
                            }
                            else{
                                $row=$result->fetch_assoc();
                                $stmt=$conn->prepare("INSERT INTO inventory (product_id,product_name,remaining_qty,date,branch_id) VALUES (?,?,?,?,?) ");
                                $stmt->bind_param('sssss',$p,$product_name[$inc],$product_qty[$inc],$date,$designation);
                                $stmt->execute();
                            }
                        }
                    }
                    else{
                        $stmt=$conn->prepare("SELECT * FROM tools WHERE tool_id = ? AND tool_name = ? AND branch_id = ?");
                        $stmt->bind_param('sss',$p,$product_name[$inc1],$designation);
                        $stmt->execute();
                        $result=$stmt->get_result();
                        $row=$result->fetch_assoc();
                        if(isset($row['tool_id'])){
    
                            $product_qty[$inc1] += intval($row['quantity']);
                            
                            $stmt=$conn->prepare("UPDATE tools SET quantity  = ? WHERE tool_id  = ? AND branch_id = ?");
                            $stmt->bind_param("sss",$product_qty[$inc1],$row['tool_id'],$designation);
                            $stmt->execute();
    
                            $stmt=$conn->prepare("SELECT * FROM inventory WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ?");
                            $stmt->bind_param('ssss', $p,$row['product_name'],$date,$designation);
                            $stmt->execute();
                            $result=$stmt->get_result();
                            if(mysqli_num_rows($result)>0){
                                if($row=$result->fetch_assoc()){
                                    
                                    $stmt=$conn->prepare("UPDATE inventory SET remaining_qty = ? WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ?");
                                    $stmt->bind_param('sssss',$product_qty[$inc],$p,$product_name[$inc],$date,$designation);
                                    $stmt->execute();                                
                                }                            
                            }
                            else{
                                $row=$result->fetch_assoc();
                                $stmt=$conn->prepare("INSERT INTO inventory (product_id,product_name,remaining_qty,date,branch_id) VALUES (?,?,?,?,?) ");
                                $stmt->bind_param('sssss',$p,$product_name[$inc],$product_qty[$inc],$date,$designation);
                                $stmt->execute();
                            }
                        }   
                    
                    }
                    
    
                    $inc++;
                    $inc1++;
                    
                }
                
        echo "Stocks Successfully Inserted!";  
    }
    else{
        $designation = $_SESSION['designation'];

        $product = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['product'])));
        $product_name = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['product_name'])));
        $product_description = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['product_description'])));
        $product_qty = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['product_qty'])));
        // $tool = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['tool'])));
        // $tool_description = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['tool_description'])));
        // $tool_qty = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['tool_qty'])));
        $date = htmlentities(stripslashes(mysqli_real_escape_string($conn, date('Y-m-d'))));
        // $get_qty='';
    
     
            // $stmt = $conn->prepare("SELECT * FROM delivery WHERE stock_no = ? AND branch_id = ? AND product_name = ? ");
            // $stmt->bind_param('sss',$product,$designation,$product_name);
            // $stmt->execute();
            // $result=$stmt->get_result();
            // $row = $result->fetch_assoc();
    
            // if(isset($row['delivery_id'])){
                
            //     $get_qty = $row['quantity'];
            //     $qty=intval($get_qty)+intval($product_qty);
            
    
            // }
            // else{
                $stmt = $conn->prepare("INSERT INTO delivery (stock_no, description, quantity, product_name, branch_id, date) VALUES (?,?,?,?,?,?) ");
                $stmt->bind_param('ssssss',$product,$product_description,$product_qty,$product_name,$designation,$date);
                $stmt->execute();
            
                $product = explode(",", $product);
                $product_name = explode(",", $product_name);
                $product_qty = explode(",", $product_qty);
    
                $inc=0;
                $inc1=0;
                $total=0;
                foreach($product as $p){
                    
                    $stmt=$conn->prepare("SELECT * FROM product WHERE product_id = ? AND product_name = ? AND branch_id = ?");
                    $stmt->bind_param("sss",$p,$product_name[$inc],$designation);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if(mysqli_num_rows($result)>0){
                        $row = $result->fetch_assoc();
                        if(isset($row['product_id'])){ 
    
                            $product_qty[$inc] += intval($row['quantity']);
    
                            $stmt=$conn->prepare("UPDATE product SET quantity  = ? WHERE product_id  = ? AND branch_id = ?");
                            $stmt->bind_param("sss",$product_qty[$inc],$row['product_id'],$designation);
                            $stmt->execute();
                            
                            $stmt=$conn->prepare("SELECT * FROM inventory WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ?");
                            $stmt->bind_param('ssss', $p,$row['product_name'],$date,$designation);
                            $stmt->execute();
                            $result=$stmt->get_result();
                            if(mysqli_num_rows($result)>0){
                                if($row=$result->fetch_assoc()){
                                    
                                    $stmt=$conn->prepare("UPDATE inventory SET remaining_qty = ? WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ?");
                                    $stmt->bind_param('sssss',$product_qty[$inc],$p,$product_name[$inc],$date,$designation);
                                    $stmt->execute();                                
                                }                            
                            }
                            else{
                                $row=$result->fetch_assoc();
                                $stmt=$conn->prepare("INSERT INTO inventory (product_id,product_name,remaining_qty,date,branch_id) VALUES (?,?,?,?,?) ");
                                $stmt->bind_param('sssss',$p,$product_name[$inc],$product_qty[$inc],$date,$designation);
                                $stmt->execute();
                            }
                        }
                    }
                    else{
                        $stmt=$conn->prepare("SELECT * FROM tools WHERE tool_id = ? AND tool_name = ? AND branch_id = ?");
                        $stmt->bind_param('sss',$p,$product_name[$inc1],$designation);
                        $stmt->execute();
                        $result=$stmt->get_result();
                        $row=$result->fetch_assoc();
                        if(isset($row['tool_id'])){
    
                            $product_qty[$inc1] += intval($row['quantity']);
                            
                            $stmt=$conn->prepare("UPDATE tools SET quantity  = ? WHERE tool_id  = ? AND branch_id = ?");
                            $stmt->bind_param("sss",$product_qty[$inc1],$row['tool_id'],$designation);
                            $stmt->execute();
    
                            $stmt=$conn->prepare("SELECT * FROM inventory WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ?");
                            $stmt->bind_param('ssss', $p,$row['product_name'],$date,$designation);
                            $stmt->execute();
                            $result=$stmt->get_result();
                            if(mysqli_num_rows($result)>0){
                                if($row=$result->fetch_assoc()){
                                    
                                    $stmt=$conn->prepare("UPDATE inventory SET remaining_qty = ? WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ?");
                                    $stmt->bind_param('sssss',$product_qty[$inc],$p,$product_name[$inc],$date,$designation);
                                    $stmt->execute();                                
                                }                            
                            }
                            else{
                                $row=$result->fetch_assoc();
                                $stmt=$conn->prepare("INSERT INTO inventory (product_id,product_name,remaining_qty,date,branch_id) VALUES (?,?,?,?,?) ");
                                $stmt->bind_param('sssss',$p,$product_name[$inc],$product_qty[$inc],$date,$designation);
                                $stmt->execute();
                            }
                        }   
                    
                    }
                    
    
                    $inc++;
                    $inc1++;
                    
                }

        if($position == 'assistant'){ 
            
            $query = "SELECT * FROM assistant WHERE assistant_id = ?";
            $stmt=$conn->prepare($query);
            $stmt->bind_param('s',$_SESSION['id']);
            $stmt->execute();
            $result=$stmt->get_result();
            if($row=$result->fetch_assoc()){
                $date = date('Y-m-d');
                $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- added a delivery, description: $product_description on $date at".date('H:i:s A');
            }
            $status = '1';

            $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
            $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
            $stmt->execute();            
        }
                
        echo "Stocks Successfully Inserted!";  
    
    }

?>