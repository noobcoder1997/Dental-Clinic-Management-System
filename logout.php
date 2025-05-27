<?php
    session_start();
    unset($_SESSION['user_status']);
    unset($_SESSION['select_branch']);
    unset($_SESSION['register']);
    unset($_SESSION['forgot_pass']);
    session_destroy();
    header("location: index.php");
?>