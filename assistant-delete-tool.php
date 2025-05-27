<?php
    session_start();
    try{
        $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));

        $query = "SELECT * FROM tool WHERE tool_id = ? ";
        $stmt0=$conn->prepare($query);
        $stmt0->bind_param('s',$id);
        $stmt0->execute();
        $result0=$stmt0->get_result();
        $row0=$result0->fetch_assoc();
        $name = $row0['tool_name'];


        if($position == 'assistant'){
            $query = "SELECT * FROM assistant WHERE assistant_id = ?";
            $stmt=$conn->prepare($query);
            $stmt->bind_param('s',$_SESSION['id']);
            $stmt->execute();
            $result=$stmt->get_result();
            if($row=$result->fetch_assoc()){
                $date = date('Y-m-d');
                $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- deleted a tool, tool name: $name on $date at".date('H:i:s A');
            }
            $status = '1';
            $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
            $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
            $stmt->execute();
        }
        
        $stmt = $conn->prepare("DELETE FROM tools WHERE tool_id = ?");
        $stmt->bind_param('s', $id);
        $stmt->execute();

        echo 'Tool was deleted successfuly!';
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
    include 'config/connection.php';

?>