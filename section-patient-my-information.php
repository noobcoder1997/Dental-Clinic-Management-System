<?php
    session_start();
    require('config/connection.php');

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
?>
<div class="container-fluid">
    <?php
        if($position == 'patient'){
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
                            <div class="row">
                            <?php
                                $stmt = $conn->prepare("SELECT * FROM register_patient WHERE register_id = ? ");
                                $stmt->bind_param('s', $id);
                                $stmt->execute();
                                $result=$stmt->get_result();
                                while($row = $result->fetch_Array(MYSQLI_ASSOC)){
                                    $birthdate = strtotime($row['birthdate']);
                                    $date = getDate($birthdate);
                                    // $fDay = $date['weekday'];
                                    $mDay = $date['mday'];
                                    $Month = $date['month'];
                                    $Year = $date['year'];
                                    $birthdate = $Month." ".$mDay.', '.$Year;
                                ?>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="name">Full Name: </label><br>
                                            <p id="name"><?php echo strtoupper($row['first_name']).' '.strtoupper($row['middle_name']).' '.strtoupper($row['last_name'])?></p><br>

                                            <label for="age">Age:</label><br>
                                            <p id="age"><?php echo strtoupper($row['age']);?></p><br>

                                            <label for="bdate">Birth Date:</label><br>
                                            <p id="bdate"><?php  echo ($birthdate); ?> </p><br>
                                            
                                            <label for="add">Address:</label><br>
                                            <p id="add"><?php echo strtoupper($row['address']);?></p><br>

                                            <label for="cn">Contact Number:</label><br>
                                            <p id="cn"><?php echo strtoupper($row['contact_no']);?></p><br>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-default float-right ml-2" data-toggle="modal" data-target="#edit-my-login-credentials-patient-modal">Edit Login Credentials</button>
                                    <div class="modal fade" id="edit-my-login-credentials-patient-modal">
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
                                                            <input class="form-control" id="e-user" value="<?php echo $row['username'] ?>"></input><br> 

                                                            <label for="e-pass">Password: </label><br>
                                                            <input class="form-control" id="e-pass" placeholder="Password"></input><br>           

                                                            <label for="e-cpass">Confirm Password: </label><br>
                                                            <input class="form-control" id="e-cpass" placeholder="Confirm Password"></input><br>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer float-right">
                                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                    <button type="button" class="btn btn-secondary" id="logInCredBtn" onclick="editMyLoginCredPatient(<?php echo $id; ?>)" data-dismiss="modal">Save changes</button>
                                                    <script>
                                                        function editMyLoginCredPatient(id){
                                                            if( $("#e-pass").val() != $("#e-cpass").val() ){
                                                                alert('Password did not match!');
                                                                $('#logInCredBtn').removeAttr('data-dismiss');
                                                            }
                                                            else if( $("#e-pass").val() == '' || $("#e-cpass").val() == '' || $('#e-user').val() == '' ){
                                                                $('#logInCredBtn').removeAttr('data-dismiss');
                                                                alert('Fields should not leave empty!');
                                                            }
                                                            else {
                                                                form = new FormData();
                                                                form.append('id', id);
                                                                form.append('user', $('#e-user').val());
                                                                form.append('pass', $('#e-pass').val());
                                                                $.ajax({
                                                                    url: "patient-edit-my-login-credentials.php",
                                                                    type: "POST",
                                                                    data: form,
                                                                    processData: false,
                                                                    contentType: false,
                                                                }).done( function(data){
                                                                    alert(data);
                                                                })
                                                                $("#edit-my-login-credentials-patient-modal").modal('hide');
                                                                $('.modal-backdrop').remove();
                                                                $("#edit-my-login-credentials-patient-modal").trigger('reset');
                                                                // location.reload()
                                                                myInformation();
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

                            <button type="button" class="btn btn-secondary float-right" data-toggle="modal" data-target="#edit-my-information-patient-modal">Edit My Information</button>
                                <div class="modal fade" id="edit-my-information-patient-modal">
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
                                                        <input class="form-control" id="e-fname" value="<?php echo $row['first_name'] ?>"></input><br> 

                                                        <label for="e-mname">Middle Name: </label><br>
                                                        <input class="form-control" id="e-mname" value="<?php echo $row['middle_name'] ?>"></input><br>           

                                                        <label for="e-mname">Last Name: </label><br>
                                                        <input class="form-control" id="e-lname" value="<?php echo $row['last_name'] ?>"></input><br>

                                                        <label for="e-age">Age:</label><br>
                                                        <input class="form-control" type="number" id="e-age" value="<?php echo strtoupper($row['age']);?>"></input><br>

                                                        <label for="e-bdate">Birth Date:</label><br>
                                                        <input class="form-control" type="text" id="e-bdate" value="<?php echo $birthdate;?>"></input><br>
                                                        
                                                        <label for="e-add">Address:</label><br>
                                                        <input class="form-control" id="e-add" value="<?php echo $row['address'];?>"></input><br>

                                                        <label for="e-cn">Contact Number:</label><br>
                                                        <input class="form-control" type="number" id="e-cn" value="<?php echo strtoupper($row['contact_no']);?>"></input><br>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                <button type="button" class="btn btn-secondary" onclick="editMyInfoPatient(<?php echo $id; ?>)" data-dismiss="modal" id="myInfoBtn">Save changes</button>
                                                <script>
                                                    function editMyInfoPatient(id){

                                                        if( $("#e-fname").val() == '' || $("#e-lname").val() == '' || $("#e-age").val() == '' || $("#e-bdate").val() == '' || $("#e-add").val() == '' || $("#e-cn").val() == '' ){
                                                            alert('Fields should not leave empty!');
                                                            $('#myInfoBtn').removeAttr('data-dismiss');
                                                        }
                                                        else {
                                                            form = new FormData();
                                                            form.append('id', id);
                                                            form.append('fnim', $('#e-fname').val());
                                                            form.append('mnim', $('#e-mname').val());
                                                            form.append('lnim', $('#e-lname').val());
                                                            form.append('bdate', $('#e-bdate').val());
                                                            form.append('address', $('#e-add').val());
                                                            form.append('age', $('#e-age').val());
                                                            form.append('contact', $('#e-cn').val());
                                                            $.ajax({
                                                                url: "patient-edit-my-info.php",
                                                                type: "POST",
                                                                data: form,
                                                                processData: false,
                                                                contentType: false,
                                                            }).done( function(data){
                                                                alert(data)
                                                            })
                                                            $("#edit-my-information-patient-modal").modal('hide');
                                                            $('.modal-backdrop').remove();
                                                            $("#edit-my-information-patient-modal").trigger('reset');
                                                            // location.reload();
                                                            myInformation();
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
    $('#e-bdate').datepicker(
        {
            maxDate: -1,
            beforeShowDay: beforeShowDay,
            dateFormat: "MM dd, yy",
        }
    );
    function beforeShowDay(sunday){
        var day = sunday.getDay();
        return [true, "calendar-background"];  
    }
</script>
