<?php
    session_start();
    
    $_SESSION['select_branch']=true;

    if(isset($_SESSION['select_branch'])){
        header('location: select-branch.php');
    }
?>