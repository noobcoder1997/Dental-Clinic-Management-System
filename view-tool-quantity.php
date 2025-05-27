<?php
session_start();
include 'config/connection.php';

$id=$_SESSION['id'];
$position=$_SESSION['position'];

if(!isset($_SESSION['designation'])){
    $designation = $_SESSION['branch'];
}
else{
    $designation = $_SESSION['designation'];
}


$tool = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['tool'])));
// $price = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['price'])));
// $desc = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['description'])));
// $qty = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['quantity'])));
$query="SELECT * FROM tools WHERE tool_id = ?";
$stmt=$conn->prepare($query);
$stmt->bind_param('s',$tool);
$stmt->execute();
$result=$stmt->get_result();
while($row=$result->fetch_assoc()){
    $qry=mysqli_query($conn, "SELECT COUNT(*) AS tools FROM tools WHERE tool_name = '$row[tool_name]' "); 
    $r=mysqli_fetch_assoc($qry);
    echo $r['tools'];
}
?>