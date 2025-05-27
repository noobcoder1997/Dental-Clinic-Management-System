<?
    session_start();
    include('config/connection.php');

    echo $location = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['location'])));
    echo $gcashnumber = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gcashnumber'])));
    echo $branchno = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['branchno'])));

    $stmt = $conn->prepare('INSERT INTO `branch`(`location`, `branch_gcash_no`, `contact_no`) VALUES (?, ?, ?)');
    $stmt->bind_param('sss',$location,$gcashnumber,$branchno,);
    $stmt->execute();

    echo 'Branch added successfully!';
?>