<?php
    session_start();
    include 'config/connection.php';
    $id = $_SESSION['id'];
    $position = $_SESSION['position'];

    if(isset($_SESSION['designation'])){
        $designation = $_SESSION['designation'];
    }else{
        $designation = $_SESSION['branch'];
    }

    $product = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['product'])));
    $product_name = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['product_name'])));
    $product_description = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['product_description'])));
    $product_qty = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['product_qty'])));
    // $tool = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['tool'])));
    // $tool_description = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['tool_description'])));
    // $tool_qty = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['tool_qty'])));
    $date = htmlentities(stripslashes(mysqli_real_escape_string($conn, date('Y-m-d'))));
    $get_qty='';

        $stmt = $conn->prepare("INSERT INTO damage_items (stock_no, description, quantity, product_name, branch_id, date) VALUES (?,?,?,?,?,?) ");
        $stmt->bind_param('ssssss',$product,$product_description,$product_qty,$product_name,$designation,$date);
        $stmt->execute();

        $stmt=$conn->prepare("SELECT * FROM product WHERE product_id = ? AND product_name = ? AND branch_id = ? ");
        $stmt->bind_param("sss",$product,$product_name,$designation);
        $stmt->execute();
        $result=$stmt->get_result();
        if(mysqli_num_rows($result)>0){
            $row=$result->fetch_assoc();

            $qty=(intval($row['quantity'])-intval($product_qty));
            $stmt=$conn->prepare("UPDATE product SET quantity  = ? WHERE product_id = ? AND product_name = ? AND branch_id = ?");
            $stmt->bind_param("ssss",$qty,$row['product_id'],$row['product_name'],$designation);
            $stmt->execute();

            $stmt=$conn->prepare("SELECT * FROM inventory WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ?");
            $stmt->bind_param('ssss', $row['product_id'],$row['product_name'],$date,$designation);
            $stmt->execute();
            $result0=$stmt->get_result();
            if(mysqli_num_rows($result0)>0){
                if($row0=$result0->fetch_assoc()){
                    
                    $damage_count = intval($row0['damaged'])+intval($product_qty);
                    $stmt=$conn->prepare("UPDATE inventory SET damaged = ?, remaining_qty = ? WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ? ");
                    $stmt->bind_param('ssssss',$damage_count,$qty,$row['product_id'],$row['product_name'],$date,$designation);
                    $stmt->execute();                                
                }                            
            }
            else{
                $row0=$result0->fetch_assoc();
                $stmt=$conn->prepare("INSERT INTO inventory (product_id,product_name,damaged,remaining_qty,date,branch_id) VALUES (?,?,?,?,?,?) ");
                $stmt->bind_param('ssssss',$row['product_id'],$row['product_name'],$product_qty,$qty,$date,$designation);
                $stmt->execute();
            }
        }
        else{
            $stmt=$conn->prepare("SELECT * FROM tools WHERE tool_id = ? AND tool_name = ? AND branch_id = ?");
            $stmt->bind_param("sss",$product,$product_name,$designation);
            $stmt->execute();
            $result=$stmt->get_result();
            $row=$result->fetch_assoc();

            $qty=(intval($row['quantity'])-intval($product_qty));
            $stmt=$conn->prepare("UPDATE tools SET quantity = ? WHERE tool_id = ? AND tool_name = ? AND branch_id = ?");
            $stmt->bind_param("ssss",$qty,$row['tool_id'],$row['tool_name'],$designation);
            $stmt->execute();

            $stmt=$conn->prepare("SELECT * FROM inventory WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ?");
            $stmt->bind_param('ssss', $row['tool_id'],$row['tool_name'],$date,$designation);
            $stmt->execute();
            $result0=$stmt->get_result();
            if(mysqli_num_rows($result0)>0){
                if($row0=$result0->fetch_assoc()){
                    
                    $damage_count = intval($row0['damaged'])+intval($product_qty);
                    $stmt=$conn->prepare("UPDATE inventory SET damaged = ?, remaining_qty = ? WHERE product_id = ? AND product_name = ? AND date = ? AND branch_id = ? ");
                    $stmt->bind_param('ssssss',$damage_count,$qty,$row['tool_id'],$row['tool_name'],$date,$designation);
                    $stmt->execute();                                
                }                            
            }
            else{
                $row0=$result0->fetch_assoc();
                $stmt=$conn->prepare("INSERT INTO inventory (product_id,product_name,damaged,remaining_qty,date,branch_id) VALUES (?,?,?,?,?,?) ");
                $stmt->bind_param('ssssss',$row['tool_id'],$row['tool_name'],$product_qty,$qty,$date,$designation);
                $stmt->execute();
            }
        }

    echo "Stocks Successfully Inserted!";  

    if($position == "assistant"){
        $query = "SELECT * FROM assistant WHERE assistant_id = ?";
        $stmt=$conn->prepare($query);
        $stmt->bind_param('s',$_SESSION['id']);
        $stmt->execute();
        $result=$stmt->get_result();
        if($row=$result->fetch_assoc()){
            
            $date = date('Y-m-d');
            
            $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- added a damage item, name: $product_name on $date at".date('H:i:s A');
        }
        $status = '1';
        $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
        $stmt->bind_param('sssss', $transaction, $_SESSION['designation'], $_SESSION['id'], $date, $status);
        $stmt->execute();        
    }

?>  