<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    $designation = $_SESSION['designation'];
    $branches = array();
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="m-0">Add Branches</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                        <div class="card-header">                                           
                                            <div class="row float-right">
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#assistant-add-branch-modal">Add Branch</button>
                                                <!-- Assistant add branch -->
                                                <div class="modal fade" id="assistant-add-branch-modal" data-keyboard="false" data-backdrop="static">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Add Branch</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                <label for="">Branch Location: </label><br>
                                                                <input class="form-control" type="text" name="" id="b-loc"><br>
                                                                <label for="">Branch Contact Number: </label><br>
                                                                <input class="form-control" type="number" name="" id="b-cno"><br>
                                                                <label for="">Branch Gcash Number: </label><br>
                                                                <input class="form-control" type="number" name="" id="b-gno"><br>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                            <button type="button" class="btn btn-primary" id="btn-add-branch" onclick="addBranchAssistant()"  data-dismiss="modal">Add Branch</button>
                                                            <script>
                                                                function addBranchAssistant(){
                                                                    b_loc = $('#b-loc').val();
                                                                    b_cno = $('#b-cno').val();
                                                                    b_gno = $('#b-gno').val();
                                                                    if((b_loc) == '' || (b_cno) == '' || (b_gno) == ''){
                                                                        alert('Empty fields should not leave empty!');
                                                                        $('#btn-add-branch').removeAttr('data-dismiss');
                                                                    }
                                                                    else if(b_cno.length != 11 || b_gno.length != 11){
                                                                        alert('Invalid Phone Number');
                                                                    }
                                                                    else{
                                                                        data = 'location='+b_loc+'&gcashno='+b_gno+'&contactno='+b_cno;
                                                                        $.ajax({
                                                                            data: data,
                                                                            method: "POST",
                                                                            url: "assistant-add-branch.php",
                                                                            }).done(function(data){
                                                                            $('#assistant-add-branch-modal').modal('hide');
                                                                            $('.modal-backdrop').remove();
                                                                            assistantAddBranch();
                                                                            alert(data)
                                                                        });
                                                                    }
                                                                }
                                                            </script>
                                                        </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="assistant-view-branches-table" class="table table-borderless table-hover">
                                            <thead>
                                            <tr>
                                                <th style="width: 35px">#</th>
                                                <th></th>
                                                <th>Location</th>
                                                <th>Contact #</th>
                                                <th>GCash #</th>
                                                <th></th>
                                                <!-- <th></th> -->
                                            </tr>
                                            </thead>
                                            <?php
                                                $no=1;
                                                $stmt=$conn->prepare('SELECT * FROM branch');
                                                // $stmt->bind_param("s", $designation);
                                                $stmt->execute();
                                                $result=$stmt->get_result();
                                                ?>
                                                    <tbody>
                                                <?php
                                                while($row=$result->fetch_Array(MYSQLI_ASSOC)){
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $no; ?></td>
                                                            <td>
                                                                <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#assistant-view-branch-modal<?php echo $row['branch_id']?>"><span class="fa fa-eye fa-fw"></span></button>
                                                                <!-- View Modal -->
                                                                <div class="modal fade" id="assistant-view-branch-modal<?php echo $row['branch_id']?>" data-keyboard="false" data-backdrop="static">
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
                                                                                                <input readonly class="form-control" type="number" name="" value="<?php echo $row['contact_no']; ?>">
                                                                                            </div>
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
                                                            <td><?php echo $row['location']; ?></td>
                                                            <td><?php echo $row['branch_gcash_no']; ?></td>
                                                            <td><?php echo $row['contact_no']; ?></td>
                                                            <td>
                                                                <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assistant-edit-branch-modal<?php echo $row['branch_id']?>"><span class="fa fa-edit fa-fw"></span>
                                                                </button>
                                                                <!-- Edit Modal -->
                                                                <div class="modal fade" id="assistant-edit-branch-modal<?php echo $row['branch_id']?>" data-keyboard="false" data-backdrop="static">
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
                                                                                                <input class="form-control" type="text" id="branch-nim-<?php echo $row['branch_id']?>" value="<?php echo $row['location']; ?>">
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
                                                                                                <input class="form-control" rows="8" name="" value="<?php echo $row['contact_no']; ?>" id="contact-<?php echo $row['branch_id']?>" type="number">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer float-right">
                                                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                                        <button type="button" class="btn btn-primary" onclick="editBranch(<?php echo $row['branch_id']?>)" id="btn-edit-branch" data-dismiss="modal">Save changes</button>
                                                                        <script>
                                                                            function editBranch(id){
                                                                                if($('#branch-nim'+id).val() == '' || $('#branch-'+id).val() == '' || $('#contact-'+id).val() == ''){
                                                                                    alert('Fields should not leave empty.');
                                                                                    $('#btn-edit-branch').removeAttr('data-dismiss');
                                                                                }
                                                                                else if($('#contact-'+id).val().length < 11 || $('#gcash-'+id).val().length < 11){
                                                                                    alert('Invalid Phone Number');
                                                                                }
                                                                                else{
                                                                                    form = new FormData();
                                                                                    form.append('loc', $('#branch-nim-'+id).val());
                                                                                    form.append('gcash', $('#gcash-'+id).val());
                                                                                    form.append('contact', $('#contact-'+id).val());
                                                                                    form.append('id', id);
                                                                                    $.ajax({
                                                                                        url: "assistant-edit-branch.php",
                                                                                        type: "POST",
                                                                                        data: form,
                                                                                        processData: false,
                                                                                        contentType: false,
                                                                                    }).done( function(data){
                                                                                        $('#assistant-edit-branch-modal'+id).modal('hide');
                                                                                        $('.modal-backdrop').remove();
                                                                                        assistantAddBranch();
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
                                                                <!-- /.modal -->
                                                                 
                                                                <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#assistant-delete-branch-modal<?php echo $row['branch_id']?>"><span class="fa fa-trash fa-fw"></span>
                                                                </button>
                                                                <!-- Delete Assistant -->
                                                                <div class="modal fade" id="assistant-delete-branch-modal<?php echo $row['branch_id']?>" data-keyboard="false" data-backdrop="static">
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
                                                                                        url: 'assistant-delete-branch.php',
                                                                                        method: 'post',
                                                                                        data: 'id='+id,
                                                                                    }).done( function(data){
                                                                                        alert(data);  
                                                                                        $('#assistant-delete-branch-modal'+id).modal('hide');
                                                                                        $('.modal-backdrop').remove();
                                                                                        assistantAddBranch();
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
                                                <?php
                                            ?>
                                            <tfoot>
                                            <tr>
                                                <th style="width: 35px">#</th>
                                                <th></th>
                                                <th>Location</th>
                                                <th>Contact #</th>
                                                <th>GCash #</th>
                                                <th></th>
                                                <!-- <th></th> -->
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
            $("#assistant-view-branches-table").DataTable({
            "responsive": true,
            "lengthChange": true, 
            "autoWidth": true,
            "pageLength": 50,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "scrollX": true,
            "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            });
        });
  })
</script>