<?php
    session_start();
    include 'config/connection.php';
    $designation = $_SESSION['branch'];
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
    }

?>