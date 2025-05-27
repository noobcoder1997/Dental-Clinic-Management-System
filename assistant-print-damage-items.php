<?php
    session_start();
    include 'config/connection.php';
    
    $id=$_SESSION['id'];
    $position=$_SESSION['position'];
        
    if(!isset($_SESSION['designation'])){
        $designation = $_SESSION['branch'];
    }
    else{
        $designation = $_SESSION['designation'];

        $stmt=$conn->prepare("SELECT * FROM delivery WHERE delivery_id = ? AND branch_id = ?");
        $stmt->bind_param('ss', $delivery_id,$designation);
        $stmt->execute();
        $result=$stmt->get_result();
        $row0=$result->fetch_assoc();

        $query = "SELECT * FROM assistant WHERE assistant_id = ?";
        $stmt=$conn->prepare($query);
        $stmt->bind_param('s',$_SESSION['id']);
        $stmt->execute();
        $result=$stmt->get_result();
        if($row=$result->fetch_assoc()){
            $date = date('Y-m-d');
            $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- printed a copy of delivery, description: $row0[description] on $date at".date('H:i:s A');
        }
        $status = '1';
        $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
        $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
        $stmt->execute();
    }
    
    require 'vendor/autoload.php';

    use Dompdf\Dompdf;


        $month = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['month'])));
        $year = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['year'])));
        $time_input = strtotime($year.'-'.$month.'-'.'01');
                                                
        $time_input = strtotime($time_input);
        $date = getDate($time_input);
        $type = CAL_GREGORIAN;
        // $year = date('Y'); // Year in 4 digit 2009 format.
        $m = $date['month'];
        $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days

        $dayEnd = $year."-".$month.'-'.$day_count;
        $dayStrt = $year."-".$month.'-'.'01';

        $no=1;
        // $schedQRY = "SELECT * FROM inventory WHERE branch_id = ? AND date BETWEEN ? AND ? GROUP BY product_name ";
        $query = " SELECT * FROM damage_items WHERE branch_id = ? AND date BETWEEN ? AND ? ";
        $stmt=$conn->prepare($query);
        $stmt->bind_param('sss',$designation,$dayStrt,$dayEnd);
        $stmt->execute();
        $result=$stmt->get_result();
        if(mysqli_num_rows($result) > 0){
            $html = "<style>
                                @page { 
                                        margin: 20mm; 
                                        // size: 148.5mm 105mm;
                                        font-size: 11px;
                                        color: #9640b0;
                                    }
                                body { 
                                        margin: 0;
                                        // background: lightgrey;
                                        color: #9640b0;
                                    }
                
                                .pdf-table,td,tr{ 
                                    border: 1px solid black;
                                    border-collapse:collapse;
                                    // padding: 3px;    
                                    text-align: center;
                                    
                                }
                                label, small{
                                    font-weight: bold;
                                }
                                label{
                                    font-size: 14px;
                                }
                                input{
                                    background: transparent;
                                    border: transparent;
                                    border-bottom: 1px solid black;
                                } 
                                p{
                                    color: black;
                                    // padding: 0;
                                }
                                input{
                                    text-indent: 10px;
                                }
                            </style>";
                    $html .=    "<div style='margin-top: 5px'>
                                    <center>
                                        <label >D' 13TH SMILE DENTAL CLINIC</label><br>
                                        <small>2nd Floor Town Center, Himatagon, Saint Bernard, Southern Leyte</small><br>
                                        <small>TRECE DURAN M. MAGBANUA <I>-P</I></small><br>
                                        <label>Inventory History</label><br>
                                    </center>
                                </div>";
                    $html .=   "
                            <div style='margin: 10px'>    
                                <p style='font-size:16px; '>Month of: <input type='text' style='width: 20%;font-size: 12px' value='$m'></p>
                                <table class='pdf-table' style='width:100%;'>
                                    <tr>
                                        <td style='height: 18px; font-size: 16px'>
                                            #
                                        </td>
                                        <td style='height: 18px; font-size: 16px'>
                                            ITEM DESCRIPTION
                                        </td>
                                        <td>
                                            QUANTITY
                                        </td>
                                        <td>
                                            PRODUCT
                                        </td>
                                        <!-- <td>
                                            DATE
                                        </td>
                                        <td>
                                            Paid
                                        </td>
                                        <td>
                                            Balance
                                        </td>-->
                                    </tr>";

            while($row = $result->fetch_assoc()){
                    $html   .=      "<tr>
                                        <td style='height: 18px; font-size: 14px'> ".$no."</td>
                                        <td style='height: 18px; font-size: 14px'>".$row['description']."</td>
                                        <td style='height: 18px; font-size: 14px'>".$row['quantity']." </td>
                                        <td style='height: 18px; font-size: 14px'>".$row['product_name']." </td>
                                        <!--<td style='height: 18px; font-size: 14px'>".$row['date']." </td>
                                        <td style='height: 18px; font-size: 14px'> </td>
                                        <td> </td>-->
                                    </tr>";
                    // $total_sold += intval($row['sold']);
                    // $total_damaged += intval($row['damaged']);
                    // $total_qty += intval($row['remaining_qty']);
                    $no++;
            }
                    // $html .=   "
                    //                 <tr>
                    //                     <td style='height: 18px; font-size: 16px'>
                    //                         TOTAL:
                    //                     </td>
                    //                     <td style='height: 18px; font-size: 16px'>
                                            
                    //                     </td>
                    //                     <td style='height: 18px; font-size: 16px'>
                    //                         ".$total_sold."
                    //                     </td>
                    //                     <td style='height: 18px; font-size: 16px'>
                    //                         ".$total_damaged."
                    //                     </td>
                    //                     <td style='height: 18px; font-size: 16px'>
                    //                         ".$total_qty."
                    //                     </td>
                    //                     <!-- <td>
                    //                         Paid
                    //                     </td>
                    //                 <td>
                    //                         Balance
                    //                     </td>-->
                    //                 </tr>";  
                    
                    $html .=    "</table>
                            </div>";     

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait'); 
            $dompdf->render();
            $output = $dompdf->output();
            file_put_contents('pdf_damage/damage_item.pdf', $output);
            $dompdf->stream();  
            
        }
        else{
            echo "No data was found!";
        }

?>