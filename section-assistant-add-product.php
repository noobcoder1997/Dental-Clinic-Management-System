<?php
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
            <h4 class="">Add Products</h4>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                    <div class="card-header">
                        <div class="row float-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#assistant-add-product-modal">Add Product</button>
                            <!-- Add Modal -->
                            <div class="modal fade" id="assistant-add-product-modal" data-keyboard="false" data-backdrop="static">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title">Add Product</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="a-product-name">Product: </label>
                                                    <input class="form-control" type="text" name="" id="a-product-name">
                                                </div>
                                                <div class="form-group">
                                                    <label for="a-product-price">Price: </label>
                                                    <input class="form-control" type="number" name="" id="a-product-price">
                                                </div>
                                                <div class="form-group">
                                                    <label for="a-product-desc">Description: </label>
                                                    <textarea class="form-control" rows="8" name="" id="a-product-desc" style="resize:none"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer float-right">
                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                    <button type="button" class="btn btn-primary" onclick="addProduct()" id="btn-add-prod" data-dismiss="modal">Add Product</button>
                                    <script>
                                        function addProduct(){
                                            if($('#a-product-name').val() == '' || $('#a-product-price').val() == '' || $('#a-product-desc').val() == ''){
                                                alert('Fields should not leave empty.');
                                                $('#btn-add-prod').removeAttr('data-dismiss');
                                            }else{
                                                form = new FormData();
                                                form.append('name', $('#a-product-name').val());
                                                form.append('price', $('#a-product-price').val());
                                                form.append('description', $('#a-product-desc').val());
                                                $.ajax({
                                                    url: "assistant-add-products.php",
                                                    type: "POST",
                                                    data: form,
                                                    processData: false,
                                                    contentType: false,
                                                }).done( function(data){
                                                    $("#assistant-add-product-modal").modal('hide');
                                                    $('.modal-backdrop').remove();
                                                    assistantAddProduct();
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
                        <table id="assistant-view-products-table" class="table table-borderless table-hover">
                            <thead>
                            <tr>
                            <th style="width: 30px">#</th>
                            <th></th>
                            <th>Product</th>
                            <th>Price</th>
                            <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no=1;
                            $status = '1';
                            $stmt = $conn->prepare("SELECT * FROM product WHERE branch_id = ? ");
                            $stmt->bind_param('s',$designation);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td>
                                            <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#assistant-view-product-modal<?php echo $row['product_id']?>"><span class="fa fa-eye fa-fw"></span></button>
                                                <!-- View Modal -->
                                            <div class="modal fade" id="assistant-view-product-modal<?php echo $row['product_id']?>" data-keyboard="false" data-backdrop="static">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">View Product Information</h4>
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
                                                                            <label for="">Product: </label>
                                                                            <input readonly class="form-control" type="text" value="<?php echo $row['product_name']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Price: </label>
                                                                            <input readonly class="form-control" type="number" value="<?php echo $row['product_price']; ?>">
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

                                            <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#assistant-transfer-product-modal<?php echo $row['product_id']?>"><span class="fa fa-sync fa-fw"></span></button>
                                                <!-- View Modal -->
                                            <div class="modal fade" id="assistant-transfer-product-modal<?php echo $row['product_id']?>" data-keyboard="false" data-backdrop="static">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">Transfer Product</h4>
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
                                                                            <label for="">Product: </label>
                                                                            <input readonly class="form-control" type="text" value="<?php echo $row['product_name']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Transfer Quantity: </label>
                                                                            <input class="form-control" type="number" value="" id="transfer_qty<?php echo $row['product_id']; ?>" min="1" step="1" oninput="this.value = Math.floor(this.value);" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Transfer to: </label>
                                                                            <select class="form-control" name="" id="transfer_branch<?php echo $row['product_id']; ?>">
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
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="transferProduct(<?php echo $row['product_id']; ?>)">Save Changes</button>
                                                    <script>
                                                        function transferProduct(id){
                                                            if($('#transfer_qty'+id).val() == '' || $('#transfer_branch'+id).val() == ''){
                                                                alert('Fields should not leave empty.');
                                                                $(".btn-transfer-product").removeAttr("data-dismiss");
                                                            }else{
                                                                form = new FormData();
                                                                form.append('qty', $('#transfer_qty'+id).val());
                                                                form.append('branch', $('#transfer_branch'+id).val());
                                                                form.append('id', id);
                                                                $.ajax({
                                                                    url: "assistant-transfer-product.php",
                                                                    type: "POST",
                                                                    data: form,
                                                                    processData: false,
                                                                    contentType: false,
                                                                }).done( function(data){
                                                                    $('#assistant-transfer-product-modal'+id).modal('hide');
                                                                    $('.modal-backdrop').remove();
                                                                    assistantAddProduct();
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
                                        </td>
                                        <td><?php echo $row['product_name']; ?></td>
                                        <td><?php echo $row['product_price']; ?></td>
                                        <td>
                                            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assistant-edit-product-modal<?php echo $row['product_id']?>"><span class="fa fa-edit fa-fw"></span>
                                            </button>

                                        <!-- Edit Modal -->
                                            <div class="modal fade" id="assistant-edit-product-modal<?php echo $row['product_id']?>" data-keyboard="false" data-backdrop="static">
                                            <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h4 class="modal-title">Edit Product Information</h4>
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
                                                                        <label for="">Product: </label>
                                                                        <input class="form-control" type="text" id="a-prod-nim-<?php echo $row['product_id']?>" value="<?php echo $row['product_name']; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="">Price: </label>
                                                                        <input class="form-control" type="number" id="a-prod-price-<?php echo $row['product_id']?>" value="<?php echo $row['product_price']; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="">Description: </label>
                                                                        <textarea class="form-control" rows="8" name="" id="a-prod-desc-<?php echo $row['product_id']?>" style="resize:none"><?php echo $row['description']; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer float-right">
                                                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="editAssistantProducts(<?php echo $row['product_id']?>)" data-dismiss="modal" id="btn-edit-prod">Save changes</button>
                                                <script>
                                                    function editAssistantProducts(id){
                                                        if($('#a-prod-nim'+id).val() == '' || $('#a-prod-price'+id).val() == '' || $('#a-prod-desc'+id).val() == ''){
                                                            alert('Fields should not leave empty.');
                                                            $("#btn-edit-prod").removeAttr('data-dismiss');
                                                        }else{
                                                            form = new FormData();
                                                            form.append('name', $('#a-prod-nim-'+id).val());
                                                            form.append('price', $('#a-prod-price-'+id).val());
                                                            form.append('description', $('#a-prod-desc-'+id).val());
                                                            form.append('id', id);
                                                            $.ajax({
                                                                url: "assistant-edit-products.php",
                                                                type: "POST",
                                                                data: form,
                                                                processData: false,
                                                                contentType: false,
                                                            }).done( function(data){
                                                                $('#assistant-edit-product-modal'+id).modal('hide');
                                                                $('.modal-backdrop').remove();
                                                                assistantAddProduct();
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
                                            <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#assistant-delete-product-modal<?php echo $row['product_id']?>"><span class="fa fa-trash fa-fw"></span>
                                            </button>

                                            <!-- Delete Assistant -->
                                            <div class="modal fade" id="assistant-delete-product-modal<?php echo $row['product_id']?>" data-keyboard="false" data-backdrop="static">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h4 class="modal-title">Delete Product Informaton</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <p>Do you want to continue? This action cannot be undone.&hellip;</p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="deleteAssistantProduct(<?php echo $row['product_id'] ?>)">Continue</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                        <script>
                                                            function deleteAssistantProduct(id){
                                                                $.ajax({
                                                                    url: 'assistant-delete-product.php',
                                                                    method: 'post',
                                                                    data: 'id='+id,
                                                                }).done( function(data){
                                                                    alert(data);
                                                                    assistantAddProduct(); 
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
                            <th>Product</th>
                            <th>Price</th>
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
            $("#assistant-view-products-table").DataTable({
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