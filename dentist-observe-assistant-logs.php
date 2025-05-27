<?php
    session_start();
    require('config/connection.php');

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    $branch = $_SESSION['branch'];
?>
<?php
    $status = '0';

    $stmt=$conn->prepare("UPDATE logs SET status = ? WHERE branch_id = ?");
    $stmt->bind_param('ss',$status,$branch);
    $stmt->execute();

?>
<div class="container-fluid">
    <?php
        if($position == 'dentist'){
        ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="">Assistant Logs</h4>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <?php
                                        $query = "SELECT * FROM logs WHERE branch_id = ? ORDER BY logs_id DESC";
                                        $stmt=$conn->prepare($query);
                                        $stmt->bind_param("s",$_SESSION['branch']);
                                        $stmt->execute();
                                        $result=$stmt->get_result();
                                        while($row=$result->fetch_assoc()){
                                            $str = strtotime($row['date']);
                                            $date = getDate($str);
                                            $month = $date['month'];
                                            $day = $date['mday'];
                                            $year = $date['year'];
                                            $new_date = $month." ".$day.", ".$year;
                                    ?>
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row float-right">
                                                    <a class=""><?php echo $new_date; ?></a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <?php  echo $row['transaction']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="card-footer"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    ?>
</div>
<script>
     $(function(){
        // $('#datepicker').datepicker();
        month_Array = ["January","February","March","April","May","June","July","August","September","October","November","December",""];
        month_index='';
        var dtToday = new Date();
        var month = dtToday.getMonth()+1 ;
        var day = dtToday.getDate();
        var mDay = dtToday.getDay();
        var year = dtToday.getFullYear();
        var weekday = dtToday.getDay();
        // for(x = 0; x < month_Array.lenght; x++){
        //     if(x == month){
        //         month_index = month_Array[x];
        //     }
        // }
        switch(month){
            case 1: 
                    month_index = month_Array[0];
                    break;
            case 2: 
                    month_index = month_Array[1];
                    break;
            case 3: 
                    month_index = month_Array[2];
                    break;
            case 4: 
                    month_index = month_Array[3];
                    break;
            case 5: 
                    month_index = month_Array[4];
                    break;
            case 6: 
                    month_index = month_Array[5];
                    break;
            case 7: 
                    month_index = month_Array[6];
                    break;
            case 8: 
                    month_index = month_Array[7];
                    break;
            case 9: 
                    month_index = month_Array[8];
                    break;
            case 10: 
                    month_index = month_Array[9];
                    break;
            case 11: 
                    month_index = month_Array[10];
                    break;
            case 12: 
                    month_index = month_Array[11];
                    break;
            case 13: 
                    month_index = month_Array[12];
                    break;
        }
        
        if(mDay==0){
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + (day+1).toString();
                var maxDate = year + '-' + month + '-' + day;
                var date =  month_index + '  ' + day + ', ' + year;
                $('#to-date-of-leave').attr('value', date);
                $('#from-date-of-leave').attr('value', date);
                $('#from-date-of-leave').attr('min', maxDate);
                $('#to-date-of-leave').attr('min', maxDate);
        }
        else{
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + (day++).toString();
                var maxDate = year + '-' + month + '-' + day;
                var date =  month_index + '  ' + day + ', ' + year;
                $('#to-date-of-leave').attr('value', date);
                $('#from-date-of-leave').attr('value', date);
                $('#from-date-of-leave').attr('min', maxDate);
                $('#to-date-of-leave').attr('min', maxDate);
        }
       
    });
    $("#from-date-of-leave").datepicker({ 
        dateFormat: "MM dd, yy",
        minDate: new Date(),
        beforeShowDay:beforeShowDay,
    });
    function beforeShowDay(sunday){
        var day = sunday.getDay();
        return [(day > 0), "calendar-background"];  
    }
    $("#to-date-of-leave").datepicker({ 
        dateFormat: "MM dd, yy",
        minDate: new Date(),
        beforeShowDay:beforeShowDay,
    });
    function beforeShowDay(sunday){
        var day = sunday.getDay();
        return [(day > 0), "calendar-background"];  
    }
</script>
<div class="modal fade" id="modal-confirmation">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Confirmation</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to continue? This action cannot be undone.</p>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary"  onclick="fileLeave(<?php echo $id; ?>)" data-dismiss="modal">Continue</button>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal