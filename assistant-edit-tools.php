<?php
    session_start();
    include 'config/connection.php';

    try{
        $designation = $_SESSION['designation'];
        $name = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['name'])));
        $desc = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['description'])));
        $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));

        $stmt = $conn->prepare("SELECT * FROM tools WHERE tool_name = ? ");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result=$stmt->get_result();
        if(mysqli_num_rows($result) < 1){
            $stmt = $conn->prepare("UPDATE tools SET tool_name = ?, description = ? WHERE tool_id = ? AND branch_id = ?");
            $stmt->bind_param("ssss", $name,$desc,$id,$designation);
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
                    $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- updated a tool, tool name: $name on $date at".date('H:i:s A');
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