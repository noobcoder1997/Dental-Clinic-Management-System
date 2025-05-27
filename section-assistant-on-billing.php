  <?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
        
    if(!isset($_SESSION['designation'])){
      $designation = $_SESSION['branch'];
    }
    else{
        $designation = $_SESSION['designation'];
    }
    $branches = array();
  ?>
  <section class="content">
      <div class="container-fluid">
          <div class="row">
              <div class="col-sm-12">
                  <div class="card mt-3">
                      <div class="card-header">
                          <h4 class="m-0">Billing</h4>
                      </div>
                      <div class="card-body">
                          <div class="row">
                              <div class="col-sm-12">
                                  <div class="card">
                                          <div class="card-header">                                           
                                              <div class="row float-right">
                                              </div>
                                          </div>
                                          <!-- /.card-header -->
                                          <div class="card-body">
                                              <table id="assistant-view-billing-table" class="table table-borderless table-hover">
                                              <thead>
                                              <tr>
                                                  <th style="width: 35px">#</th>
                                                  <th></th>
                                                  <th>Patient</th>
                                                  <!-- <th>Contact #</th>
                                                  <th>GCash #</th> -->
                                                  <th>Date</th>
                                                  <!-- <th></th> -->
                                              </tr>
                                              </thead>
                                              <?php
                                                  $tooth_numbers = [
                                                    '18','17','16','15','14','13','12','11','21','22','23','24','25','26','27','28',
                                                    '48','47','46','45','44','43','42','41','31','32','33','34','35','36','37','38',
                                                    '55','54','53','52','51','61','62','63','64','65',
                                                    '85','84','83','82','81','71','72','73','74','75'];
                                                  $no=1;
                                                  $status = '0';
                                                  $patient='';
                                                  // $stmt=$conn->prepare('SELECT * FROM billing ');
                                                  $stmt=$conn->prepare('SELECT * FROM billing WHERE status = ? AND branch_id = ? ORDER BY billing_id ASC');
                                                  $stmt->bind_param("ss",$status,$designation);
                                                  $stmt->execute();
                                                  $result=$stmt->get_result();
                                                  ?>
                                                      <tbody>
                                                  <?php
                                                  while($row=$result->fetch_Array(MYSQLI_ASSOC)){
                                                    $stmt0=$conn->prepare("SELECT * FROM register_patient WHERE register_id = ?");
                                                    $stmt0->bind_param("s",$row['patient_id']);
                                                    $stmt0->execute();
                                                    $result0=$stmt0->get_result();
                                                    if($row0=$result0->fetch_assoc()){
                                                      $patient = strtoupper($row0['first_name']." ".$row0['middle_name']." ".$row0['last_name']);
                                                    }
                                                      ?>
                                                          <tr>
                                                              <td><?php echo $no; ?></td>
                                                              <td>
                                                                <button type="button" class="btn btn-success btn-xs" onclick="open_modal(<?php echo $row['billing_id']; ?>)">
                                                                  <span class="fa fa-eye fa-fw"></span>
                                                                </button>
                                                                <div class="modal fade" id="assistant-view-bill-modal<?php echo $row['billing_id']; ?>">
                                                                  <div class="modal-dialog modal-dialog-scrollable">
                                                                    <div class="modal-content">
                                                                      <div class="modal-header">
                                                                        <h4 class="modal-title">BILL FOR <?php echo $patient ?></h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="_back()">
                                                                          <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                      </div>
                                                                      <div class="modal-body">
                                                                        <div class="row">
                                                                          <div class="col-sm-12">
                                                                            <label for="">Patient Name:</label>
                                                                            <input type="text" name="" id="" class="form-control" value="<?php echo $patient; ?>" readonly> 
                                                                            <br>  
                                                                          </div> 
                                                                          
                                                                          <div class="col-sm-12">
                                                                                <label for="">Tooth worked on:</label>
                                                                                <select name="" id="tooth_worked<?php echo $row['billing_id']; ?>" class="form-control">
                                                                                  <option value=""></option>
                                                                                  <?php
                                                                                  $index=0;
                                                                                  while($index < count($tooth_numbers)){
                                                                                    echo "<option value='$tooth_numbers[$index]'>Tooth #$tooth_numbers[$index]</option>";
                                                                                    $index++;
                                                                                  }
                                                                                  ?>
                                                                                </select><br> 
                                                                              </div>                                                                       
                                                                        </div>
                                                                        <h5>Services Rendered</h5>
                                                                        <div class="row">
                                                                          <div class="col-sm-12">
                                                                            <div class="row">
                                                                              <div class="col-sm-12">
                                                                                <label for="">Services Applied:</label>
                                                                                <select name="" id="service_applied" class="form-control">
                                                                                  <option value=""></option>
                                                                                  <?php 
                                                                                    $stmt1=$conn->prepare("SELECT * FROM services WHERE branch_id = ? ");
                                                                                    $stmt1->bind_param('s',$designation);
                                                                                    $stmt1->execute();
                                                                                    $result1=$stmt1->get_result();
                                                                                    while($row1=$result1->fetch_assoc()){
                                                                                        echo '<option value='.$row1['service_id'].'
                                                                                        >'.$row1['service_name'].'</option>';
                                                                                    }
                                                                                  ?>
                                                                                </select>
                                                                                <br>
                                                                              </div>
                                                                              <div class="col-sm-12">
                                                                                <label for="">Price</label>
                                                                                <input type="number" name="" id="service_price<?php echo $row['billing_id']; ?>" class="form-control" onkeyup="if(this.value<0){event.target.value=0;}" min=1 >
                                                                              </div>
                                                                              <div class="col-sm-12">
                                                                                <label for="" hidden>Quantity</label>
                                                                                <input type="hidden" name="" id="service_qty<?php echo $row['billing_id']; ?>" class="form-control" onkeyup="if(this.value<0){this.value=0;}" min=1 value='1'  onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
                                                                              </div>
                                                                              <div class="col-sm-12 ">
                                                                                <br>
                                                                                <!-- <button class="btn btn-success btn-sm" onclick="view_services()">
                                                                                    <span class="fa fa-eye fa-fw"></span>
                                                                                </button> -->
                                                                                <button class="btn btn-primary float-right" onclick="add_service(<?php echo $row['billing_id']; ?>)">
                                                                                    <span class="fa fa-plus fa-fw"></span>
                                                                                </button>
                                                                                <br>
                                                                              </div>
                                                                              <div class="col-sm-12">
                                                                                  <div class="row services"></div>
                                                                                  
                                                                              </div>
                                                                            </div>
                                                                          </div>
                                                                          <div class="col-sm-12">
                                                                            <div class="col-sm-3">
                                                                              
                                                                            </div>
                                                                          </div>
                                                                        </div>
                                                                        <br>
                                                                        <h5>Products Rendered</h5>
                                                                        <div class="row">
                                                                          <div class="col-sm-12">
                                                                            <div class="row">
                                                                              <div class="col-sm-12">
                                                                                <br>
                                                                                <label for="">Stock left</label>
                                                                                <input type="number" class="form-control" name="" id="product_qty" readonly>
                                                                                <br>
                                                                              </div>
                                                                              <div class="col-sm-12">
                                                                                <label for="">Add Products:</label>
                                                                                <select name="" id="product_applied" class="form-control" onchange="selectproduct();disabledOption();">
                                                                                  <option value=""></option>
                                                                                  <?php 
                                                                                    // $product_count=0;
                                                                                    $stmt1=$conn->prepare("SELECT * FROM product WHERE branch_id = ?");
                                                                                    $stmt1->bind_param('s',$designation);
                                                                                    $stmt1->execute();
                                                                                    $result1=$stmt1->get_result();
                                                                                    while($row1=$result1->fetch_assoc()){
                                                                                        echo '<option value='.$row1['product_id'].'
                                                                                        >'.$row1['product_name'].'</option>';
                                                                                    }
                                                                                    $stmt1=$conn->prepare("SELECT * FROM tools WHERE branch_id = ?");
                                                                                    $stmt1->bind_param('s',$designation);
                                                                                    $stmt1->execute();
                                                                                    $result1=$stmt1->get_result();
                                                                                    while($row1=$result1->fetch_assoc()){
                                                                                        echo '<option value='.$row1['tool_id'].'
                                                                                        >'.$row1['tool_name'].'</option>';
                                                                                    }
                                                                                  ?>
                                                                                </select>
                                                                                  <script>
                                                                                    function selectproduct(){
                                                                                      form=new FormData();
                                                                                      form.append("product",$('#product_applied').val());
                                                                                      form.append("product_name",$('#product_applied :selected').text());
                                                                                      $.ajax({
                                                                                          type:"POST",
                                                                                          url:'view-product-qty-billing.php',
                                                                                          data:form,
                                                                                          contentType:false,
                                                                                          processData:false,
                                                                                      }).done( function(data){
                                                                                          $('#product_qty').val(data);
                                                                                      });
                                                                                    }
                                                                                  </script>
                                                                              </div>
                                                                              <div class="col-sm-12">
                                                                                <br>
                                                                                <label for="">Quantity</label>
                                                                                <input type="number" name="" id="product_qty<?php echo $row['billing_id']; ?>" class="form-control" min=1 onkeyup="if(this.value<0){this.value=0;}" value='1'  onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
                                                                              </div>
                                                                              <div class="col-sm-12">
                                                                                <br>
                                                                                <button class="btn btn-primary float-right" onclick="add_product(<?php echo $row['billing_id']; ?>)">
                                                                                    <span class="fa fa-plus fa-fw"></span>
                                                                                </button>
                                                                                <br>
                                                                              </div>
                                                                              <div class="col-sm-12">
                                                                                <!-- <br> -->
                                                                                <br>
                                                                              </div>
                                                                            </div>
                                                                          </div>
                                                                        </div>
                                                                      </div>
                                                                      <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="_back()" >Cancel</button>  
                                                                        <!-- float-right -->
                                                                        <button type="button" class="btn btn-primary"onclick="view_services(<?php echo $row['billing_id']; ?>)">Save & Proceed</button>
                                                                      </div>
                                                                    </div>
                                                                    <!-- /.modal-content -->
                                                                  </div>
                                                                  <!-- /.modal-dialog -->
                                                                </div>
                                                                <!-- /.modal -->
                                                                
                                                                  <script>
                                                                    var $select = $("#product_applied");
                                                                    var selected = [];  

                                                                    function disabledOption(){
                                                                      //   $.each($select, function(index, select) {           
                                                                      //       if (select.value !== "") { selected.push(select.value); }
                                                                      //   });         
                                                                      // $("option").prop("disabled", false);         
                                                                      // for (var index in selected) { 
                                                                      //   $('option[value="'+selected[index]+'"]').prop("disabled", true); 
                                                                      // }
                                                                    }

                                                                    function _back(){
                                                                      // location.reload()
                                                                      product_qty = [];
                                                                      service_qty = [];

                                                                      services = [];
                                                                      products = [];

                                                                      service_prices = [];
                                                                      teeth_worked = [];

                                                                      $('#service_applied').val('');
                                                                      $('#tooth_worked<?php echo $row['billing_id']; ?>').val('');
                                                                      $('#service_price<?php echo $row['billing_id']; ?>').val('');
                                                                      $('#service_qty<?php echo $row['billing_id']; ?>').val('1');

                                                                      $('#product_applied').val('');
                                                                      $('#product_qty<?php echo $row['billing_id']; ?>').val('1');
                                                                    }

                                                                    function add_service(id){
                                                                      if($('#service_applied').val() == '' ||$('#service_price'+id).val() == '' || $('#tooth_worked'+id).val() == ''){
                                                                        alert('Fields should not leave empty!');
                                                                      }
                                                                      else{
                                                                        services.push($('#service_applied :selected').val())
                                                                        service_qty.push(parseInt($('#service_qty'+id).val()))
                                                                        service_prices.push(parseFloat($('#service_price'+id).val()))   
                                                                        teeth_worked.push($('#tooth_worked'+id).val());
                                                                        alert('Service added!');  
                                                                        console.log(services)
                                                                        console.log(service_qty)
                                                                        console.log(service_prices) 
                                                                        console.log(teeth_worked) 
                                                                      }

                                                                    }
                                                                    
                                                                    function add_product(id){
                                                                      if($('#product_qty'+id).val() == '' || $('#product_applied').val() == '' ){
                                                                        alert('Fields should not leave empty!');
                                                                      }
                                                                      else if($('#product_qty'+id).val() > $('#product_qty').val()){
                                                                        alert('You entered more than the quantity of the product!')
                                                                      }
                                                                      else{
                                                                        products.push($('#product_applied :selected').val())
                                                                        product_qty.push(($('#product_qty'+id).val()))
                                                                        product_name.push($('#product_applied :selected').text());

                                                                        console.log(products);
                                                                        console.log(product_qty);
                                                                        console.log(product_name)

                                                                        alert('Product added!');
                                                                      }
                                                                      
                                                                    }

                                                                    function removeItem(array, itemToRemove) {
                                                                      const index = array.indexOf(itemToRemove);

                                                                      if (index !== -1) {
                                                                          array.splice(index, 1);
                                                                      }
                                                                      console.log("Updated Array: ", array);
                                                                    }

                                                                    // SAVE & PROCEED BUTTON
                                                                    function view_services(id){
                                                                      if(services.length < 1 || products.length < 1 ){
                                                                        alert('Please add Services or Products first!');
                                                                      }
                                                                      else if(service_prices.length < 1 || $('#service_price'+id).val() == ''){
                                                                        alert('Please add Services or Products first!');
                                                                      }
                                                                      else if($('#tooth_worked'+id).val() == ''){
                                                                        alert('Tooth is missing! Please add a tooth.');
                                                                      }
                                                                      else{
                                                                        console.log({service_prices},{services})
                                                                        console.log(product_name)
                                                                        form=new FormData();
                                                                        form.append('service',services);
                                                                        form.append("service_price",service_prices);
                                                                        form.append("tooth",teeth_worked);
                                                                        form.append("product",products);
                                                                        form.append("product_names",product_name);
                                                                        form.append("product_qty",product_qty);
                                                                        form.append("service_qty",service_qty);
                                                                        form.append("patient",<?php echo $row['patient_id']; ?>);
                                                                        form.append("bill_id",id);
                                                                        $.ajax({
                                                                          method:"POST",
                                                                          data:form,
                                                                          url:"view-service-modal.php",
                                                                          cache:false,
                                                                          contentType:false,
                                                                          processData:false,
                                                                        }).done( function(data){
                                                                          // alert(data)
                                                                          
                                                                          $('.services1').html  (data)                                          
                                                                          $('#modal-transaction').modal('toggle');
                                                                        })
                                                                        services = [];
                                                                        service_qty = [];
                                                                        products = [];
                                                                        product_qty = [];
                                                                        service_prices = [];
                                                                        teeth_worked = []; 
                                                                        product_name = [];                                      
                                                                      }
                                                                    }
                                                                  </script>
                                                              </td>
                                                              <td>
                                                                <?php
                                                                  $stmt0=$conn->prepare("SELECT * FROM register_patient WHERE register_id = ?");
                                                                  $stmt0->bind_param("s",$row['patient_id']);
                                                                  $stmt0->execute();
                                                                  $result0=$stmt0->get_result();
                                                                  while($row0=$result0->fetch_assoc()){
                                                                    echo $row0['first_name'].' '.$row0['middle_name'].' '.$row0['last_name'];
                                                                  }
                                                                ?>
                                                              <!-- </td>
                                                              <td><?php  ?></td>
                                                              <td><?php  ?></td> -->
                                                              <td>
                                                                <?php 
                                                                  $date = strtotime($row['date']);
                                                                  $new_date = getDate($date);
                                                                  $day = $new_date['mday'];
                                                                  $month = $new_date['month'];
                                                                  $year = $new_date['year'];
                                                                  
                                                                  echo $long_date = $month." ".$day.", ".$year;
                                                                ?>
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
                                                  <th>Patient</th>
                                                  <!-- <th>Contact #</th>
                                                  <th>GCash #</th> -->
                                                  <th>Date</th>
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

  <div class="row services1"></div>

  <script>
    $(document).ready( function(){
      
      var services = [];
      var service_qty = [];
      var service_prices = [];
      var product_qty = [];
      var products = [];
      var product_name = [];

      var teeth_worked = [];

          $(function () {
              $("#assistant-view-billing-table").DataTable({
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
    
          
    function open_modal(id){
      $('#assistant-view-bill-modal'+id).modal('show');
      services = [];
      service_qty = [];
      products = [];
      product_qty = [];
      service_prices = [];
      teeth_worked = [];
      product_name = [];
    }
    
  </script>