<?
    include 'config/connection.php';

    try{
        $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));

        $div='Branch was successfully removed!';

        $stmt = $conn->prepare('DELETE FROM branch WHERE branch_id = ?');
        $stmt->bind_param('s',$id,);
        $stmt->execute();

        echo $div;
    }
    catch(Exception $e){
        $e->getMessage();
    }

?>