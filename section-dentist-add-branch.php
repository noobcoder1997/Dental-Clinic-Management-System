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
                <h4 class="">Add Branches</h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card">
                        <div class="card-header">
                            <div class="row float-right">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#dentist-add-branch-modal">Add branch</button>
                                <!-- Add Modal -->
                                <div class="modal fade" id="dentist-add-branch-modal" data-keyboard="false" data-backdrop="static">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header ">
                                        <h4 class="modal-title">Add Branch</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="location">Location: </label>
                                                        <input class="form-control" type="text" name="" id="location">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="gcash">Gcash #: </label>
                                                        <input class="form-control" type="number" name="" id="gcash">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="contact">Contact #: </label>
                                                        <input class="form-control" type="number" name="" id="contact">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer float-right">
                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addBranch()" id="btn-add-branch1">Add Branch</button>
                                        <script>
                                            function addBranch(){
                                                if($('#location').val() == '' || $('#gcash').val() == '' || $('#contact').val() == ''){
                                                    alert('Fields should not leave empty.');
                                                    $('#btn-add-branch1').removeAttr('data-dismiss')
                                                }
                                                else if($('#contact').val().length != 11 || $('#gcash').val().length != 11){
                                                    alert('Invalid Phone Number');
                                                }
                                                else{
                                                    form = new FormData();
                                                    form.append('loc', $('#location').val());
                                                    form.append('gcash', $('#gcash').val());
                                                    form.append('contact', $('#contact').val());
                                                    $.ajax({
                                                        url: "dentist-add-branch.php",
                                                        type: "POST",
                                                        data: form,
                                                        processData: false,
                                                        contentType: false,
                                                    }).done( function(data){
                                                        $("#dentist-add-branch-modal").modal('hide');
                                                        $('.modal-backdrop').remove();
                                                        addDentistBranch();
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
                                     <!-- <script>
                                        // /this makes the input blank everytime the modal is displayed
                                        $(document).ready( function(){
                                            $(document).ready(function() {
                                                $("#dentist-add-branch-modal").on("hidden.bs.modal", function() {
                                                $("#dentist-add-branch-modal input").val('');
                                                $("#dentist-add-branch-modal textarea").val('');
                                                });
                                            });
                                        })
                                     </script> -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="dentist-view-branch-table" class="table table-borderless table-hover">
                              <thead>
                              <tr>
                                <th style="width: 30px">#</th>
                                <th></th>
                                <th>Location</th>
                                <th>Gcash #</th>
                                <th>Contact #</th>
                                <th></th>
                              </tr>
                              </thead>
                              <tbody>
                                <?php
                                $no=1;
                                $stmt = $conn->prepare("SELECT * FROM branch");
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                                <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#dentist-view-branch-modal<?php echo $row['branch_id']?>"><span class="fa fa-eye fa-fw"></span></button>
                                                <!-- View Modal -->
                                                <div class="modal fade" id="dentist-view-branch-modal<?php echo $row['branch_id']?>" data-keyboard="false" data-backdrop="static">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h4 class="modal-title">View Branch Information</h4>
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
                                                                                <label for="">Location: </label>
                                                                                <input readonly class="form-control" type="text" value="<?php echo $row['location']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="">Gcash #: </label>
                                                                                <input readonly class="form-control" type="number" value="<?php echo $row['branch_gcash_no']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="">Contact #: </label>
                                                                                <input readonly class="form-control" type="number" value="<?php echo $row['contact_no']; ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer float-right">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </script>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->
                                            </td>
                                            <td><?php echo $row['location']; ?></td>
                                            <td><?php echo $row['branch_gcash_no']; ?></td>
                                            <td><?php echo $row['contact_no']; ?></td>
                                            <td>
                                              <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#dentist-edit-branch-modal<?php echo $row['branch_id']?>"><span class="fa fa-edit fa-fw"></span>
                                              </button>

                                            <!-- Edit Modal -->
                                              <div class="modal fade" id="dentist-edit-branch-modal<?php echo $row['branch_id']?>" data-keyboard="false" data-backdrop="static">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">Edit Branch Information</h4>
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
                                                                            <label for="">Location: </label>
                                                                            <input class="form-control" type="text" id="loc-<?php echo $row['branch_id']?>" value="<?php echo $row['location']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Gcash #: </label>
                                                                            <input class="form-control" type="number" id="gcash-<?php echo $row['branch_id']?>" value="<?php echo $row['branch_gcash_no']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Contact #: </label>
                                                                            <input class="form-control" type="number" id="contact-<?php echo $row['branch_id']?>" value="<?php echo $row['contact_no']; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer float-right">
                                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn-edit-branch1" onclick="editBranch(<?php echo $row['branch_id']?>)">Save changes</button>
                                                    <script>
                                                        function editBranch(id){
                                                            if($('#loc-'+id).val() == '' || $('#gcash-'+id).val() == '' || $('#contact-'+id).val() == ''){
                                                                alert('Fields should not leave empty.');
                                                                $('#btn-edit-branch1').removeAttr('data-dismiss');
                                                            
                                                            }
                                                            else if($('#contact-'+id).val().length != 11 ||  $('#gcash-'+id).val().length != 11){
                                                                alert('Invalid Phone Number');
                                                            }
                                                            else{
                                                                form = new FormData();
                                                                form.append('loc', $('#loc-'+id).val());
                                                                form.append('gcash', $('#gcash-'+id).val());
                                                                form.append('contact', $('#contact-'+id).val());
                                                                form.append('id', id);
                                                                $.ajax({
                                                                    url: "dentist-edit-branch.php",
                                                                    type: "POST",
                                                                    data: form,
                                                                    processData: false,
                                                                    contentType: false,
                                                                }).done( function(data){
                                                                    $('#dentist-edit-branch-modal'+id).modal('hide');
                                                                    $('.modal-backdrop').remove();
                                                                    addDentistBranch();
                                                                    alert(data)
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
                                              <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#dentist-delete-branch-modal<?php echo $row['branch_id']?>"><span class="fa fa-trash fa-fw"></span>
                                              </button>

                                                <!-- Delete Assistant -->
                                                <div class="modal fade" id="dentist-delete-branch-modal<?php echo $row['branch_id']?>" data-keyboard="false" data-backdrop="static">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h4 class="modal-title">Delete Branch Informaton</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body">
                                                            <p>Do you want to continue? This action cannot be undone.&hellip;</p>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="deleteBranch(<?php echo $row['branch_id'] ?>)">Continue</button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                            <script>
                                                                function deleteBranch(id){
                                                                    $.ajax({
                                                                        url: 'dentist-delete-branch.php',
                                                                        method: 'post',
                                                                        data: 'id='+id,
                                                                    }).done( function(data){
                                                                        alert(data);
                                                                        addDentistBranch();
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
                                <th>Location</th>
                                <th>Gcash #</th>
                                <th>Contact #</th>
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
            $("#dentist-view-branch-table").DataTable({
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