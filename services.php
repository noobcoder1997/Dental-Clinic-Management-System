<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];

    if(isset($_POST['branch'])){

        $branch = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['branch'])));

        $stmt = $conn->prepare("SELECT * FROM  services WHERE branch_id = ?");
        $stmt->bind_param('s', $branch);
        $stmt->execute();

        $result = $stmt->get_result();

        if(mysqli_num_rows($result) > 0){
            while($row=$result->fetch_array(MYSQLI_ASSOC)){
                ?>
                    <!-- <label for="">Price: </label> -->
                    <option value="<?php echo $row['service_id'] ?>"><?php echo $row['service_name'] ?></option>
                <?php
            }
        }
        else{
            ?>
                <option value=""></option>
            <?php
        }
    }
    else if(isset($_SESSION['designation'])){

        $stmt = $conn->prepare("SELECT * FROM  services WHERE branch_id = ?");
        $stmt->bind_param('s', $_SESSION['designation']);
        $stmt->execute();

        $result = $stmt->get_result();

        if(mysqli_num_rows($result) > 0){
            while($row=$result->fetch_array(MYSQLI_ASSOC)){
                ?>
                    <!-- <label for="">Price: </label> -->
                    <option value="<?php echo $row['service_id'] ?>"><?php echo $row['service_name'] ?></option>
                <?php
            }
        }
        else{
            ?>
                <option value=""></option>
            <?php
        } 
    }
    else{
        $stmt = $conn->prepare("SELECT * FROM  services WHERE branch_id = ?");
        $stmt->bind_param('s', $_SESSION['branch']);
        $stmt->execute();

        $result = $stmt->get_result();

        if(mysqli_num_rows($result) > 0){
            while($row=$result->fetch_array(MYSQLI_ASSOC)){
                ?>
                    <!-- <label for="">Price: </label> -->
                    <option value="<?php echo $row['service_id'] ?>"><?php echo $row['service_name'] ?></option>
                <?php
            }
        }
        else{
            ?>
                <option value=""></option>
            <?php
        } 
    }

?>