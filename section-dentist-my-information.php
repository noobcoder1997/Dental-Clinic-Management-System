<?php
    session_start();
    require('config/connection.php');

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    $branch = $_SESSION['branch'];
?>
<div class="container-fluid">
    <?php
        if($position == 'dentist'){
        ?>
            <div class="row">
            <div class="col-sm-12">
                <div class="card mt-3">
                <div class="card-header">
                    <h4 class="">My Personal Information</h4>
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <!-- <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#file-vacation-modal">File Leave</button> -->
                            <div class="modal fade" id="file-vacation-modal">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h4 class="modal-title">File Vacation</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <!-- <label for="e-user">Date: </label><br> -->
                                                    <label for="e-user">From: </label><br>
                                                    <input type="text" class="form-control" id="from-date-of-leave" value=""></input><br> 

                                                    <label for="e-user">To: </label><br>
                                                    <input type="text" class="form-control" id="to-date-of-leave" value=""></input><br>            

                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer float-right">
                                            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                            <button type="button" class="btn btn-secondary"  data-toggle="modal" data-target="#modal-confirmation" data-dismiss="modal">Save changes</button>
                                            <script>
                                                function fileLeave(id){
                                                    console.log($('#from-date-of-leave').val())
                                                    console.log($('#to-date-of-leave').val())
                                                    form = new FormData();
                                                    form.append('id', id);
                                                    form.append('from-date', $('#from-date-of-leave').val());
                                                    form.append('to-date', $('#to-date-of-leave').val());
                                                    $.ajax({
                                                        url: "dentist-file-leave.php",
                                                        type: "POST",
                                                        data: form,
                                                        processData: false,
                                                        contentType: false,
                                                    }).done( function(data){
                                                        myDentistInformation(); 
                                                        alert(data); 
                                                    })
                                                }
                                            </script>
                                        </div>
                                    </div>
                                <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                        <!-- /.modal -->
                            <div class="row">
                            <?php
                                $stmt = $conn->prepare("SELECT * FROM dentist WHERE dentist_id = ? ");
                                $stmt->bind_param('s', $id);
                                $stmt->execute();
                                $result=$stmt->get_result();
                                while($row = $result->fetch_Array(MYSQLI_ASSOC)){
                                ?>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="name">Full Name: </label><br>
                                            <p id="name"><?php echo strtoupper($row['first_name']).' '.strtoupper($row['middle_name']).' '.strtoupper($row['last_name'])?></p><br>

                                            <label for="pos">Position:</label><br>
                                            <p id="pos"><?php echo strtoupper($row['position']);?></p><br>

                                            <!-- <label for="bdate">Birth Date:</label><br>
                                            <p id="bdate"><?php //echo strtoupper($row['birthdate']);?></p><br>
                                            
                                            <label for="add">Address:</label><br>
                                            <p id="add"><?php //echo strtoupper($row['address']);?></p><br>

                                            <label for="cn">Contact Number:</label><br>
                                            <p id="cn"><?php //echo strtoupper($row['contact_no']);?></p><br> -->
                                        </div>
                                    </div>
                                    <!-- <label for="name">Full Name</label>
                                    <p id="name"></p>
                                    <label for="name">Full Name</label>
                                    <p id="name"></p>
                                    <label for="name">Full Name</label>
                                    <p id="name"></p>
                                    <label for="name">Full Name</label>
                                    <p id="name"></p> -->
                                <!-- <input type="text" value="<?php //echo password_hash('admin', PASSWORD_DEFAULT); ?>"> -->
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-default float-right ml-2" data-toggle="modal" data-target="#edit-my-login-credentials-dentist-modal">Edit Login Credentials</button>
                                    <div class="modal fade" id="edit-my-login-credentials-dentist-modal">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h4 class="modal-title">Edit my Login Credentials</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <label for="e-user">Username: </label><br>
                                                            <input class="form-control" id="e-den-user" value="<?php echo $row['username'] ?>"></input><br> 

                                                            <label for="e-pass">Password: </label><br>
                                                            <input class="form-control" id="e-den-pass" placeholder="Password"></input><br>           

                                                            <label for="e-cpass">Confirm Password: </label><br>
                                                            <input class="form-control" id="e-den-cpass" placeholder="Confirm Password"></input><br>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer float-right">
                                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="editMyLoginCredDentist(<?php echo $id; ?>)" >Save changes</button>
                                                    <script>
                                                        function editMyLoginCredDentist(id){
                                                            if( $("#e-den-pass").val() != $("#e-den-cpass").val() ){
                                                                alert('Password did not match!');
                                                            }
                                                            else if( $("#e-den-pass").val() == '' || $("#e-den-cpass").val() == '' || $('#e-den-user').val() == '' ){
                                                                alert('Fields should not leave empty!');
                                                            }
                                                            else {
                                                                form = new FormData();
                                                                form.append('id', id);
                                                                form.append('user', $('#e-den-user').val());
                                                                form.append('pass', $('#e-den-pass').val());
                                                                // form.append('user', id);
                                                                // form.append('pass', id);
                                                                $.ajax({
                                                                    url: "dentist-edit-my-login-credentials.php",
                                                                    type: "POST",
                                                                    data: form,
                                                                    processData: false,
                                                                    contentType: false,
                                                                }).done( function(data){
                                                                    $("#edit-my-login-credentials-dentist-modal").modal("toggle");
                                                                    $(".modal-backdrop").remove();
                                                                    alert(data); 
                                                                })
                                                                myDentistInformation(); 
                                                            }
                                                        }
                                                    </script>
                                                </div>
                                            </div>
                                        <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                <!-- /.modal -->

                            <button type="button" class="btn btn-secondary float-right" data-toggle="modal" data-target="#edit-my-information-dentist-modal">Edit My Information</button>
                                <div class="modal fade" id="edit-my-information-dentist-modal">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h4 class="modal-title">Edit my Information</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <label for="e-fname">First Name: </label><br>
                                                        <input class="form-control" id="e_den-fname" value="<?php echo $row['first_name'] ?>"></input><br> 

                                                        <label for="e-mname">Middle Name: </label><br>
                                                        <input class="form-control" id="e_den-mname" value="<?php echo $row['middle_name'] ?>"></input><br>           

                                                        <label for="e-mname">Last Name: </label><br>
                                                        <input class="form-control" id="e_den-lname" value="<?php echo $row['last_name'] ?>"></input><br>

                                                        <!-- <label for="e-bdate">Birth Date:</label><br>
                                                        <input class="form-control" type="date" id="e-bdate" value="<?php //echo strtoupper($row['birthdate']);?>"></input><br>
                                                        
                                                        <label for="e-add">Address:</label><br>
                                                        <input class="form-control" id="e-add" value="<?php //echo $row['address'];?>"></input><br>

                                                        <label for="e-cn">Contact Number:</label><br>
                                                        <input class="form-control" type="number" id="e-cn" value="<?php //echo strtoupper($row['contact_no']);?>"></input><br> -->

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="editMyInfoDentist(<?php echo $id; ?>)" >Save changes</button>
                                                <script>
                                                    function editMyInfoDentist(id){

                                                        if( $("#e_den-fname").val() == '' || $("#e_den-lname").val() == '' || $("#e_den-mname").val() == '' ){
                                                            alert('Fields should not leave empty!');
                                                        }
                                                        else {
                                                            form = new FormData();
                                                            form.append('id', id);
                                                            form.append('fnim', $('#e_den-fname').val());
                                                            form.append('mnim', $('#e_den-mname').val());
                                                            form.append('lnim', $('#e_den-lname').val());
                                                            // form.append('bdate', $('#e-bdate').val());
                                                            // form.append('address', $('#e-add').val());
                                                            // form.append('age', $('#e-age').val());
                                                            // form.append('contact', $('#e-cn').val());
                                                            // form.append('user', id);
                                                            // form.append('pass', id);
                                                            $.ajax({
                                                                url: "dentist-edit-my-info.php",
                                                                type: "POST",
                                                                data: form,
                                                                processData: false,
                                                                contentType: false,
                                                            }).done( function(data){
                                                                $("#edit-my-information-dentist-modal").modal("toggle");
                                                                $(".modal-backdrop").remove();
                                                                alert(data);
                                                            })
                                                            myDentistInformation(); 
                                                        }
                                                         
                                                    }
                                                </script>
                                            </div>
                                        </div>
                                    <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                            <!-- /.modal -->
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
<!-- /.modal -->