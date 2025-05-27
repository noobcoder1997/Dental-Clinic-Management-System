<?php
    session_start();
    include 'config/connection.php';

    try{
        $id = $_SESSION['id'];
        $position = $_SESSION['position'];
        if(!isset($_SESSION['designation'])){
            $designation = $_SESSION['branch'];
        }
        else{
            $designation = $_SESSION['designation'];
        }

        $p_id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['p_id'])));
        $inv_id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['inv_id'])));
        $p_name = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['name'])));
        $p_qty = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['quantity'])));
        $old_qty = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['old_quantity'])));
        $p_desc = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['description'])));
        $reset_qty = 0;
        $new_qty = 0;

        $stmt_=$conn->prepare("SELECT * FROM inventory WHERE product_name = ? AND product_id = ? AND branch_id = ? ");
        $stmt_->bind_param('sss',$p_name,$p_id,$designation);
        $stmt_->execute();
        $result_=$stmt_->get_result();
        if($row_=$result_->fetch_assoc()){

            $stmt0=$conn->prepare("SELECT * FROM damage_items WHERE product_name = ? AND stock_no = ? AND branch_id = ? ");
            $stmt0->bind_param('sss',$p_name,$p_id,$designation);
            $stmt0->execute();
            $result0=$stmt0->get_result();
            
            if($row0=$result0->fetch_assoc()){

                $reset_qty = intval($row_['remaining_qty']) + intval($row0['quantity']); 

                $stmt=$conn->prepare("SELECT * FROM product WHERE branch_id = ? AND product_id = ? AND product_name = ? ");
                $stmt->bind_param('sss',$designation,$p_id,$p_name);
                $stmt->execute();
            
                $result=$stmt->get_result();
                if(mysqli_num_rows($result)>0){
                    if($row = $result->fetch_assoc()){
                        
                        $new_p_qty = (intval($old_qty) + intval($row['quantity'])) - intval($p_qty);
                        // $new_qty = intval($row_['remaining_qty']) + $new_qty ;
                        if($new_p_qty < $row['quantity']){
                            $stmt=$conn->prepare("UPDATE product SET quantity = ? WHERE branch_id = ? AND product_id = ? AND product_name = ?");
                            $stmt->bind_param('ssss', $new_p_qty, $designation, $p_id, $p_name);
                            $stmt->execute(); 
                        }
                    }
                }
                else{
                    $new_qty = $reset_qty - intval($p_qty);

                    $stmt=$conn->prepare("SELECT * FROM tools WHERE branch_id = ? AND tool_id = ? AND tool_name");
                    $stmt->bind_param('sss',$designation,$p_id,$p_name);
                    $stmt->execute();
            
                    $result=$stmt->get_result();
                    if($row = $result->fetch_assoc()){

                        $new_p_qty = (intval($old_qty) + intval($row['quantity'])) - intval($p_qty);
                        // $new_qty = intval($row_['remaining_qty']) + $new_qty ;
                        if($new_p_qty < $row['quantity']){
                            $stmt=$conn->prepare("UPDATE tools SET quantity = ? WHERE branch_id = ? AND tool_id = ? AND tool_name = ?");
                            $stmt->bind_param('ssss', $new_p_qty, $designation, $p_id, $p_name);
                            $stmt->execute(); 
                        }
                    }
                }
                
                $stmt=$conn->prepare("UPDATE inventory SET remaining_qty = ?, damaged = ? WHERE branch_id = ? AND product_id = ? AND product_name = ? AND date = ? ");
                $stmt->bind_param('ssssss' ,$new_p_qty ,$p_qty, $designation, $p_id, $p_name,$row0['date']);
                $stmt->execute();
            
                $stmt=$conn->prepare("UPDATE damage_items SET stock_no = ?, product_name = ?, quantity = ?, description = ? WHERE branch_id = ? AND item_id = ?");
                $stmt->bind_param('ssssss', $p_id, $p_name,$p_qty,$p_desc,$designation,$inv_id);
                $stmt->execute();

                echo 'Damaged items was updated successfully!';

                if($position == 'assistant'){

                    $query = "SELECT * FROM assistant WHERE assistant_id = ?";
                    $stmt=$conn->prepare($query);
                    $stmt->bind_param('s',$_SESSION['id']);
                    $stmt->execute();
                    $result=$stmt->get_result();
                    if($row=$result->fetch_assoc()){
                        $date = date('Y-m-d');
                        $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- updated a damage item, name: $p_name on $date at".date('H:i:s A');
                    }
                    
                    $status = '1';
                    if($position != 'dentist'){
                        $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
                        $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);                    
                    }
                }
            }
        }
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
    

?>