<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];   
    $branch = $_SESSION['branch'];
?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card mt-3">
              <div class="card-header">
                <!-- <h3 class="card-title">Dental Clinic Branch</h3> -->
                <h4 class="">Add Dentists</h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card">
                        <div class="card-header">
                            <div class="row float-right">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#dentist-add-dentist-modal">Add Dentist</button>
                                <!-- Add Modal -->
                                <div class="modal fade" id="dentist-add-dentist-modal"  data-keyboard="false" data-backdrop="static">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h4 class="modal-title">Add Dentist</h4>
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
                                                                <label for="first-name">First Name: </label><br>
                                                                <input class="form-control" type="text" name="" id="first-name"><br>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="middle-name">Middle Name: </label><br>
                                                                <input class="form-control" type="text" name="" id="middle-name"><br>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="last-name">Last Name: </label><br>
                                                                <input class="form-control" name="" id="last-name"><br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label for="username">Username: </label><br>
                                                                <input class="form-control" type="text" name="" id="username"><br>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="pass">Password: </label><br>
                                                                <input class="form-control" type="text" name="" id="pass"><br>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="confirm-pass">Confirm Password: </label><br>
                                                                <input class="form-control" type="text" name="" id="confirm-pass"><br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer float-right">
                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn-add-dent" onclick="addDentist()">Add Dentist</button>
                                        <script>    
                                            function addDentist(){
                                                pass='';

                                                if($('#first-name').val() == '' || $('#last-name').val() == '' || $('#username').val() == '' || $('#pass').val() == '' || $('#confirm-pass').val() == ''){
                                                    alert('Fields should not leave empty.');
                                                    $("#btn-add-dent").removeAttr('data-dismiss');
                                                }
                                                else if($('#pass').val() != $('#confirm-pass').val()){
                                                    alert('Passwords did not match.');
                                                    $("#btn-add-dent").removeAttr('data-dismiss');
                                                }
                                                else{   
                                                    form = new FormData();
                                                    form.append('firstname', $('#first-name').val());
                                                    form.append('middlename', $('#middle-name').val());
                                                    form.append('lastname', $('#last-name').val());
                                                    form.append('username', $('#username').val());
                                                    form.append('password', $('#pass').val());
                                                    $.ajax({
                                                        url: "dentist-add-dentist.php",
                                                        type: "POST",
                                                        data: form,
                                                        processData: false,
                                                        contentType: false,
                                                    }).done( function(data){
                                                        $("#dentist-add-dentist-modal").modal('hide');
                                                        $('.modal-backdrop').remove();
                                                        alert(data);
                                                    })
                                                    addDentistDentist();
                                                }
                                            }
                                        </script>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                     <!-- <script>
                                        // /this makes the input blank everytime the modal is displayed
                                        $(document).ready( function(){
                                            $(document).ready(function() {
                                                $("#dentist-add-dentist-modal").on("hidden.bs.modal", function() {
                                                $("#dentist-add-dentist-modal input").val('');
                                                });
                                            });
                                        })
                                     </script> -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="dentist-view-services-table" class="table table-borderless table-hover">
                              <thead>
                              <tr>
                                <th style="width: 30px">#</th>
                                <th></th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                                <th></th>
                              </tr>
                              </thead>
                              <tbody>
                                <?php
                                $no=1;
                                $stmt = $conn->prepare("SELECT * FROM dentist WHERE dentist_id <> ?");
                                $stmt->bind_param('s', $id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                                <button class="btn btn-success btn-xs" data-target="#dentist-view-dentist-modal<?php echo $row['dentist_id']?>" data-toggle="modal"><span class="fa fa-eye fa-fw"></span></button>
                                                <!-- View Modal -->
                                                <div class="modal fade" id="dentist-view-dentist-modal<?php echo $row['dentist_id']?>" data-keyboard="false" data-backdrop="static">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-secondary">
                                                        <h4 class="modal-title">Edit Dentist Information</h4>
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
                                                                                <label for="fnim">First Name: </label>
                                                                                <input class="form-control" type="text" value="<?php echo $row['first_name']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="mnim">Middle Name: </label>
                                                                                <input class="form-control" type="text"  value="<?php echo $row['middle_name']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="lnim">Last Name: </label>
                                                                                <input class="form-control" type="text" value="<?php echo $row['last_name']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="euser">Username: </label>
                                                                                <input class="form-control" type="text" value="<?php echo $row['username']; ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer float-right">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                    </div>
                                                    <!-- <script>
                                                        // /this makes the input blank everytime the modal is displayed
                                                        $(document).ready( function(){
                                                            $(document).ready(function() {
                                                                $("#dentist-edit-dentist-modal"+<?php //echo $row['dentist_id'] ?>).on("hidden.bs.modal", function() {
                                                                    addDentistDentist();
                                                                });
                                                            });
                                                        })
                                                    </script> -->
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->
                                            </td>
                                            <td><?php echo $row['first_name']; ?></td>
                                            <td><?php echo $row['middle_name']; ?></td>
                                            <td><?php echo $row['last_name']; ?></td>
                                            <td><?php echo $row['username']; ?></td>
                                            <td>
                                                <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#dentist-edit-dentist-modal<?php echo $row['dentist_id']?>"><span class="fa fa-edit fa-fw"></span>
                                                </button>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="dentist-edit-dentist-modal<?php echo $row['dentist_id']?>" data-keyboard="false" data-backdrop="static">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h4 class="modal-title">Edit Dentist Information</h4>
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
                                                                                <label for="fnim">First Name: </label>
                                                                                <input class="form-control" type="text" id="den_fnim<?php echo $row['dentist_id']?>" value="<?php echo $row['first_name']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="mnim">Middle Name: </label>
                                                                                <input class="form-control" type="text" id="den_mnim<?php echo $row['dentist_id']?>" value="<?php echo $row['middle_name']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="lnim">Last Name: </label>
                                                                                <input class="form-control" type="text" id="den_lnim<?php echo $row['dentist_id']?>" value="<?php echo $row['last_name']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="euser">Username: </label>
                                                                                <input class="form-control" type="text" id="den_euser<?php echo $row['dentist_id']?>" value="<?php echo $row['username']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="epass">Password: </label>
                                                                                <input class="form-control" type="text" id="den_epass<?php echo $row['dentist_id']?>" value="" placeholder="password will not change if blank">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="epass">Confirm Password: </label>
                                                                                <input class="form-control" type="text" id="den_cpass<?php echo $row['dentist_id']?>" value="" placeholder="password will not change if blank">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer float-right">
                                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn-edit-dent1" onclick="editDentist(<?php echo $row['dentist_id']?>)">Save changes</button>
                                                        <script>
                                                            function editDentist(id){
                                                                
                                                                if( ( $('#den_epass'+id).val() != $('#den_cpass'+id).val() )){
                                                                    alert('Passwords not match!');
                                                                    $("#btn-edit-dent1").removeAttr('data-dismiss');
                                                                }
                                                                else if($('#den_fnim'+id).val() == '' || $('#den_lnim'+id).val() == '' || $('#den_euser'+id).val() == ''){
                                                                    alert('Fields should not leave empty.');
                                                                    $("#btn-edit-dent1").removeAttr('data-dismiss');
                                                                }
                                                                else{
                                                                    form = new FormData();
                                                                    form.append('fnim', $('#den_fnim'+id).val());
                                                                    form.append('mnim', $('#den_mnim'+id).val());
                                                                    form.append('lnim', $('#den_lnim'+id).val());
                                                                    form.append('user', $('#den_euser'+id).val());
                                                                    form.append('pass', $('#den_cpass'+id).val());
                                                                    form.append('id', id);
                                                                    $.ajax({
                                                                        url: "dentist-edit-dentist.php",
                                                                        type: "POST",
                                                                        data: form,
                                                                        processData: false,
                                                                        contentType: false,
                                                                    }).done( function(data){
                                                                        $("#dentist-edit-dentist-modal"+id).modal('hide');
                                                                        $(".modal-backdrop").remove();
                                                                        addDentistDentist();
                                                                        alert(data);
                                                                    })
                                                                }
                                                            }
                                                        </script>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                    </div>
                                                    <!-- <script>
                                                        // /this makes the input blank everytime the modal is displayed
                                                        $(document).ready( function(){
                                                            $(document).ready(function() {
                                                                $("#dentist-edit-dentist-modal"+<?php echo $row['dentist_id'] ?>).on("hidden.bs.modal", function() {
                                                                    addDentistDentist();
                                                                });
                                                            });
                                                        })
                                                    </script> -->
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->
                                                <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#dentist-delete-dentist-modal<?php echo $row['dentist_id']?>"><span class="fa fa-trash fa-fw"></span>
                                                </button>

                                                <!-- Delete Assistant -->
                                                <div class="modal fade" id="dentist-delete-dentist-modal<?php echo $row['dentist_id']?>" data-keyboard="false" data-backdrop="static">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h4 class="modal-title">Delete Dentist Informaton</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body">
                                                            <p>Do you want to continue? This action cannot be undone.&hellip;</p>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="deleteDentist(<?php echo $row['dentist_id'] ?>)">Continue</button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                            <script>
                                                                function deleteDentist(id){
                                                                    $.ajax({
                                                                        url: 'dentist-delete-dentist.php',
                                                                        method: 'post',
                                                                        data: 'id='+id,
                                                                    }).done( function(data){
                                                                        alert(data);   
                                                                        addDentistDentist();
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
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                                <th></th>
                              </tr>
                              </tfoot>
                            </table>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
<script>
  $(document).ready( function(){
        $(function () {
            $("#dentist-view-services-table").DataTable({
            "responsive": true,
            "lengthChange": true, 
            "autoWidth": true,
            // "pageLength": 50,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "scrollX": true,
            });
        });
  })
</script>