<?php
    session_start();
    include 'config/connection.php';
    $array=[];
    $dentist = htmlentities(stripcslashes(mysqli_real_escape_string($conn,$_POST['dentist'])));
    $branch = htmlentities(stripcslashes(mysqli_real_escape_string($conn,$_POST['branch'])));

    $stmt=$conn->prepare("SELECT * FROM schedules WHERE dentist_id = ? AND branch_id = ?");
    $stmt->bind_param("ss", $dentist,$branch);
    $stmt->execute();
    $result=$stmt->get_result();
    while($row=$result->fetch_assoc()){
        array_push($array, $row['schedule']);
    }

    ?>
    <input type="text" id="sched-datepicker" class="form-control" placeholder="Dentist Schedule" readonly><br>

    <script>

        $('#sched-datepicker').datepicker(
            {
                minDate: 0,
                beforeShowDay: function(date){
                    var day = date.getDay();
                    var string = jQuery.datepicker.formatDate("yy-mm-dd",date);
                    return [(sched_array.indexOf(string) != -1)?(day>0):"", "calendar-background"];
                },
                dateFormat: "MM dd, yy",
            }
        );
        function beforeShowDay(sunday){
            var day = sunday.getDay();
            return [(day>0), "calendar-background"];  
        }
        // var unavailableDates = ["12-12-2024", "12-10-2024", "12-15-2024"];

        var sched_array = <?php echo '["' . implode('", "', $array) . '"]' ?>

</script>