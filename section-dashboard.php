<?php
  session_start();
  require('config/connection.php');

  $id = $_SESSION['id'];
  $position = $_SESSION['position'];   

?>

<!-- Main content -->
<section class="content">
  <?php
  if($position != 'patient'){
    ?>
        <div class="container-fluid">
          <div class="row">
              <div class="col-sm-12">
                  <div class="card mt-3">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="row">

                              <div class="col-lg-4 col-4">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                  <div class="inner">
                                    <?php 
                                      $query='';
                                      if(!isset($_SESSION['designation'])){
                                        $query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointment WHERE status = 'Pending' AND branch_id = '$_SESSION[branch]' ");
                                      }
                                      else{
                                        $query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointment WHERE status = 'Pending' AND branch_id = '$_SESSION[designation]' ");
                                      }
                                        $row = mysqli_fetch_assoc($query);
                                        echo '<h3>'.$row['count'].'</h3>';
                                    ?>
                                    <p>Today's Pending Appointments</p>
                                  </div>
                                  <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                  </div>
                                  <a type="button" onclick="" class="small-box-footer">
                                    <!-- More info <i class="fas fa-arrow-circle-right"></i> -->
                                  </a>
                                </div>
                              </div>
                              <!-- ./col -->

                              <div class="col-lg-4 col-4">
                                <!-- small box -->
                                <div class="small-box bg-success">
                                  <div class="inner">
                                    <?php 
                                      $query='';
                                      if(!isset($_SESSION['designation'])){
                                        $query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointment WHERE status = 'Approved' AND branch_id = '$_SESSION[branch]' ");
                                      }
                                      else{
                                        $query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointment WHERE status = 'Approved' AND branch_id = '$_SESSION[designation]' ");
                                      }
                                      $row = mysqli_fetch_assoc($query);
                                      echo '<h3>'.$row['count'].'</h3>';
                                    ?>
                                    <p>Today's Approved Appointments</p>
                                  </div>
                                  <div class="icon">
                                    <i class="ion ion-clipboard"></i>
                                  </div>
                                  <a href="#" class="small-box-footer">
                                    <!-- More info <i class="fas fa-arrow-circle-right"></i> -->
                                  </a>
                                </div>
                              </div>
                              <!-- ./col -->

                              <div class="col-lg-4 col-4">
                                <!-- small box -->
                                <div class="small-box bg-warning">
                                  <div class="inner">
                                    <?php 
                                      $query='';
                                      if(!isset($_SESSION['designation'])){
                                        $query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointment WHERE status = 'Declined' AND branch_id = '$_SESSION[branch]' ");
                                      }
                                      else{
                                        $query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointment WHERE status = 'Declined' AND branch_id = '$_SESSION[designation]' ");
                                      }
                                      $row = mysqli_fetch_assoc($query);
                                      echo '<h3>'.$row['count'].'</h3>';
                                    ?>
                                    <p>Today's Declined Appointments</p>
                                  </div>
                                  <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                  </div>
                                  <a href="#" class="small-box-footer">
                                    <!-- More info <i class="fas fa-arrow-circle-right"></i> -->
                                  </a>
                                </div>
                              </div>
                              <!-- ./col -->

                              <div class="col-lg-4 col-4">
                                <!-- small box -->
                                <div class="small-box bg-danger">
                                  <div class="inner">
                                    <?php 
                                      $query='';
                                      $date_ = date('Y-m-d');
                                      if(!isset($_SESSION['designation'])){
                                        $query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM delivery WHERE branch_id = '$_SESSION[branch]' AND date = '$date_' ");
                                      }
                                      else{
                                        $query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM delivery WHERE branch_id = '$_SESSION[designation]' AND date = '$date_' ");
                                      }
                                      $row = mysqli_fetch_assoc($query);
                                      echo '<h3>'.$row['count'].'</h3>';
                                    ?>
                                    <p>Today's Delivery</p>
                                  </div>
                                  <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                  </div>
                                  <a href="#" class="small-box-footer">
                                    <!-- More info <i class="fas fa-arrow-circle-right"></i> -->
                                  </a>
                                </div>
                              </div>
                              <!-- ./col -->

                              <div class="col-lg-4 col-4">
                                <!-- small box -->
                                <div class="small-box bg-primary">
                                  <div class="inner">
                                    <?php 
                                      $query='';
                                      $date_ = date('Y-m-d');
                                     
                                      $query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM register_patient ");

                                      $row = mysqli_fetch_assoc($query);
                                      echo '<h3>'.$row['count'].'</h3>';
                                    ?>
                                    <p>Registered Patient</p>
                                  </div>
                                  <div class="icon">
                                    <i class="ion ion-person-stalker"></i>
                                  </div>
                                  <a href="#" class="small-box-footer">
                                    <!-- More info <i class="fas fa-arrow-circle-right"></i> -->
                                  </a>
                                </div>
                              </div>

                              <div class="col-lg-4 col-4">
                                <!-- small box -->
                                <div class="small-box bg-default">
                                  <div class="inner">
                                    <?php 
                                      $query='';
                                      $date_ = date('Y-m-d');
                                      $st = 'x';
                                     
                                      $query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointment WHERE status = 'x' ");

                                      $row = mysqli_fetch_assoc($query);
                                      echo '<h3>'.$row['count'].'</h3>';
                                    ?>
                                    <p>Successful Transaction</p>
                                  </div>
                                  <div class="icon">
                                    <i class="ion ion-document-text"></i>
                                  </div>
                                  <a href="#" class="small-box-footer">
                                    <!-- More info <i class="fas fa-arrow-circle-right"></i> -->
                                  </a>
                                </div>
                              </div>

                            </div>
                          </div>
                          <!-- COL-SM-12 -->
                          <div class="col-sm-12">
                            <div class="row">
                              <div class="col-sm-12">
                                <!-- LINE CHART -->
                                <div class="card card-info">
                                      <!-- <div class="card-header">
                                          <h3 class="card-title">Line Chart</h3>

                                          <div class="card-tools">
                                          <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                              <i class="fas fa-minus"></i>
                                          </button>
                                          <button type="button" class="btn btn-tool" data-card-widget="remove">
                                              <i class="fas fa-times"></i>
                                          </button>
                                          </div>
                                      </div> -->
                                      <div class="card-body">
                                          <div class="chart">
                                              <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                          </div>
                                              <center>
                                                  <label>Monthly Stock Record</label>
                                              </center>
                                      </div>
                                      <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                              </div>
                            </div>
                          </div>
                          <!-- COL-SM-12 -->
                            <div class="col-sm-12">
                            <div class="row">
                              <div class="col-sm-6">
                                <!-- AREA CHART -->
                                <div class="card card-primary">
                                    <!-- <div class="card-header">
                                        <h3 class="card-title">Area Chart</h3>

                                        <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        </div>
                                    </div> -->
                                    <div class="card-body">
                                        <div class="chart">
                                          <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                        </div>
                                        
                                        <CENTER>
                                          <label align="center">Monthly Sales</label>
                                        </CENTER>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                              </div>
                              <!-- col-sm-6 -->
                              <div class="col-sm-6">
                                <!-- DONUT CHART -->
                                <div class="card card-danger">
                                <!-- <div class="card-header">
                                    <h3 class="card-title">Donut Chart</h3>

                                    <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    </div>
                                </div> -->
                                <div class="card-body">
                                    <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                                  <CENTER>
                                    <label align="center">Assistants</label>
                                  </CENTER>
                                
                                <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                              </div>
                              <!-- col-sm-6 -->
                                <div class="col-sm-6">
                                  <!-- PIE CHART -->
                                  <div class="card card-danger">
                                    <!-- <div class="card-header">
                                        <h3 class="card-title">Pie Chart</h3>

                                        <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        </div>
                                    </div> -->
                                    <div class="card-body">
                                        <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                      <center>
                                        <label align="center">Dentists</label>
                                      </center>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                                <!-- col-sm-6 -->
                                <!-- <div class="col-sm-6"> -->
                                    <!-- BAR CHART -->
                                    <!-- <div class="card card-success">
                                      <div class="card-header">
                                          <h3 class="card-title">Bar Chart</h3>

                                          <div class="card-tools">
                                          <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                              <i class="fas fa-minus"></i>
                                          </button>
                                          <button type="button" class="btn btn-tool" data-card-widget="remove">
                                              <i class="fas fa-times"></i>
                                          </button>
                                          </div>
                                      </div>
                                      <div class="card-body">
                                          <div class="chart">
                                          <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                          </div>
                                      </div> -->
                                      <!-- /.card-body -->
                                    <!-- </div> -->
                                    <!-- /.card -->
                                <!-- </div> -->
                                <!-- col-sm-6 -->
                                <!-- <div class="col-sm-6"> -->
                                    <!-- STACKED BAR CHART -->
                                  <!-- <div class="card card-success"> -->
                                  <!-- <div class="card-header">
                                      <h3 class="card-title">Stacked Bar Chart</h3>

                                      <div class="card-tools">
                                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                          <i class="fas fa-minus"></i>
                                      </button>
                                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                                          <i class="fas fa-times"></i>
                                      </button>
                                      </div>
                                  </div> -->
                                  <!-- <div class="card-body">
                                      <div class="chart">
                                      <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                      </div>
                                  </div>
                                  <center>
                                    <label for="" align="center">Appointments</label>
                                  </center> -->
                                  <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                                <!-- col-sm-6 -->
                            </div>
                            </div>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
        <!-- Charts -->
        <script>
          $(function () {
            /* ChartJS
            * -------
            * Here we will create a few charts using ChartJS
            */

            //--------------
            //- AREA CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

            var areaChartData = {
              labels  : [
                <?php
                    for ($m=1; $m<=12; $m++) {
                        $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                        echo "'".$month."',";
                    }
                ?>
                ],
              datasets: [
                {
                  label               : 'Sold',
                  backgroundColor     : 'rgba(33, 238, 206, 0.8)',
                  borderColor         : 'rgba(33, 238, 206, 0.8)',
                  pointRadius          : false,
                  pointColor          : '#3b8bba',
                  pointStrokeColor    : 'rgba(60,141,188,1)',
                  pointHighlightFill  : '#fff',
                  pointHighlightStroke: 'rgba(60,141,188,1)',
                  data                : [
                                            <?php
                                              $months = ["01","02","03","04","05","06",'07',"08","09","10","11","12"];
                                              foreach($months as $m){
                                                  $time_input = strtotime(date('Y').'-'.$m.'-'.'1');

                                                  $time_input = strtotime($time_input);
                                                  $date = getDate($time_input);
                                                  $type = CAL_GREGORIAN;
                                                  $month = $date['mon']; // Month ID, 1 through to 12.
                                                  $year = date('Y'); // Year in 4 digit 2009 format.
                                                  $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
                                                  $dayEnd = $year."-".$m.'-'.$day_count;
                                                  $dayStrt = $year."-".$m.'-'.'01';
                                                  
                                                  $query = "";
                                                  if(!isset($_SESSION['designation'])){
                                                    $query = mysqli_query($conn, "SELECT SUM(sold) AS r FROM inventory WHERE branch_id = '$_SESSION[branch]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
                                                    $row =  mysqli_fetch_assoc($query);
                                                      echo intval($row['r']).",";
                                                  } 
                                                  else{
                                                    $query = mysqli_query($conn, "SELECT SUM(sold) AS r FROM inventory WHERE branch_id = '$_SESSION[designation]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
                                                    $row =  mysqli_fetch_assoc($query);
                                                      echo intval($row['r']).",";
                                                  }
                                              }
                                            ?>
                                        ]
                },
                {
                  label               : 'Damaged',
                  backgroundColor     : 'rgba(238, 33, 129, 0.8)',
                  borderColor         : 'rgba(238, 33, 129, 0.8)',
                  pointRadius         : false,
                  pointColor          : 'rgba(210, 214, 222, 1)',
                  pointStrokeColor    : '#c1c7d1',
                  pointHighlightFill  : '#fff',
                  pointHighlightStroke: 'rgba(220,220,220,1)',
                  data                : [
                                            <?php
                                                $months = ["01","02","03","04","05","06",'07',"08","09","10","11","12"];
                                                foreach($months as $m){
                                                    $time_input = strtotime(date('Y').'-'.$m.'-'.'1');
                                                
                                                    $time_input = strtotime($time_input);
                                                    $date = getDate($time_input);
                                                    $type = CAL_GREGORIAN;
                                                    $month = $date['mon']; // Month ID, 1 through to 12.
                                                    $year = date('Y'); // Year in 4 digit 2009 format.
                                                    $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
                                                    $dayEnd = $year."-".$m.'-'.$day_count;
                                                    $dayStrt = $year."-".$m.'-'.'01';
                                                    
                                                    $query = "";
                                                    if(!isset($_SESSION['designation'])){
                                                      $query = mysqli_query($conn, "SELECT SUM(damaged) AS r FROM inventory WHERE branch_id = '$_SESSION[branch]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
                                                      $row =  mysqli_fetch_assoc($query);
                                                        echo intval($row['r']).",";
                                                    } 
                                                    else{
                                                      $query = mysqli_query($conn, "SELECT SUM(damaged) AS r FROM inventory WHERE branch_id = '$_SESSION[designation]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
                                                      $row =  mysqli_fetch_assoc($query);
                                                        echo intval($row['r']).",";
                                                    }
                                                }
                                            ?>
                                        ]
                },
                // {
                //   label               : 'Stocks',
                //   backgroundColor     : 'rgba(238, 33, 129, 0.8)',
                //   borderColor         : 'rgba(238, 33, 129, 0.8)',
                //   pointRadius         : false,
                //   pointColor          : 'rgba(210, 214, 222, 1)',
                //   pointStrokeColor    : '#c1c7d1',
                //   pointHighlightFill  : '#fff',
                //   pointHighlightStroke: 'rgba(220,220,220,1)',
                //   data                : [
                //                             <?php
                //                                 $months = ["01","02","03","04","05","06",'07',"08","09","10","11","12"];
                //                                 foreach($months as $m){
                //                                     $time_input = strtotime(date('Y').'-'.$m.'-'.'1');
                                                
                //                                     $time_input = strtotime($time_input);
                //                                     $date = getDate($time_input);
                //                                     $type = CAL_GREGORIAN;
                //                                     $month = $date['mon']; // Month ID, 1 through to 12.
                //                                     $year = date('Y'); // Year in 4 digit 2009 format.
                //                                     $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
                //                                     $dayEnd = $year."-".$m.'-'.$day_count;
                //                                     $dayStrt = $year."-".$m.'-'.'01';
                                                    
                //                                     $query = "";
                //                                     if(!isset($_SESSION['designation'])){
                //                                       $query = mysqli_query($conn, "SELECT SUM(remaining_qty) AS r FROM inventory WHERE branch_id = '$_SESSION[branch]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
                //                                       $row =  mysqli_fetch_assoc($query);
                //                                         echo intval($row['r']).",";
                //                                     } 
                //                                     else{
                //                                       $query = mysqli_query($conn, "SELECT SUM(remaining_qty) AS r FROM inventory WHERE branch_id = '$_SESSION[designation]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
                //                                       $row =  mysqli_fetch_assoc($query);
                //                                         echo intval($row['r']).",";
                //                                     }
                //                                 }
                //                             ?>
                //                         ]
                // },
              ]
            }

            var areaChartOptions = {
              maintainAspectRatio : false,
              responsive : true,
              legend: {
                display: false
              },
              scales: {
                xAxes: [{
                  gridLines : {
                    display : false,
                  }
                }],
                yAxes: [{
                  gridLines : {
                    display : false,
                  }
                }]
              }
            }

            // This will get the first returned node in the jQuery collection.
            new Chart(areaChartCanvas, {
              type: 'line',
              data: areaChartData,
              options: areaChartOptions
            })

            //-------------
            //- LINE CHART -
            //--------------
            var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
            var lineChartOptions = $.extend(true, {}, areaChartOptions)
            var lineChartData = {
              labels  : [
                <?php
                    for ($m=1; $m<=12; $m++) {
                        $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                        echo "'".$month."',";
                    }
                ?>
                ],
              datasets: [
                {
                  label               : 'Stocks',
                  backgroundColor     : 'rgba(238, 33, 129, 0.8)',
                  borderColor         : 'rgba(238, 33, 129, 0.8)',
                  pointRadius         : false,
                  pointColor          : 'rgba(210, 214, 222, 1)',
                  pointStrokeColor    : '#c1c7d1',
                  pointHighlightFill  : '#fff',
                  pointHighlightStroke: 'rgba(220,220,220,1)',
                  data                : [
                                            <?php
                                                $months = ["01","02","03","04","05","06",'07',"08","09","10","11","12"];
                                                foreach($months as $m){
                                                    $time_input = strtotime(date('Y').'-'.$m.'-'.'1');
                                                
                                                    $time_input = strtotime($time_input);
                                                    $date = getDate($time_input);
                                                    $type = CAL_GREGORIAN;
                                                    $month = $date['mon']; // Month ID, 1 through to 12.
                                                    $year = date('Y'); // Year in 4 digit 2009 format.
                                                    $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
                                                    $dayEnd = $year."-".$m.'-'.$day_count;
                                                    $dayStrt = $year."-".$m.'-'.'01';
                                                    
                                                    $query = "";
                                                    if(!isset($_SESSION['designation'])){
                                                      $query = mysqli_query($conn, "SELECT SUM(remaining_qty) AS r FROM inventory WHERE branch_id = '$_SESSION[branch]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
                                                      $row =  mysqli_fetch_assoc($query);
                                                        echo intval($row['r']).",";
                                                    } 
                                                    else{
                                                      $query = mysqli_query($conn, "SELECT SUM(remaining_qty) AS r FROM inventory WHERE branch_id = '$_SESSION[designation]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
                                                      $row =  mysqli_fetch_assoc($query);
                                                        echo intval($row['r']).",";
                                                    }
                                                }
                                            ?>
                                        ]
                }
              ]
            }
            lineChartData.datasets[0].fill = false;
            // lineChartData.datasets[1].fill = false;
            lineChartOptions.datasetFill = false

            var lineChart = new Chart(lineChartCanvas, {
              type: 'line',
              data: lineChartData,
              options: lineChartOptions
            })

            //-------------
            //- DONUT CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            
            var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
            var donutData        = {
              labels: [
                "<?php
                    $query = mysqli_query($conn, "SELECT * FROM dentist");
                    while($row = mysqli_fetch_assoc($query)){
                        echo $row['first_name'];
                    }
                ?>"
              ],
              datasets: [
                {
                  data: [
                    <?php
                        $count=1;
                        $query = mysqli_query($conn, "SELECT * FROM dentist");
                        while($row = mysqli_fetch_assoc($query)){
                            echo $count.",";
                        }
                    ?>
                  ],
                  backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                }
              ]
            }
            var pieData        = {
              labels: [
                <?php
                if(isset($_SESSION['designation'])){
                  $query = mysqli_query($conn, "SELECT * FROM assistant WHERE designation = '$_SESSION[designation]'");
                  while($row = mysqli_fetch_assoc($query)){
                      echo "'".$row['first_name']."',";
                  }
                }
                else{
                  $query = mysqli_query($conn, "SELECT * FROM assistant WHERE designation = '$_SESSION[branch]'");
                  while($row = mysqli_fetch_assoc($query)){
                      echo "'".$row['first_name']."',";
                  }
                }    
                ?>
              ],
              datasets: [
                {
                  data: [
                    <?php
                        $count=1;
                        if(isset($_SESSION['designation'])){
                          $query = mysqli_query($conn, "SELECT * FROM assistant WHERE designation = '$_SESSION[designation]'");
                          while($row = mysqli_fetch_assoc($query)){
                            echo $count.",";
                          }
                        }
                        else{
                          $query = mysqli_query($conn, "SELECT * FROM assistant WHERE designation = '$_SESSION[branch]'");
                          while($row = mysqli_fetch_assoc($query)){
                            echo $count.",";
                          }
                        } 
                    ?>
                  ],
                  backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                }
              ]
            }
            var donutOptions     = {
              maintainAspectRatio : false,
              responsive : true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            new Chart(donutChartCanvas, {
              type: 'doughnut',
              data: pieData,
              options: donutOptions
            })

            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieData        = donutData;
            var pieOptions     = {
              maintainAspectRatio : false,
              responsive : true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            new Chart(pieChartCanvas, {
              type: 'pie',
              data: pieData,
              options: pieOptions
            })

            //-------------
            //- BAR CHART -
            //-------------
            // var barChartCanvas = $('#barChart').get(0).getContext('2d')
            // var barChartData = $.extend(true, {}, areaChartData)
            // var temp0 = areaChartData.datasets[0]
            // var temp1 = areaChartData.datasets[1]
            // barChartData.datasets[0] = temp1
            // barChartData.datasets[1] = temp0

            // var barChartOptions = {
            //   responsive              : true,
            //   maintainAspectRatio     : false,
            //   datasetFill             : false
            // }

            // new Chart(barChartCanvas, {
            //   type: 'bar',
            //   data: barChartData,
            //   options: barChartOptions
            // })

            //---------------------
            //- STACKED BAR CHART -
            //---------------------
            // var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
            // // var stackedBarChartData = $.extend(true, {}, barChartData)
            // var stackedBarChartData = {
            //   labels  : [
            //     <?php
            //         for ($m=1; $m<=12; $m++) {
            //             $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
            //             echo "'".$month."',";
            //         }
            //     ?>
            //     ],
            //   datasets: [
            //     {
            //       label               : 'Solds',
            //       backgroundColor     : 'rgba(151, 246, 101, 0.8)',
            //       borderColor         : 'rgba(151, 246, 101, 0.8)',
            //       pointRadius          : false,
            //       pointColor          : '#3b8bba',
            //       pointStrokeColor    : 'rgba(60,141,188,1)',
            //       pointHighlightFill  : '#fff',
            //       pointHighlightStroke: 'rgba(60,141,188,1)',
            //       data                : [
            //                                 <?php

            //                                     $months = ["01","02","03","04","05","06",'07',"08","09","10","11","12"];
            //                                     foreach($months as $m){
            //                                         $time_input = strtotime(date('Y').'-'.$m.'-'.'1');
                                                
            //                                         $time_input = strtotime($time_input);
            //                                         $date = getDate($time_input);
            //                                         $type = CAL_GREGORIAN;
            //                                         $month = $date['mon']; // Month ID, 1 through to 12.
            //                                         $year = date('Y'); // Year in 4 digit 2009 format.
            //                                         $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
            //                                         $dayEnd = $year."-".$m.'-'.$day_count;
            //                                         $dayStrt = $year."-".$m.'-'.'01';
                                                    
            //                                         $query = "";
            //                                         if(!isset($_SESSION['designation'])){
            //                                           $query = mysqli_query($conn, "SELECT SUM(sold) AS r FROM inventory WHERE branch_id = '$_SESSION[branch]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
            //                                           $row =  mysqli_fetch_assoc($query);
            //                                             echo $row['r'].",";
            //                                         } 
            //                                         else{
            //                                           $query = mysqli_query($conn, "SELECT SUM(sold) AS r FROM inventory WHERE branch_id = '$_SESSION[designation]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
            //                                           $row =  mysqli_fetch_assoc($query);
            //                                             echo $row['r'].",";
            //                                         }
            //                                     }
            //                                 ?>
            //                             ]
            //     },
            //     {
            //       label               : 'Damaged',
            //       backgroundColor     : 'rgba(232, 92, 12, 0.8)',
            //       borderColor         : 'rgba(232, 92, 12, 0.8)',
            //       pointRadius          : false,
            //       pointColor          : '#3b8bba',
            //       pointStrokeColor    : 'rgba(60,141,188,1)',
            //       pointHighlightFill  : '#fff',
            //       pointHighlightStroke: 'rgba(60,141,188,1)',
            //       data                : [
            //                                 <?php

            //                                     $months = ["01","02","03","04","05","06",'07',"08","09","10","11","12"];
            //                                     foreach($months as $m){
            //                                         $time_input = strtotime(date('Y').'-'.$m.'-'.'1');
                                                
            //                                         $time_input = strtotime($time_input);
            //                                         $date = getDate($time_input);
            //                                         $type = CAL_GREGORIAN;
            //                                         $month = $date['mon']; // Month ID, 1 through to 12.
            //                                         $year = date('Y'); // Year in 4 digit 2009 format.
            //                                         $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
            //                                         $dayEnd = $year."-".$m.'-'.$day_count;
            //                                         $dayStrt = $year."-".$m.'-'.'01';
                                                    
            //                                         $query = "";
            //                                         if(!isset($_SESSION['designation'])){
            //                                           $query = mysqli_query($conn, "SELECT SUM(damaged) AS r FROM inventory WHERE branch_id = '$_SESSION[branch]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
            //                                           $row =  mysqli_fetch_assoc($query);
            //                                             echo $row['r'].",";
            //                                         } 
            //                                         else{
            //                                           $query = mysqli_query($conn, "SELECT SUM(damaged) AS r FROM inventory WHERE branch_id = '$_SESSION[designation]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
            //                                           $row =  mysqli_fetch_assoc($query);
            //                                             echo $row['r'].",";
            //                                         }
            //                                     }
            //                                 ?>
            //                             ]
            //     },
            //     {
            //       label               : 'Stocks',
            //       backgroundColor     : 'rgba(232, 92, 12, 0.8)',
            //       borderColor         : 'rgba(232, 92, 12, 0.8)',
            //       pointRadius          : false,
            //       pointColor          : '#3b8bba',
            //       pointStrokeColor    : 'rgba(60,141,188,1)',
            //       pointHighlightFill  : '#fff',
            //       pointHighlightStroke: 'rgba(60,141,188,1)',
            //       data                : [
            //                                 <?php

            //                                     $months = ["01","02","03","04","05","06",'07',"08","09","10","11","12"];
            //                                     foreach($months as $m){
            //                                         $time_input = strtotime(date('Y').'-'.$m.'-'.'1');
                                                
            //                                         $time_input = strtotime($time_input);
            //                                         $date = getDate($time_input);
            //                                         $type = CAL_GREGORIAN;
            //                                         $month = $date['mon']; // Month ID, 1 through to 12.
            //                                         $year = date('Y'); // Year in 4 digit 2009 format.
            //                                         $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
            //                                         $dayEnd = $year."-".$m.'-'.$day_count;
            //                                         $dayStrt = $year."-".$m.'-'.'01';
                                                    
            //                                         $query = "";
            //                                         if(!isset($_SESSION['designation'])){
            //                                           $query = mysqli_query($conn, "SELECT SUM(remaining_qty) AS r FROM inventory WHERE branch_id = '$_SESSION[branch]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
            //                                           $row =  mysqli_fetch_assoc($query);
            //                                             echo $row['r'].",";
            //                                         } 
            //                                         else{
            //                                           $query = mysqli_query($conn, "SELECT SUM(remaining_qty) AS r FROM inventory WHERE branch_id = '$_SESSION[designation]' AND date BETWEEN '$dayStrt' AND '$dayEnd'");
            //                                           $row =  mysqli_fetch_assoc($query);
            //                                             echo $row['r'].",";
            //                                         }
            //                                     }
            //                                 ?>
            //                             ]
            //     }
            //   ]
            // }
            

            // var stackedBarChartOptions = {
            //   responsive              : true,
            //   maintainAspectRatio     : false,
            //   scales: {
            //     xAxes: [{
            //       stacked: true,
            //     }],
            //     yAxes: [{
            //       stacked: true
            //     }]
            //   }
            // }

            // new Chart(stackedBarChartCanvas, {
            //   type: 'bar',
            //   data: stackedBarChartData,
            //   options: stackedBarChartOptions
            // })
          })
        </script>  
    <?php
  }
  else{
    ?>
      <div class="row">
          <?php
          // if($position == 'patient'){
          $stmt0=$conn->prepare("SELECT * FROM services GROUP BY service_name");
          $stmt0->execute();
          $result0=$stmt0->get_result();
            while($row0=$result0->fetch_assoc()){
            ?>
            <div class="col-sm-4">
                <div class="card mt-3">

                    <div class="card-body m-0">
                      <div class="row">
                        
                        <?php 
                          if($row0['image'] == ""){
                            ?>
                            <img src="clinic_logo.png" alt="" id="service_img" style="border-radius:10px">
                            <div class="col-sm-12" style="position:absolute; top:28px" z-index:'99999'>
                              <center>
                                <div style="background-color:#9640b0; color:#fff; border: 2px solid; border-radius: 15px; padding: 5px; position: absolute; font-weight:bolder"><?php echo $row0['service_name']; ?></div>
                              </center>
                            </div>
                            <div class="col-sm-12" style="position:absolute; bottom: 80px;">
                              <center>
                                <div style="background-color:#9640b0; color:#fff; border: 2px solid; border-radius: 15px; padding: 5px; position: absolute; right: 35px; font-size:24px">
                                  <strong><?php echo '₱ ' . number_format($row0['price'], 2); ?></strong>
                                </div>
                              </center>
                            </div>
                            <?php
                          }
                          else{
                            ?>
                            <img src="<?php echo $row0['image']; ?>" alt="" id="service_img"  style="border-radius:10px">
                            <div class="col-sm-12" style="position:absolute; top:28px">
                              <center>
                                <div style="background-color:#fff; border: 2px solid; border-radius: 15px; padding: 5px; position: absolute; font-weight:bolder"><?php echo $row0['service_name']; ?></div>
                              </center>
                            </div>

                            <div class="col-sm-12" style="position:absolute; bottom: 80px;">
                              <center>
                                <div style="background-color:#fff; border: 2px solid; border-radius: 15px; padding: 5px; position: absolute; right: 35px; font-size:24px">
                                  <strong><?php echo '₱ ' . number_format($row0['price'], 2); ?></strong>
                                </div>
                              </center>
                            </div>
                            <?php
                          }
                        ?>
                      </div>
                    </div>
                </div>
            </div>
            <?php } ?>
          <?php //} ?>
      </div>
    <?php
  }
  ?>
</section>
<!-- /.content -->

<?php

    // $id = $_SESSION['id'];
    // $position = $_SESSION['position'];

    // require 'vendor/autoload.php';

    // use Dompdf\Dompdf;


    // $i = 0;

    // $html = "<style>
    //             @page { 
    //                     margin: 0; 
    //                     size: 148.5mm 105mm;
    //                     font-size: 11px;
    //                     color: #9640b0;
    //                 }
    //             body { 
    //                     margin: 0;
    //                     // background: lightgrey;
    //                     color: #9640b0;
    //                 }

    //             .pdf-table,td,tr{ 
    //                 border: 1px solid black;
    //                 border-collapse:collapse;
    //                 // padding: 3px;    
    //                 text-align: center;
                    
    //             }
    //             label, small{
    //                 font-weight: bold;
    //             }
    //             label{
    //                 font-size: 14px;
    //             }
    //             input{
    //                 background: transparent;
    //                 border: transparent;
    //                 border-bottom: 1px solid black;
    //             } 
    //             p{
    //                 color: black;
    //                 padding: 0;
    //             }
    //             input{
    //                 text-indent: 30px;
    //             }
    //         </style>";
    // $html .=    "<div style='margin-top: 5px'>
    //                 <center>
    //                     <label>D' 13TH SMILE DENTAL CLINIC</label><br>
    //                     <small>2nd Floor Town Center, Himatagon, Saint Bernard, Southern Leyte</small><br>
    //                     <small>TRECE DURAN M. MAGBANUA <I>-P</I></small><br>
    //                     <label>ACKNOWEDGEMENT RECIEPT</label><br>
    //                 </center>
    //             </div>";
    // $html .= "<div style='margin:10px 0 0 10px'>
    //             <label style='margin-left:385px; '>Date: <input type='text' style='width: 20%'></label><br>
    //             <label style='margin-left:0; '>Name: <input type='text' style='width: 30%'></label>
    //             <label style='margin-left:30%;'>No. <b style='color: red; font-size: 20px'>00001</b></label>
    //         </div>";
    // $html .=   "
    //         <div style='margin: 10px'>    
    //             <table class='pdf-table' style='width:100%;'>
    //                 <tr>
    //                     <td style='height: 14px'>
    //                         QTY
    //                     </td>
    //                     <td>
    //                         ITEM DESCRIPTION
    //                     </td>
    //                     <td>
    //                         PRICE
    //                     </td>
    //                     <td>
    //                         AMOUNT
    //                     </td>
    //                     <!--<td>
    //                         Fee
    //                     </td>
    //                     <td>
    //                         Paid
    //                     </td>
    //                     <td>
    //                         Balance
    //                     </td>-->
    //                 </tr>";
    //         while($i < 10){
    // $html   .=      "<tr>
    //                     <td style='height: 14px'> </td>
    //                     <td> </td>
    //                     <td> </td>
    //                     <td> </td>
    //                     <!--<td> </td>
    //                     <td> </td>
    //                     <td> </td>-->
    //                 </tr>";
    //                 $i++;
    //         }
    // $html   .=  "</table>
    //             <div style='margin-top: 10px'>
    //                 <label>Mode of payment:<input type='text' style='width: 78.25%'></label>
    //             </div>
    //         </div>";

    // $html   .=" <div style='margin-top: 1.5em'>
    //                 <center>
    //                     <input type='text'><br>
    //                     <label style='margin-right:0'>Signature</label>
    //                 </center>
    //             </div>";
            
    // $dompdf = new Dompdf();
    // $dompdf->loadHtml($html);
    // // $customPaper = array(0,0,900,900);
    // // $dompdf->setPaper($customPaper,'portrait');
    // // $dompdf->setPaper('A4', 'portrait'); 
    // $dompdf->render();
    // $output = $dompdf->output();


?> 

