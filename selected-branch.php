<?php
    session_start();
    include 'config/connection.php';
    
    $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));

    $_SESSION['branch'] = $id;

    if(isset($_SESSION['branch'])){
        unset($_SESSION['select_branch']);
        $_SESSION['user_status'] = 1;
    }

?>