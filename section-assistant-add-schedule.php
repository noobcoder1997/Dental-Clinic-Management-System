<?php
    session_start();
    require('config/connection.php');

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    
    if(isset($_SESSION['designation'])){
        $designation = $_SESSION['designation'];
    }
    else{
        $designation = $_SESSION['branch'];
    }
    $sched_array=array();

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card mt-3">
            <div class="card-header">
                <h4 class="">Dentist Schedules</h4>
            </div>
            <div class="card-body">
                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary float-right" data-toggle="modal" data-target="#sched-modal">Set Schedule</button>
                                
                                <div class="modal fade" id="sched-modal" data-keyboard="false" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h4 class="modal-title">Set Schedule</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="">Dentist:</label>
                                                        <select name="" id="sched-dentist2" class="form-control">
                                                            <?php
                                                                $stmt=$conn->prepare("SELECT * FROM dentist");
                                                                $stmt->execute();
                                                                $result=$stmt->get_result();
                                                                while($row=$result->fetch_assoc()){
                                                                    echo "<option value='$row[dentist_id]'>Dr. $row[first_name] $row[middle_name] $row[last_name]</option>";
                                                                    
                                                                }
                                                            ?>
                                                        </select><br>
                                                        <label for="">From:</label>
                                                        <input type="text" id="sched-from-datepicker" class="form-control" placeholder="Pick a Date"><br>

                                                        <label for="">To:</label>
                                                        <input type="text" id="sched-to-datepicker" class="form-control" placeholder="Pick a Date"><br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                                <button type="button" class="btn btn-primary float-right" id="submit-schedule">Submit</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                
                                
                            </div>
                            <div class="col-sm-12">
                                <br>
                                <h5>Dentist Schedule</h5>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <select name="" id="sched-dentist0" class="form-control">
                                            <?php
                                                $stmt=$conn->prepare("SELECT * FROM dentist");
                                                $stmt->execute();
                                                $result=$stmt->get_result();
                                                while($row=$result->fetch_assoc()){
                                                    echo "<option value='$row[dentist_id]'>Dr. $row[first_name] $row[middle_name] $row[last_name]</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" class="btn btn-primary btn-block float-right" id="submit-dentist">Select Dentist</button>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12" id="show-sched-date">
                                        <input type="text" id="" class="form-control" placeholder="Dentist Schedule" readonly><br>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-sm-12">
                                <h5>View Dentist Schedules</h5>
                                <label for="">Dentist:</label>
                                
                                
                                <label for="">Date Schedule:</label>
                                <input type="text" id="sched-datepicker" class="form-control" placeholder="Pick a Date"><br>

                                <button type="button" class="btn btn-primary float-right" id="submit-schedule2">Submit</button>
                            </div>
                        </div> -->
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
            <div class="card-footer"></div>
            </div>
        </div>
    </div>
</div>
<script>

    $('#sched-from-datepicker, #sched-to-datepicker').datepicker(
        {
            minDate: 0,
            beforeShowDay:beforeShowDay,
            // beforeShowDay: function(date){
            //     var day = date.getDay();
            //     var string = jQuery.datepicker.formatDate("yy-mm-dd",date);
            //     return [(sched_array.indexOf(string) == -1)?(day>0):"", "calendar-background"];
            // },
            dateFormat: "MM dd, yy",
        }
    );
    function beforeShowDay(sunday){
        var day = sunday.getDay();
        return [(day>0), "calendar-background"];  
    }

    var sched_array = <?php echo '["' . implode('", "', $sched_array) . '"]' ?>
    
    $("#submit-schedule").on("click", function(){
        $("#sched-from-datepicker").val();
        $('#sched-to-datepicker').val();
        if($("#sched-from-datepicker").val() == '' || $('#sched-to-datepicker').val() == ''){
            alert("Empty Dates!");
        }
        else{
            form=new FormData()
            form.append('from',$("#sched-from-datepicker").val())
            form.append('to',$('#sched-to-datepicker').val())
            form.append('dentist',$('#sched-dentist2').val())
            form.append('branch',<?php echo $designation ?>)
            $.ajax({
                data:form,
                type:'post',
                url:'set-schedule.php',
                processData: false,
                contentType:false,
            }).done( function(data){
                // console.log(data)
                $("#sched-modal").modal("toggle");
                $(".modal-backdrop").remove();
                alert(data);
            })
            assistantAddSchedule();
        }
    })

    // $("#submit-schedule2").on("click", function(){
    //     $("#sched-datepicker").val();
    //     form=new FormData()
    //     form.append('to',$('#sched-datepicker').val())
    //     form.append('dentist',$('#sched-dentist2').val())
    //     $.ajax({
    //         data:form,
    //         type:'post',
    //         url:'-set-schedule.php',
    //         processData: false,
    //         contentType:false,
    //     }).done( function(data){
    //         // alert(data);
    //         console.log(data)
    //     })
    // })
    $("#submit-dentist").on('click', function(){
        $.ajax({
            data: 'dentist='+$("#sched-dentist0").val()+"&branch="+<?php echo $designation ?>,
            type: 'POST',
            url: 'select-dentist-sched0.php',
        }).done( function(data){
            // alert(data)
            $('#show-sched-date').html(data)
            $('#sched-datepicker').datepicker('show');
        })
    })
</script>
