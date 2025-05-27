<?php 
    include('config/connection.php');
    session_start();

    $st='1';
    $stmt=$conn->prepare("SELECT COUNT(*) AS id FROM logs WHERE status = ? AND branch_id = ?");
    $stmt->bind_param('ss',$st,$_SESSION['branch']);
    $stmt->execute();
    $result=$stmt->get_result();
    while($row=$result->fetch_assoc()){
        if($row['id'] != 0){
            echo 'Assistant Logs<span class="right badge badge-info" id=""><b>'.$row['id'].'</b></span>';
        }
        else{
            echo 'Assistant Logs';
        }
    }
?>