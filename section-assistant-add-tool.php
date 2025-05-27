<?php

use PhpOffice\PhpSpreadsheet\Shared\OLE\PPS;

    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    $designation = $_SESSION['designation'];
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- alert notifications -->
            <?php 
                $query = mysqli_query($conn, "SELECT * FROM notifications WHERE status = 1 AND position = 0 AND patient_id = '$designation' ");
                while($row = mysqli_fetch_assoc($query)){
            ?>
            <div class="alert alert-warning alert-dismissible fade show my-3" data-toggle="collapse" data-target=".collapse<?php echo $row['id']; ?>" role="alert">
                Notification
                
                <button type="button" class="close notif-close<?php echo $row['id'];?>" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="collapse collapse<?php echo $row['id']; ?>">
                    <div class="card mx-auto" style="background-color:transparent; width:100%">
                        <div class="card-body">
                            <?php
                                    echo $row['message'];

                                if($row['showBtn'] == 1){
                            ?>
                                    <br>
                                    <br>
                                    Click receive if you have received the package.
                                    <button class="btn btn-info btn-sm btn-receive<?php echo $row['id'];?>"><span  class="fa fa-check fa-fw"></span> Recieved</button>
                                    <script>
                                        $('.btn-receive<?php echo $row['id'];?>').on('click', function(){
                                            form.append('id', <?php echo $row['id']; ?>);
                                            form.append('branch', <?php echo $designation; ?>);
                                            form.append('sendto', <?php echo $row['_from']; ?>);
                                            $.ajax({
                                                data: form,
                                                type: 'POST',
                                                url: "assistant-receive-transfer.php",
                                                processData: false,
                                                contentType: false,
                                                cache: false,
                                                success:(data)=>{
                                                    alert(data)
                                                }
                                            });
                                        })
                                    </script>
                            <?php
                                }
                                else{
                                    ?>
                                    <script>
                                        $('.notif-close<?php echo $row['id'];?>').on('click', function(){
                                            $.ajax({
                                                data: 'id='+<?php echo $row['id']; ?>,
                                                type: 'post',
                                                url: 'remove-asst-notif.php',
                                                success: (data)=>{
                                                }
                                            })
                                        });                                        
                                    </script>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div> 
            <?php
                }
            ?>
            <!-- alert notifications -->
        <div class="card mt-3">
            <div class="card-header">
            <!-- <h3 class="card-title">Dental Clinic Branch</h3> -->
            <h4 class="">Add Tools</h4>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                    <div class="card-header">
                        <div class="row float-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#assistant-add-tool-modal">Add Tool</button>
                            <!-- Add Modal -->
                            <div class="modal fade" id="assistant-add-tool-modal" data-keyboard="false" data-backdrop="static">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title">Add Tool</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="tool-name">Tool: </label>
                                                    <input class="form-control" type="text" name="" id="as-tool-name">
                                                </div>
                                                <div class="form-group">
                                                    <label for="tool-desc">Description: </label>
                                                    <textarea class="form-control" rows="8" name="" id="as-tool-desc" style="resize:none"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer float-right">
                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                    <button type="button" class="btn btn-primary" id="btn-add-tool" data-dismiss="modal" onclick="addTool()">Add tool</button>
                                    <script>
                                        function addTool(){
                                            if($('#as-tool-name').val() == '' || $('#as-tool-desc').val() == '' || $('#as-tool-qty').val() == ''){
                                                alert('Fields should not leave empty.');
                                                $("#btn-add-tool").removeAttr('data-dismiss');w
                                            }else{
                                                form = new FormData();
                                                form.append('name', $('#as-tool-name').val());
                                                form.append('description', $('#as-tool-desc').val());
                                                $.ajax({
                                                    url: "assistant-add-tools.php",
                                                    type: "POST",
                                                    data: form,
                                                    processData: false,
                                                    contentType: false,
                                                }).done( function(data){
                                                    $('#assistant-add-tool-modal').modal('hide');
                                                    $('.modal-backdrop').remove();
                                                    assistantAddTools();
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
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="assistant-view-tools-table" class="table table-borderless table-hover">
                            <thead>
                            <tr>
                            <th style="width: 30px">#</th>
                            <th></th>
                            <th>Tool</th>
                            <th>Description</th>
                            <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no=1;
                            $stmt = $conn->prepare("SELECT * FROM tools WHERE branch_id = ?");
                            $stmt->bind_param('s',$designation);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td>
                                            <button class="btn btn-success btn-xs" data-target="#assistant-view-tool-modal<?php echo $row['tool_id']?>" data-toggle="modal"><span class="fa fa-eye fa-fw"></span></button>
                                            <!-- View Modal -->
                                            <div class="modal fade" id="assistant-view-tool-modal<?php echo $row['tool_id']?>" data-keyboard="false" data-backdrop="static">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h4 class="modal-title">View Tool Information</h4>
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
                                                                                <label for="">Tool: </label>
                                                                                <input readonly class="form-control" type="text" value="<?php echo $row['tool_name']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="">Description: </label>
                                                                                <textarea readonly class="form-control" rows="8" name=""  style="resize:none"><?php echo $row['description']; ?></textarea>
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

                                            <button class="btn btn-warning btn-xs" data-target="#assistant-transfer-tool-modal<?php echo $row['tool_id']?>" data-toggle="modal"><span class="fa fa-sync fa-fw"></span></button>
                                            <!-- View Modal -->
                                            <div class="modal fade" id="assistant-transfer-tool-modal<?php echo $row['tool_id']?>" data-keyboard="false" data-backdrop="static">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h4 class="modal-title">Transfer a Tool</h4>
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
                                                                                <label for="">Tool: </label>
                                                                                <input readonly class="form-control" type="text" value="<?php echo $row['tool_name']; ?>">
                                                                                <input readonly class="form-control" type="hidden" value="<?php echo $row['description']; ?>" id="descp<?php echo $row['tool_id']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="">Transfer Quantity: </label>
                                                                                <input class="form-control" type="number" value="" id="transfer_qty<?php echo $row['tool_id']; ?>" min="1" step="1" oninput="this.value = Math.floor(this.value);">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="">Transfer to: </label>
                                                                                <select name="" id="transfer_branch<?php echo $row['tool_id']; ?>" class="form-control">
                                                                                    <?php
                                                                                        $_query = "SELECT * FROM branch WHERE branch_id <> ?";
                                                                                        $sttm = $conn->prepare($_query);
                                                                                        $sttm->bind_param('s', $designation);
                                                                                        $sttm->execute();
                                                                                        $result0=$sttm->get_result();

                                                                                        while($row0=$result0->fetch_assoc()){
                                                                                            ?>
                                                                                                <option value="<?php echo $row0['branch_id']; ?>"><?php echo $row0['location']; ?></option>
                                                                                            <?php    
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer float-right">
                                                            <button type="button" class="btn btn-secondary btn-transfer-tool" data-dismiss="modal" onclick="transferTool(<?php echo $row['tool_id']; ?>)">Save changes</button>
                                                        </div>
                                                        <script>
                                                            function transferTool(id){
                                                                if($('#transfer_qty'+id).val() == '' || $('#transfer_branch'+id).val() == ''){
                                                                    alert('Fields should not leave empty.');
                                                                    $(".btn-transfer-tool").removeAttr("data-dismiss");
                                                                }else{
                                                                    form = new FormData();
                                                                    form.append('qty', $('#transfer_qty'+id).val());
                                                                    form.append('branch', $('#transfer_branch'+id).val());
                                                                    form.append('id', id);
                                                                    $.ajax({
                                                                        url: "assistant-transfer-tools.php",
                                                                        type: "POST",
                                                                        data: form,
                                                                        processData: false,
                                                                        contentType: false,
                                                                    }).done( function(data){
                                                                        $('#assistant-transfer-tool-modal'+id).modal('hide');
                                                                        $('.modal-backdrop').remove();
                                                                        assistantAddTools();
                                                                        alert(data);
                                                                    })
                                                                }
                                                            }
                                                        </script>
                                                    </div>
                                                <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.modal -->
                                        </td>
                                        <td><?php echo $row['tool_name']; ?></td>
                                        <td><?php echo $row['description']; ?></td>
                                        <td>
                                            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assistant-edit-tool-modal<?php echo $row['tool_id']?>"><span class="fa fa-edit fa-fw"></span>
                                            </button>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="assistant-edit-tool-modal<?php echo $row['tool_id']?>" data-keyboard="false" data-backdrop="static">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">Edit Tool Information</h4>
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
                                                                            <label for="">Tool: </label>
                                                                            <input class="form-control" type="text" id="a-tool-nim-<?php echo $row['tool_id']?>" value="<?php echo $row['tool_name']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Description: </label>
                                                                            <textarea class="form-control" rows="8" name="" id="a-tool-desc-<?php echo $row['tool_id']?>" style="resize:none"><?php echo $row['description']; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer float-right">
                                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                    <button type="button" class="btn btn-primary" id="btn-edit-tool" data-dismiss="modal" onclick="editTools(<?php echo $row['tool_id']?>)">Save changes</button>
                                                    <script>
                                                        function editTools(id){
                                                            if($('#a-tool-nim'+id).val() == '' || $('#a-tool-desc'+id).val() == ''){
                                                                alert('Fields should not leave empty.');
                                                                $("#btn-edit-tool").removeAttr("data-dismiss");
                                                            }else{
                                                                form = new FormData();
                                                                form.append('name', $('#a-tool-nim-'+id).val());
                                                                form.append('description', $('#a-tool-desc-'+id).val());
                                                                form.append('id', id);
                                                                $.ajax({
                                                                    url: "assistant-edit-tools.php",
                                                                    type: "POST",
                                                                    data: form,
                                                                    processData: false,
                                                                    contentType: false,
                                                                }).done( function(data){
                                                                    $('#assistant-edit-tool-modal'+id).modal('hide');
                                                                    $('.modal-backdrop').remove();
                                                                    assistantAddTools();
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
                                            
                                            <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#assistant-delete-tool-modal<?php echo $row['tool_id']?>"><span class="fa fa-trash fa-fw"></span>
                                            </button>

                                            <!-- Delete Assistant -->
                                            <div class="modal fade" id="assistant-delete-tool-modal<?php echo $row['tool_id']?>" data-keyboard="false" data-backdrop="static">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h4 class="modal-title">Delete Tool Informaton</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <p>Do you want to continue? This action cannot be undone.&hellip;</p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="deleteTool(<?php echo $row['tool_id'] ?>)">Continue</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                        <script>
                                                            function deleteTool(id){
                                                                $.ajax({
                                                                    url: 'assistant-delete-tool.php',
                                                                    method: 'post',
                                                                    data: 'id='+id,
                                                                }).done( function(data){
                                                                    alert(data);  
                                                                    assistantAddTools(); 
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
                            <th>Tool</th>
                            <th>Description</th>
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
            $("#assistant-view-tools-table").DataTable({
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