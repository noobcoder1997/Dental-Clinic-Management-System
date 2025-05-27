<?php
    session_start();
    include 'config/connection.php';

    try{
        $id = $_SESSION['id'];
        $position = $_SESSION['position'];
        $designation = $_SESSION['designation'];

        $loc = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['loc'])));
        $gcash = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['gcash'])));
        $contact = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['contact'])));
        $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));

        $stmt = $conn->prepare('SELECT * FROM branch WHERE location = ?');
        $stmt->bind_param("s",$loc);
        $stmt->execute();
        $result=$stmt->get_result();
        if(mysqli_num_rows($result) < 1){
            $stmt = $conn->prepare("UPDATE branch SET location = ?, branch_gcash_no = ?, contact_no = ? WHERE branch_id = ? ");
            $stmt->bind_param("ssss", $loc,$gcash,$contact,$id);
            $stmt->execute();
            echo "Successfully Updated!";


            if($position == 'assistant'){
                $query = "SELECT * FROM assistant WHERE assistant_id = ?";
                $stmt=$conn->prepare($query);
                $stmt->bind_param('s',$_SESSION['id']);
                $stmt->execute();
                $result=$stmt->get_result();
                if($row=$result->fetch_assoc()){
                    $date = date('Y-m-d');
                    $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- updated a branch, branch name: $loc on $date at".date('H:i:s A');
                }
                $status = '1';
                $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
                $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
                $stmt->execute();
            }
        }
    }
    catch(Exception $e){
        echo $e->getMessage();
    }

?>