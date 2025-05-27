<?php
    session_start();
    require('config/connection.php');

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    $designation = $_SESSION['designation'];
?>
<div class="container-fluid">
    <?php
        if($position == 'assistant'){
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
                                    $stmt = $conn->prepare("SELECT * FROM assistant WHERE assistant_id = ? ");
                                    $stmt->bind_param('s', $id);
                                    $stmt->execute();
                                    $result=$stmt->get_result();
                                    while($row = $result->fetch_Array(MYSQLI_ASSOC)){
                                ?>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="name">Full Name: </label><br>
                                            <p id="name"><?php echo strtoupper($row['first_name']).' '.strtoupper($row['middle_name']).' '.strtoupper($row['last_name'])?></p><br>

                                            <label for="name">Age: </label><br>
                                            <p id="name"><?php echo strtoupper($row['age'])?></p><br>
                                            
                                            <label for="name">Address: </label><br>
                                            <p id="name"><?php echo strtoupper($row['address'])?></p><br>

                                            <label for="name">Contact Number: </label><br>
                                            <p id="name"><?php echo strtoupper($row['contact_no'])?></p><br>
                                            
                                            <label for="designation">Designation:</label><br>
                                            <?php
                                                $stmt0 = $conn->prepare(("SELECT * FROM branch WHERE branch_id = ?"));
                                                $stmt0->bind_param("s", $row['designation']);
                                                $stmt0->execute();
                                                $result0 = $stmt0->get_result();
                                                if($row0 = $result0->fetch_assoc()){
                                                    ?>
                                                        <p id="designation"><?php echo strtoupper($row0['location']);?></p><br>
                                                    <?php
                                                }
                                            ?>
                                            
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <!-- <button type="button" class="btn btn-default float-right ml-2" data-toggle="modal" data-target="#edit-my-login-credentials-assistant-modal">Edit Login Credentials</button> -->
                                    <div class="modal fade" id="edit-my-login-credentials-assistant-modal">
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
                                                            <input class="form-control" id="e-as-user" value="<?php echo $row['username'] ?>"></input><br> 

                                                            <label for="e-pass">Password: </label><br>
                                                            <input class="form-control" id="e-as-pass" placeholder="Password"></input><br>           

                                                            <label for="e-cpass">Confirm Password: </label><br>
                                                            <input class="form-control" id="e-as-cpass" placeholder="Confirm Password"></input><br>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer float-right">
                                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                    <button type="button" class="btn btn-secondary" id="btn-login-cred" onclick="editAssistantLoginCred(<?php echo $id; ?>)" data-dismiss="modal">Save changes</button>
                                                    <script>
                                                        function editAssistantLoginCred(id){
                                                            if( $("#e-as-pass").val() != $("#e-as-cpass").val() ){
                                                                alert('Password did not match!');
                                                                $('#btn-login-cred').removeAttr('data-dismiss');
                                                            }
                                                            else if( $("#e-as-pass").val() == '' || $("#e-as-cpass").val() == '' || $('#e-as-user').val() == '' ){
                                                                alert('Fields should not leave empty!');
                                                                $('#btn-login-cred').removeAttr('data-dismiss');
                                                            }
                                                            else {
                                                                form = new FormData();
                                                                form.append('id', id);
                                                                form.append('user', $('#e-as-user').val());
                                                                form.append('pass', $('#e-as-pass').val());
                                                                $.ajax({
                                                                    url: "assistant-edit-my-login-credentials.php",
                                                                    type: "POST",
                                                                    data: form,
                                                                    processData: false,
                                                                    contentType: false,
                                                                }).done( function(data){
                                                                    
                                                                    if(data == 'Successfully Updated!'){
                                                                        alert(data);
                                                                    }
                                                                    else{
                                                                        alert(data);
                                                                    }
                                                                })
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

                            <button type="button" class="btn btn-secondary float-right" data-toggle="modal" data-target="#edit-my-information-assistant-modal">Edit My Information</button>
                                <div class="modal fade" id="edit-my-information-assistant-modal">
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
                                                        <input class="form-control" id="e-as-fname" value="<?php echo $row['first_name'] ?>"></input><br> 

                                                        <label for="e-mname">Middle Name: </label><br>
                                                        <input class="form-control" id="e-as-mname" value="<?php echo $row['middle_name'] ?>"></input><br>           

                                                        <label for="e-lname">Last Name: </label><br>
                                                        <input class="form-control" id="e-as-lname" value="<?php echo $row['last_name'] ?>"></input><br>

                                                        <label for="e-mname">Age: </label><br>
                                                        <input class="form-control" id="e-as-age" value="<?php echo $row['age'] ?>"></input><br>

                                                        <label for="e-mname">Birt date: </label><br>
                                                        <input class="form-control" id="e-as-bdate" value="<?php echo $row['bdate'] ?>"></input><br>

                                                        <label for="e-mname">Address: </label><br>
                                                        <input class="form-control" id="e-as-address" value="<?php echo $row['address'] ?>"></input><br>

                                                        <label for="e-mname">Contact Number: </label><br>
                                                        <input class="form-control" id="e-as-cno" value="<?php echo $row['contact_no'] ?>"></input><br>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                <button type="button" class="btn btn-secondary" id="btn-edit-info" onclick="editMyInfoAssistant(<?php echo $id; ?>)" data-dismiss="modal">Save changes</button>
                                                <script>
                                                    function editMyInfoAssistant(id){

                                                        if( $("#e-as-fname").val() == '' || $("#e-as-lname").val() == ''  ){
                                                            alert('Fields should not leave empty!');
                                                            $('#btn-edit-info').removeAttr('data-dismiss');
                                                        }
                                                        else if($('#e-as-cno').val().length != 11){
                                                            alert('Invalid Phone number!')
                                                        }
                                                        else {
                                                            form = new FormData();
                                                            form.append('id', id);
                                                            form.append('fnim', $('#e-as-fname').val());
                                                            form.append('mnim', $('#e-as-mname').val());
                                                            form.append('lnim', $('#e-as-lname').val());
                                                            form.append('age', $('#e-as-age').val());
                                                            form.append('address', $('#e-as-address').val());
                                                            form.append('contact_no', $('#e-as-cno').val());
                                                            form.append('bdate', $('#e-as-bdate').val());
                                                            $.ajax({
                                                                url: "assistant-edit-my-info.php",
                                                                type: "POST",
                                                                data: form,
                                                                processData: false,
                                                                contentType: false,
                                                            }).done( function(data){
                                                                if(data == 'Successfully Updated!' ){
                                                                    alert(data);
                                                                }
                                                                else{
                                                                    alert(data)
                                                                }
                                                            })
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
    $('#e-as-bdate').datepicker(
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