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
                <h4 class="">Add Products</h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card">
                        <div class="card-header">
                            <div class="row float-right">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#dentist-add-product-modal">Add Product</button>
                                <!-- Add Modal -->
                                <div class="modal fade" id="dentist-add-product-modal" data-keyboard="false" data-backdrop="static">
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
                                                        <label for="product-name">Product: </label>
                                                        <input class="form-control" type="text" name="" id="product-name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="product-price">Price: </label>
                                                        <input class="form-control" type="number" name="" id="product-price">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="product-desc">Description: </label>
                                                        <textarea class="form-control" rows="8" name="" id="product-desc" style="resize:none"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer float-right">
                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addProduct()" id="addPRoduct">Add Product</button>
                                        <script>
                                            function addProduct(){
                                                if($('#product-name').val() == '' || $('#product-price').val() == '' || $('#product-desc').val() == ''){
                                                    alert('Fields should not leave empty.');
                                                    $("#addPRoduct").removeAttr("data-dismiss");
                                                }else{
                                                    form = new FormData();
                                                    form.append('name', $('#product-name').val());
                                                    form.append('price', $('#product-price').val());
                                                    form.append('description', $('#product-desc').val());
                                                    $.ajax({
                                                        url: "dentist-add-products.php",
                                                        type: "POST",
                                                        data: form,
                                                        processData: false,
                                                        contentType: false,
                                                    }).done( function(data){
                                                        $("#dentist-add-product-modal").modal('toggle');
                                                        $(".modal-backdrop").remove();
                                                        alert(data);
                                                    })
                                                    addDentistProduct();
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
                            <table id="dentist-view-products-table" class="table table-borderless table-hover">
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
                                $stmt = $conn->prepare("SELECT * FROM product WHERE branch_id = ?");
                                $stmt->bind_param("s",$branch);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                                <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#dentist-view-product-modal<?php echo $row['product_id']?>"><span class="fa fa-eye fa-fw"></span></button>
                                                 <!-- View Modal -->
                                                <div class="modal fade" id="dentist-view-product-modal<?php echo $row['product_id']?>" data-keyboard="false" data-backdrop="static">
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
                                                                                <input readonly class="form-control" type="text" id="v-prod-nim-<?php echo $row['product_id']?>" value="<?php echo $row['product_name']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="">Price: </label>
                                                                                <input readonly class="form-control" type="number" id="v-prod-price-<?php echo $row['product_id']?>" value="<?php echo $row['product_price']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="">Description: </label>
                                                                                <textarea readonly class="form-control" rows="8" name="" id="v-prod-desc-<?php echo $row['product_id']?>" style="resize:none"><?php echo $row['description']; ?></textarea>
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
                                            <td><?php echo $row['product_name']; ?></td>
                                            <td><?php echo $row['product_price']; ?></td>
                                            <td>
                                              <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#dentist-edit-product-modal<?php echo $row['product_id']?>"><span class="fa fa-edit fa-fw"></span>
                                              </button>

                                            <!-- Edit Modal -->
                                              <div class="modal fade" id="dentist-edit-product-modal<?php echo $row['product_id']?>" data-keyboard="false" data-backdrop="static">
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
                                                                            <input class="form-control" type="text" id="prod-nim-<?php echo $row['product_id']?>" value="<?php echo $row['product_name']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Price: </label>
                                                                            <input class="form-control" type="number" id="prod-price-<?php echo $row['product_id']?>" value="<?php echo $row['product_price']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Description: </label>
                                                                            <textarea class="form-control" rows="8" name="" id="prod-desc-<?php echo $row['product_id']?>" style="resize:none"><?php echo $row['description']; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer float-right">
                                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="editProducts(<?php echo $row['product_id']?>)" id="editPRoduct">Save changes</button>
                                                    <script>
                                                        function editProducts(id){
                                                            if($('#prod-nim-'+id).val() == '' || $('#prod-price-'+id).val() == '' || $('#prod-desc-'+id).val() == ''){
                                                                alert('Fields should not leave empty.');
                                                                $("#editPRoduct").removeAttr("data-dismiss");
                                                            }else{
                                                                form = new FormData();
                                                                form.append('name', $('#prod-nim-'+id).val());
                                                                form.append('price', $('#prod-price-'+id).val());
                                                                form.append('description', $('#prod-desc-'+id).val());
                                                                form.append('id', id);
                                                                $.ajax({
                                                                    url: "dentist-edit-products.php",
                                                                    type: "POST",
                                                                    data: form,
                                                                    processData: false,
                                                                    contentType: false,
                                                                }).done( function(data){
                                                                    $("#dentist-add-product-modal").modal('toggle');
                                                                    $(".modal-backdrop").remove();
                                                                    alert(data);
                                                                })
                                                                addDentistProduct();
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
                                              <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#dentist-delete-product-modal<?php echo $row['product_id']?>"><span class="fa fa-trash fa-fw"></span>
                                              </button>

                                              <!-- Delete Assistant -->
                                                <div class="modal fade" id="dentist-delete-product-modal<?php echo $row['product_id']?>" data-keyboard="false" data-backdrop="static">
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
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="deleteProduct(<?php echo $row['product_id'] ?>)">Continue</button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                            <script>
                                                                function deleteProduct(id){
                                                                    $.ajax({
                                                                        url: 'dentist-delete-product.php',
                                                                        method: 'post',
                                                                        data: 'id='+id,
                                                                    }).done( function(data){
                                                                        alert(data);
                                                                        addDentistProduct(); 
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
            $("#dentist-view-products-table").DataTable({
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