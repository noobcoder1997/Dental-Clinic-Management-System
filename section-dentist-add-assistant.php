<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];   
    $branch = $_SESSION['branch'];
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <!-- <h3 class="m-0">Dental Clinic Assistant</h3> -->
                        <h4 >Add Assistants</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                        <div class="card-header">                                           
                                            <div class="row float-right">
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#dentist-add-assistant-modal">Add Assistant</button>
                                            </div>
                                            <!-- Add Modal -->
                                            <div class="modal fade" id="dentist-add-assistant-modal" data-keyboard="false" data-backdrop="static">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Add Assistant</h2>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                            <center><label for="">Personal Information</label></center>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                <div class="form-group">
                                                                <label for="">First Name</label>
                                                                    <input type="text" name="first_name" id="as_first_name" class="form-control" placeholder="First Name" required>
                                                                </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                <div class="form-group">
                                                                <label for="">Middle Name</label>
                                                                    <input type="text" name="middle_name" id="as_middle_name" class="form-control" placeholder="Middle Name" required>
                                                                </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                <div class="form-group">
                                                                <label for="">Last Name</label>
                                                                    <input type="text" name="last_name" id="as_last_name" class="form-control" placeholder="Last Name" required>
                                                                </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                <div class="form-group">
                                                                <label for="">Birth Date</label>
                                                                    <input class="form-control" type="text" id="as_bdate" placeholder="Birth Date">
                                                                </div>
                                                                </div> 
                                                                <div class="col-sm-12">
                                                                <div class="form-group">
                                                                <label for="">Age</label>
                                                                    <input type="number" name="" id="as_age" class="form-control" placeholder="Age" required min=1>
                                                                </div>
                                                                </div> 
                                                                <div class="col-sm-12">
                                                                <div class="form-group">
                                                                <label for="">Address</label>
                                                                <textarea name="address" class="form-control" placeholder="Address" rows="3" cols="50"placeholder="Address" id="as_address"  required></textarea>
                                                                </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                <div class="form-group">
                                                                <label for="">Contact Number</label>
                                                                    <input type="number" name="contact_no" id="as_contact_no" class="form-control" placeholder="Contact Number" required>
                                                                </div>
                                                                </div> 
                                                                <div class="col-sm-12">
                                                                <div class="form-group">
                                                                <label for="">Designation</label>
                                                                    <select name="designation" id="as_designation" class="form-control" name="designation" required>
                                                                    <?php 
                                                                        $stmt = $conn->prepare('SELECT * FROM branch');
                                                                        $stmt->execute();
                                                                        $result = $stmt->get_result();
                                                                        while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                                                            ?>
                                                                            <option value="<?php echo $row['branch_id'] ?>"><?php echo $row['location'] ?></option>
                                                                            <?Php
                                                                        }
                                                                    ?>
                                                                    </select>
                                                                </div>
                                                                </div>     
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                    <center><label for="">User Identification</label></center>
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                            <label for="">Username:</label> 
                                                                            <input type="text" name="username" id="as_username" class="form-control" placeholder="Username" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="">Password:</label> 
                                                                                <input type="password" name="password" id="as_password" class="form-control" placeholder="Password" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="">Confirm Password: </label> 
                                                                                <input type="password" name="confirm_password" id="as_confirm_password" class="form-control" placeholder="Confirm Password" required>
                                                                                <p id="-alert-password"></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                 <!--  <center>
                                                                        <img src="assets/dist/img/office.jpg" alt="Dental Clinic Logo" class="logo" id="profilepic"> 
                                                                    </center> -->
                                                                    </div>
                                                                    
                                                                </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <!-- <div class="row float-right">
                                                            <div class="col-sm-12">
                                                                    <button class="btn btn-primary" type="button" onclick="">Register Patient</button>
                                                            </div>
                                                            </div> -->
                                                        </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer float-right">
                                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                        <!-- <button class="btn btn-secondary" type="button" name="dentist-add-assistant"><span class="fa fa-plus fa-fw"></span> Add this Assistant</button> -->
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="dentistAddAssistant()" id="btn-add-asst"><span class="fa fa-plus fa-fw"></span> Add Assistant</button>
                                                        <script>
                                                              function dentistAddAssistant(){

                                                                if($('#as_first_name').val() == "" || $('#as_last_name').val() == "" || $('#as_username').val() == "" || $('#as_confirm_password').val() == "" || $('#as_password').val() == "" || $('#as_designation').val() == ""){
                                                                    alert('Fields should not leave empty');
                                                                    $('#btn-add-asst').removeAttr('data-dismiss');
                                                                }
                                                                else if($('#as_password').val() != $('#as_confirm_password').val()){
                                                                    alert('Passwords did not match.');
                                                                    $('#btn-add-asst').removeAttr('data-dismiss');
                                                                }
                                                                else if($('#as_contact_no').val().length != 11){
                                                                    alert('Invalid Phone number!');
                                                                    $('#btn-add-asst').removeAttr('data-dismiss');
                                                                }
                                                                else{
                                                                    form = new FormData();
                                                                    form.append('first_name', $('#as_first_name').val());
                                                                    form.append('middle_name', $('#as_middle_name').val());
                                                                    form.append('last_name', $('#as_last_name').val());
                                                                    form.append('username', $('#as_username').val());
                                                                    form.append('password', $('#as_confirm_password').val());
                                                                    form.append('designation', $('#as_designation').val());
                                                                    form.append('address', $('#as_address').val());
                                                                    form.append('contact', $('#as_contact_no').val());
                                                                    form.append('age', $('#as_age').val());
                                                                    form.append('bdate', $('#as_bdate').val());
                                                                    $.ajax({
                                                                        url: "dentist-add-assistant.php",
                                                                        type: "POST",
                                                                        data: form,
                                                                        processData: false,
                                                                        contentType: false,
                                                                    }).done( function(data){
                                                                        $("#dentist-add-assistant-modal").modal('hide');
                                                                        $('.modal-backdrop').remove();
                                                                        addDentistAssistant();
                                                                        alert(data);
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
                                            <!-- Modal -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="dentist-view-assistant-table" class="table table-borderless table-hover">
                                            <thead>
                                            <tr>
                                                <th style="width: 30px">#</th>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Username</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no=1;
                                                $stmt = $conn->prepare("SELECT * FROM assistant WHERE assistant_id <> ?");
                                                $stmt->bind_param('s', $id);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $no; ?></td>
                                                            <td>
                                                                <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#dentist-view-assistant-modal<?php echo $row['assistant_id']?>"><span class="fa fa-eye fa-fw"></span></button>
                                                                <!-- View Modal -->
                                                                <div class="modal fade" id="dentist-view-assistant-modal<?php echo $row['assistant_id']?>" data-keyboard="false" data-backdrop="static">
                                                                    <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                        <h4 class="modal-title">Edit Assistant Information</h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <div class="row">
                                                                                        <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">First Name: </label>
                                                                                                <input readonly class="form-control" type="text" value="<?php echo $row['first_name']; ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Middle Name: </label>
                                                                                                <input readonly class="form-control" type="text" value="<?php echo $row['middle_name']; ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Last Name: </label>
                                                                                                <input readonly class="form-control" type="text"  value="<?php echo $row['last_name']; ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Designation: </label>
                                                                                                <select readonly class="form-control" name="">
                                                                                                    <?php
                                                                                                        $stmt1 = $conn->prepare("SELECT * FROM branch");
                                                                                                        $stmt1->execute();
                                                                                                        $result1 = $stmt1->get_result();
                                                                                                        while($row1 = $result1->fetch_array(MYSQLI_ASSOC)){
                                                                                                            ?>
                                                                                                                <option value="<?php echo $row1['branch_id']; ?>"><?php echo $row1['location']; ?></option>
                                                                                                            <?php
                                                                                                        }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Username: </label>
                                                                                                <input readonly class="form-control" type="text" value="<?php echo $row['username']; ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Birth Date</label>
                                                                                                <input class="form-control" type="text"  value="<?php echo $row['bdate']; ?>" readonly>
                                                                                            </div>
                                                                                        </div> 
                                                                                        <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Age</label>
                                                                                                <input type="number" name=""class="form-control" value="<?php echo $row['age']; ?>" readonly min=1>
                                                                                            </div>
                                                                                        </div> 
                                                                                        <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Address</label>
                                                                                                <textarea name="address" class="form-control" placeholder="Address" rows="3" cols="50"placeholder="Address"  readonly><?php echo $row['username']; ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Contact Number</label>
                                                                                                <input type="number" name="contact_no"  class="form-control" value="<?php echo $row['contact_no']; ?>" readonly>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer float-right">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                    <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>
                                                                <!-- /.modal -->
                                                            </td>
                                                            <td><?php echo $row['first_name']." ".$row['middle_name']." ".$row['last_name']; ?></td>
                                                            <td>
                                                                <?php 
                                                                    $stmt0 = $conn->prepare("SELECT * FROM branch WHERE branch_id = ?");
                                                                    $stmt0->bind_param('s',$row['designation']);
                                                                    $stmt0->execute();

                                                                    $result0 = $stmt0->get_result();
                                                                    if($row0 = $result0->fetch_array(MYSQLI_ASSOC)){
                                                                        echo $row0['location'];
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td><?php echo $row['username']; ?></td>
                                                            <td>
                                                            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#dentist-edit-assistant-modal<?php echo $row['assistant_id']?>"><span class="fa fa-edit fa-fw"></span>
                                                            </button>

                                                            <!-- Edit Modal -->
                                                            <div class="modal fade" id="dentist-edit-assistant-modal<?php echo $row['assistant_id']?>" data-keyboard="false" data-backdrop="static">
                                                                <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                    <h4 class="modal-title">Edit Assistant Information</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <div class="row">
                                                                                    <div class="col-sm-12">
                                                                                        <div class="form-group">
                                                                                            <label for="">First Name: </label>
                                                                                            <input class="form-control" type="text" id="f-nim-<?php echo $row['assistant_id']?>" value="<?php echo $row['first_name']; ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-12">
                                                                                        <div class="form-group">
                                                                                            <label for="">Middle Name: </label>
                                                                                            <input class="form-control" type="text" id="m-nim-<?php echo $row['assistant_id']?>" value="<?php echo $row['middle_name']; ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-12">
                                                                                        <div class="form-group">
                                                                                            <label for="">Last Name: </label>
                                                                                            <input class="form-control" type="text" id="l-nim-<?php echo $row['assistant_id']?>" value="<?php echo $row['last_name']; ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-12">
                                                                                        <div class="form-group">
                                                                                            <label for="">Designation: </label>
                                                                                            <select class="form-control" name="" id="des-<?php echo $row['assistant_id']?>">
                                                                                                <?php
                                                                                                    $stmt1 = $conn->prepare("SELECT * FROM branch");
                                                                                                    $stmt1->execute();
                                                                                                    $result1 = $stmt1->get_result();
                                                                                                    while($row1 = $result1->fetch_array(MYSQLI_ASSOC)){
                                                                                                        ?>
                                                                                                            <option value="<?php echo $row1['branch_id']; ?>"><?php echo $row1['location']; ?></option>
                                                                                                        <?php
                                                                                                    }
                                                                                                ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-12">
                                                                                        <div class="form-group">
                                                                                            <label for="">Birth Date</label>
                                                                                            <input class="form-control" type="text" id="b-date-<?php echo $row['assistant_id']?>"  value="<?php echo $row['bdate']; ?>" >
                                                                                        </div>
                                                                                    </div> 
                                                                                    <div class="col-sm-12">
                                                                                        <div class="form-group">
                                                                                            <label for="">Age</label>
                                                                                            <input type="number" name=""class="form-control"  id="age-<?php echo $row['assistant_id']?>"value="<?php echo $row['age']; ?>"  min=1>
                                                                                        </div>
                                                                                    </div> 
                                                                                    <div class="col-sm-12">
                                                                                        <div class="form-group">
                                                                                            <label for="">Address</label>
                                                                                            <textarea name="address" class="form-control" placeholder="Address" rows="3" cols="50"placeholder="Address"   id="address-<?php echo $row['assistant_id']?>"><?php echo $row['username']; ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-12">
                                                                                        <div class="form-group">
                                                                                            <label for="">Contact Number</label>
                                                                                            <input type="number" name="contact_no"  class="form-control" value="<?php echo $row['contact_no']; ?>"  id="cno-<?php echo $row['assistant_id']?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-12">
                                                                                        <div class="form-group">
                                                                                            <label for="">Username: </label>
                                                                                            <input class="form-control" type="text" id="user-<?php echo $row['assistant_id']?>" value="<?php echo $row['username']; ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-12">
                                                                                        <div class="form-group">
                                                                                            <label for="">Password: </label>
                                                                                            <input class="form-control" type="text" id="pass-<?php echo $row['assistant_id']?>" value="" placeholder="password will not change if blank">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-12">
                                                                                        <div class="form-group">
                                                                                            <label for="">Confirm Password: </label>
                                                                                            <input class="form-control" type="text" id="c-pass-<?php echo $row['assistant_id']?>" value="" placeholder="password will not change if blank">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer float-right">
                                                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                                    <button type="button" class="btn btn-primary" id="btn-edit-asst2" data-dismiss="modal" onclick="editAssistant(<?php echo $row['assistant_id']?>)" >Save changes</button>
                                                                    <script>
                                                                        function editAssistant(id){
                                                                            if($('#f-nim-'+id).val() == '' || $('#l-nim-'+id).val() == '' || $('#des-'+id).val() == '' || $('#user-'+id).val() == ''){
                                                                                alert('Fields should not leave empty.');
                                                                                $('#btn-edit-asst2').removeAttr('data-dismiss');
                                                                            }
                                                                            else if($('#pass-'+id).val() != $('#c-pass-'+id).val()){
                                                                                alert('Passwords did not match.');
                                                                                $('#btn-edit-asst2').removeAttr('data-dismiss');
                                                                            }
                                                                            else if($('#cno-'+id).val().length != 11){
                                                                                alert('Invalid Phone number!')
                                                                            }
                                                                            else{
                                                                                form = new FormData();
                                                                                form.append('first_name', $('#f-nim-'+id).val());
                                                                                form.append('middle_name', $('#m-nim-'+id).val());
                                                                                form.append('last_name', $('#l-nim-'+id).val());
                                                                                form.append('username', $('#user-'+id).val());
                                                                                form.append('designation', $('#des-'+id).val());
                                                                                form.append('address', $('#address-'+id).val());
                                                                                form.append('contact', $('#cno-'+id).val());
                                                                                form.append('age', $('#age-'+id).val());
                                                                                form.append('birthdate', $('#b-date-'+id).val());
                                                                                form.append('password', $('#pass-'+id).val());
                                                                                form.append('id', id);
                                                                                $.ajax({
                                                                                    url: "dentist-edit-assistant.php",
                                                                                    type: "POST",
                                                                                    data: form,
                                                                                    processData: false,
                                                                                    contentType: false,
                                                                                }).done( function(data){
                                                                                    alert(data);
                                                                                    $("#dentist-edit-assistant-modal"+id).modal('hide');
                                                                                    $('.modal-backdrop').remove();
                                                                                })
                                                                                addDentistAssistant();
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
                                                            <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#dentist-delete-assistant-modal<?php echo $row['assistant_id']?>"><span class="fa fa-trash fa-fw"></span>
                                                            </button>

                                                            <!-- Delete Assistant -->
                                                                <div class="modal fade" id="dentist-delete-assistant-modal<?php echo $row['assistant_id']?>" data-keyboard="false" data-backdrop="static">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                            <h4 class="modal-title">Delete Assistant Informaton</h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                            <p>Do you want to continue? This action cannot be undone.&hellip;</p>
                                                                            </div>
                                                                            <div class="modal-footer justify-content-between">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="deleteAssistant(<?php echo $row['assistant_id'] ?>)">Continue</button>
                                                                            </div>
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                            <script>
                                                                                function deleteAssistant(id){
                                                                                    $.ajax({
                                                                                        url: 'dentist-delete-assistant.php',
                                                                                        method: 'post',
                                                                                        data: 'id='+id,
                                                                                    }).done( function(data){
                                                                                        alert(data); 
                                                                                        addDentistAssistant(); 
                                                                                    })
                                                                                }
                                                                            </script>
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>
                                                                <!-- /.modal -->
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    $no++;
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="width: 30px">#</th>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Username</th>
                                                <th></th>
                                            </tr>
                                            </tfoot>
                                            </table>
                                        </div>
                                        <!-- /.card-body -->
                                        <!-- /.card -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>

</section>
<script>
    $(document).ready( function(){
        $(function () {
            $("#dentist-view-assistant-table").DataTable({
            "responsive": true,
            "lengthChange": true, 
            "autoWidth": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "scrollX": true,
            });
        });
    })
</script>
<script>
    $('#as_bdate').datepicker(
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