<?php
    session_start();
    include 'config/connection.php';
    
    try{
        $location = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['location'])));
        $gcash = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gcashno'])));
        $contact = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['contactno'])));

        $stmt = $conn->prepare('SELECT * FROM branch WHERE location = ? ');
        $stmt->bind_param("s",$location);
        $stmt->execute();
        $result=$stmt->get_result();
        if(mysqli_num_rows($result) < 1){
            $stmt = $conn->prepare("INSERT INTO branch (location, branch_gcash_no, contact_no) VALUES (?,?,?) ");
            $stmt->bind_param('sss',$location,$gcash,$contact);
            $stmt->execute();
        
            echo "Branch Successfully Inserted!";

            if($_SESSION['position'] == 'assistant'){
                $query = "SELECT * FROM assistant WHERE assistant_id = ?";
                $stmt=$conn->prepare($query);
                $stmt->bind_param('s',$_SESSION['id']);
                $stmt->execute();
                $result=$stmt->get_result();
                if($row=$result->fetch_assoc()){
                    $date = date('Y-m-d');
                    $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- added a branch, branch name: $location on $date at".date('H:i:s A');
                }
                $status = '1';
                $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
                $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
                $stmt->execute();
            }
        }
        else{
            echo "Branch already listed!";
        }       
    }
    catch(Exception $e){
        echo $e->getMessage();
    }

?>