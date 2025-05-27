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
    
    $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
    $long_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $sched_array=array();

    $already_selected_value = date('Y');
    $earliest_year = 1950;
?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card mt-3">
              <div class="card-header">
                <!-- <h3 class="card-title">Dental Clinic Branch</h3> -->
                <h4 class="">Inventory</h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card">
                        <div class="card-header">
                            <div class="row float-right">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modal-inventory">
                                    Print Document
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="assistant-view-products-table" class="table table-borderless table-hover">
                              <thead>
                                <tr>
                                    <th style="width: 30px">#</th>
                                    <!-- <th></th> -->
                                    <th>Item</th>
                                    <th>Sold</th>
                                    <th>Damaged</th>
                                    <th>Stocks</th>
                                    <th>Date</th>
                                    <!-- <th></th> -->
                                    <!-- <th></th> -->
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                // $time_input = strtotime(date('Y').'-'.$m.'-'.'1');
                                            
                                // $time_input = strtotime($time_input);
                                // $date = getDate($time_input);
                                // $type = CAL_GREGORIAN;
                                // $year = date('Y'); // Year in 4 digit 2009 format.
                                // $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
                                // $dayEnd = $year."-".$m.'-'.$day_count;
                                // $dayStrt = $year."-".$m.'-'.'1';

                                $no=1;
                                $status='1';
                                $stmt = $conn->prepare("SELECT * FROM inventory WHERE branch_id = ? ORDER BY date DESC");
                                $stmt->bind_param('s',$designation);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                    $time_input = strtotime($row['date']);
                                    $date = getDate($time_input);
                                    $mDay = $date['mday'];
                                    $Month = $date['month'];
                                    $Year = $date['year'];
                                    $_date = $Month.' '.$mDay.', '.$Year; //format the date example {December 2, 2024}
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $row['product_name']; ?></td>
                                            <td><?php echo $row['sold']; ?></td>
                                            <td><?php echo $row['damaged']; ?></td>
                                            <td><?php echo $row['remaining_qty']; ?></td>
                                            <td><?php echo $_date; ?></td>
                                            
                                        </tr>
                                    <?php
                                    $no++;
                                }
                                ?>
                              </tbody>
                              <tfoot>
                                <tr>
                                    <th style="width: 30px">#</th>
                                    <!-- <th></th> -->
                                    <th>Item</th>
                                    <th>Sold</th>
                                    <th>Damaged</th>
                                    <th>Stocks</th>
                                    <th>Date</th>
                                    <!-- <th></th> -->
                                    <!-- <th></th> -->
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

<div class="modal fade" id="modal-inventory" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Print Inventory</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <!-- <div class="row">
                <div class="col-sm-12">
                    <label for="">From:</label>
                    <input type="text" id="sched-from-datepicker" class="form-control" placeholder="Pick a Date"><br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label for="">To:</label>
                    <input type="text" id="sched-to-datepicker" class="form-control" placeholder="Pick a Date"><br>
                </div>
            </div>
            OR -->
            <div class="row">
                <div class="col-sm-12">
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="">Month</label>
                            <select name="" id="month" class="form-control">
                                <option value=""></option>
                                <?php
                                    $index=0;
                                    foreach($months as $month){
                                        echo "<option value='".$month."'>".$long_months[$index]."</option>";
                                        $index++;
                                    }
                                ?>
                            </select>    
                        </div>
                        <div class="col-sm-12">
                            <label for="">Year</label>
                            <select name="" id="year" class="form-control">
                                <option value=""></option>
                                <?php
                                    foreach (range(date('Y'), $earliest_year) as $x) {
                                        print '<option value="'.$x.'"'.($x === $already_selected_value ? ' selected=selected ' : '').'>'.$x.'</option>';
                                    }
                                ?>
                            </select>    
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- <iframe id="iframe" alt="PDF not available" frameborder="0" style="display:none; width:100%; height:80vh"></iframe> -->
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="print_inventory()" >Print Document</button>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
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
<script>

    $('#sched-from-datepicker, #sched-to-datepicker').datepicker(
        {
            // minDate: 0,
            beforeShowDay:beforeShowDay,
            // beforeShowDay: function(date){
            //     var day = date.getDay();
            //     var string = jQuery.datepicker.formatDate("yy-mm-dd",date);
            //     return [(sched_array.indexOf(string) == -1)?(day>0):"", "calendar-background"];
            // },
            dateFormat: "MM dd, yy",
        }
    );
    function beforeShowDay(sunday){
        var day = sunday.getDay();
        return [(day>0), "calendar-background"];  
    }

    var sched_array = <?php echo '["' . implode('", "', $sched_array) . '"]' ?>
    
    function print_inventory(){
        $("#sched-from-datepicker").val();
        $('#sched-to-datepicker').val();

        // if(($("#sched-from-datepicker").val() == '' || $('#sched-to-datepicker').val() == '') && ($('#month').val() == '' || $('#year').val() == '')){
        //     alert("Empty Dates!");
        // }
        // else if(($("#sched-from-datepicker").val() != '' || $('#sched-to-datepicker').val() != '') && ($('#month').val() != '' || $('#year').val() != '')){
        //     alert("Select only one option!");
        // }
        if(($('#month').val() == '' || $('#year').val() == '')){
            alert("Fields should not leave empty!");
        }
        else{
            form=new FormData()
            form.append('from',$("#sched-from-datepicker").val())
            form.append('to',$('#sched-to-datepicker').val())
            form.append('month',$('#month').val())
            form.append('year',$('#year').val())
            data = $.ajax({
                data:form,
                type:'post',
                url:'assistant-print-inventory.php',
                processData: false,
                contentType:false,
            })
            $.when(data).done( function(data){
                if(data === 'No data was found!'){
                    alert(data);
                }
                else{
                    
                    var iframe = document.createElement('iframe');
                    iframe.style.display = "none";
                    iframe.src = 'pdf_inventory/inventory1.pdf';
                    document.body.appendChild(iframe);
                    iframe.contentWindow.focus();
                    window.open(iframe.src);
                    // iframe.contentWindow.print();                
                }
                console.log(data)
            })
        }
    }
</script>