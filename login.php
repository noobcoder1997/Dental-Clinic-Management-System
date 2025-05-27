<?php
    session_start(); // Start the session
    include 'config/connection.php';

    $message = '<div class="alert alert-danger alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span class="glyphicon glyphicon-warning-sign"></span>&nbsp;Incorrect Username or Password!
                </div>';

    $username = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['username'])));
    $password = htmlentities(stripslashes(mysqli_real_escape_string($conn, md5(trim($_POST['password']))))); 
    $key='dcms.dcms';
    $salt='bad geniuses';
    $pepper='T';
    $password=$password.$salt.$pepper;
    $password=hash_hmac('sha256', $password, $key, false);

    // Prepare the SQL query to check the user credentials
    $stmt0 = $conn->prepare("SELECT * FROM register_patient WHERE username = ?");
    $stmt0->bind_param('s', $username); // Bind parameters
    $stmt0->execute(); // Execute query

    // Get the result
    $result0 = $stmt0->get_result();

    if($row0 = $result0->fetch_array(MYSQLI_ASSOC)){
        if($password == $row0['password']){
            $_SESSION['id'] = $row0['register_id'];
            // $_SESSION['username'] = $username;
            $_SESSION['position'] = $row0['position'];
            // $_SESSION['designation'] = $row['designation'];
            // $_SESSION['patient_full_name'] = $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'];
            $_SESSION['user_status'] = 1;

            $message = '<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Successfully Login!</div>';
        }
    }

    $stmt1 = $conn->prepare("SELECT * FROM assistant WHERE username=?");
    $stmt1->bind_param('s', $username); // Bind parameters
    $stmt1->execute(); // Execute query

    // Get the result
    $result1 = $stmt1->get_result();

    if($row1 = $result1->fetch_array(MYSQLI_ASSOC)){
        if($password == $row1['password']){
            $_SESSION['id'] = $row1['assistant_id'];
            // $_SESSION['username'] = $username;
            $_SESSION['position'] = $row1['position'];
            $_SESSION['designation'] = $row1['designation'];
            // $_SESSION['designation'] = $row['designation'];
            // $_SESSION['patient_full_name'] = $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'];
            $_SESSION['user_status'] = 1;
            $message = '<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Successfully Login!</div>';
        }
    }


    $stmt2 = $conn->prepare("SELECT * FROM dentist WHERE username = ?");
    $stmt2->bind_param('s', $username); // Bind parameters
    $stmt2->execute(); // Execute query

    // Get the result
    $result2 = $stmt2->get_result();

    if($row2 = $result2->fetch_array(MYSQLI_ASSOC)){
        if($password == $row2['password']){
            $_SESSION['id'] = $row2['dentist_id'];
            // $_SESSION['username'] = $username;
            $_SESSION['position'] = $row2['position'];
            // $_SESSION['designation'] = $row['designation'];
            // $_SESSION['patient_full_name'] = $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'];
            $_SESSION['select_branch'] = 1; //true or false
            ?>
                <input type="hidden" id="position" value="<?php echo $_SESSION['position']; ?>">
            <?php
            $message = '<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Successfully Login!</div>';
        }
    }

    echo $message;

    $conn->close(); 
?>