<?php
    session_start();
    include 'config/connection.php';

    try{
        if(!isset($_SESSION['designation'])){
            $designation = $_SESSION['branch'];
        }
        else{
            $designation = $_SESSION['designation'];        
        }
        
        $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));
        $stock = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['stock'])));
        $stock_name = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['stock_name'])));
        $desc = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['description'])));
        $quantity = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['quantity'])));
        $date = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['date'])));
        $input = strtotime($date);
        $date = getDate($input);
        $day = $date['mday'];
        $month = $date['mon'];
        $year = $date['year'];
        $new_date = $year.'-'.$month.'-'.$day;
        $stmt = $conn->prepare("UPDATE delivery SET description = ?, date = ? WHERE delivery_id = ? AND branch_id = ? ");
        $stmt->bind_param('ssss',$desc,$new_date,$id,$designation);
        $stmt->execute();

        echo "Stocks Successfully Updated!";


        if($position == 'assistant'){
            $query = "SELECT * FROM assistant WHERE assistant_id = ?";
            $stmt=$conn->prepare($query);
            $stmt->bind_param('s',$_SESSION['id']);
            $stmt->execute();
            $result=$stmt->get_result();
            if($row=$result->fetch_assoc()){
                $date = date('Y-m-d');
                $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- updated a delivery, description: $desc on $date at".date('H:i:s A');
            }
            $status = '1';
            $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
            $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
            $stmt->execute();
        }
    }
    catch(Exception $e){
        echo $e->getMessage();
    }


?>