<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    
    require_once('fpdf/fpdf.php');
    require_once('fpdf/fpdi/src/autoload.php');  
   
    // use setasign\fpdf\fpdf; 
    use setasign\Fpdi\Fpdi;

    // initiate FPDI
    $pdf = new FPDI();
    
    $stmt1 = $conn->prepare("SELECT * FROM register_patient WHERE register_id = ? ");
    $stmt1->bind_param('s', $id);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    
    if(mysqli_num_rows($result1) > 0){
        while($row1 = $result1->fetch_assoc()){

            // add a page
            $pdf->AddPage();
    
    //         // set the source file
            $pdf->setSourceFile("pdf_patient_record/caps.pdf");
    
    //         // import page 1
            $tplId = $pdf->importPage(1);
            
            // use the imported page and place it at point 10,10 with a width of 100 mm
            $pdf->useTemplate($tplId, 7, 5, 200);
    
            // Now, you can add additional content on top of the existing PDF page
            $pdf->SetFont('Arial', '', 9);
    
            //Name
            $pdf->SetXY(28, 58); // Set position where text will be inserted
            $pdf->Write(10, strtoupper($row1['first_name']." ".$row1['last_name']));

            //Address
            $pdf->SetXY(89, 58); // Set position where text will be inserted
            $pdf->Write(10, strtoupper($row1['address']));
            //Contact #
            // $pdf->SetXY(162, 57); // Set position where text will be inserted
            // $pdf->Write(10, $row1['contact_no']);
            //Age
            $pdf->SetXY(28, 64); // Set position where text will be inserted
            $pdf->Write(10, $row1['age']);
            //Sex
            $pdf->SetXY(48, 64); // Set position where text will be inserted
            $pdf->Write(10, strtoupper($row1['sex']));
            //Marital Status
            $pdf->SetXY(83, 64); // Set position where text will be inserted
            $pdf->Write(10, strtoupper($row1['marital_status']));
            //Occupation
            $pdf->SetXY(120, 64); // Set position where text will be inserted
            $pdf->Write(10, strtoupper($row1['occupation']));
            //Contact #
            $pdf->SetXY(162, 63); // Set position where text will be inserted
            $pdf->Write(10, $row1['contact_no']);
            //Office Address
            $pdf->SetXY(40, 69); // Set position where text will be inserted
            $pdf->Write(10, strtoupper($row1['office_address']));
            //Date
            $pdf->SetXY(169, 68); // Set position where text will be inserted
            $pdf->Write(10, date('m-d-Y'));
    
    
            $pdf->SetFont('Arial', 'B', 20);
            // Se Font color
            $pdf->SetTextColor(255, 0, 0); //red
    
    
            $query0 = "SELECT * FROM patient_records WHERE patient_id = ? ";
            $stmt0 = $conn->prepare($query0);
            $stmt0->bind_param('s',$id);
            $stmt0->execute();
            $result0=$stmt0->get_result();
            while($row0=$result0->fetch_assoc()){
    
                $teeth = explode(",",$row0['tooth_no']);
                foreach($teeth as $tooth){
                    switch($tooth){
                        // upper left teeth
                        case '18':
                            $pdf->SetXY(15, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X'); 
                            break;
                        case '17':
                            $pdf->SetXY(29, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X'); 
                            break;
                        case '16':
                            $pdf->SetXY(44, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X'); 
                            break;
                        case '15':
                            $pdf->SetXY(55, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X'); 
                            break;
                        case '14':
                            $pdf->SetXY(65, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '13':
                            $pdf->SetXY(75, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X'); 
                            break;
                        case '12':
                            $pdf->SetXY(86, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '11':
                            $pdf->SetXY(97, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X'); 
                            break;
                        // upper left teeth
                        // upper right teeth
                        case '21':
                            $pdf->SetXY(111, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '22':
                            $pdf->SetXY(122, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '23':
                            $pdf->SetXY(134, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '24':
                            $pdf->SetXY(144, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '25':
                            $pdf->SetXY(154, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '26':
                            $pdf->SetXY(166, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X'); 
                            break;
                        case '27':
                            $pdf->SetXY(179, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '28':
                            $pdf->SetXY(191, 82); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        // upper right teeth
    
                        // lower left teeth
                        case '48':
                            $pdf->SetXY(15, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '47':
                            $pdf->SetXY(29, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '46':
                            $pdf->SetXY(45, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '45':
                            $pdf->SetXY(57, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '44':
                            $pdf->SetXY(66, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '43':
                            $pdf->SetXY(76, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '42':
                            $pdf->SetXY(86, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '41':
                            $pdf->SetXY(97, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        // lower left teeth
                        // lower right teeth
                        case '31':
                            $pdf->SetXY(111, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '32':
                            $pdf->SetXY(122, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '33':
                            $pdf->SetXY(133, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '34':
                            $pdf->SetXY(143, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '35':
                            $pdf->SetXY(153, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '36':
                            $pdf->SetXY(165, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '37':
                            $pdf->SetXY(178, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '38':
                            $pdf->SetXY(191, 143); // Set position where text will be inserted
                            $pdf->Write(10, 'X'); 
                            break;
                        // lower right teeth
                        ################## SMALL TEETH ################### SMALL TEETH #############  SMALL TEETH
                        // upper left teeth
                        case '55':
                            $pdf->SetXY(45, 163); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '54':
                            $pdf->SetXY(65, 163); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '53':
                            $pdf->SetXY(77, 163); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '52':
                            $pdf->SetXY(89, 163); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '51':
                            $pdf->SetXY(99, 163); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        // upper left teeth
                        // upper right teeth
                        case '61':
                            $pdf->SetXY(110, 163); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '62':
                            $pdf->SetXY(120, 163); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                        case '63':
                            $pdf->SetXY(133, 163); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '64':
                            $pdf->SetXY(145, 163); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '65':
                            $pdf->SetXY(162, 163); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        // upper right teeth
    
                        // lower left teeth
                        case '85':
                            $pdf->SetXY(46, 219); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '84':
                            $pdf->SetXY(64, 219); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '83':
                            $pdf->SetXY(77, 219); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '82':
                            $pdf->SetXY(88, 219); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '81':
                            $pdf->SetXY(99, 219); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        // lower left teeth
                        // lower right teeth
                        case '71':
                            $pdf->SetXY(110, 219); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '72':
                            $pdf->SetXY(121, 219); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '73':
                            $pdf->SetXY(133, 219); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '74':
                            $pdf->SetXY(145, 219); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        case '75':
                            $pdf->SetXY(145, 219); // Set position where text will be inserted
                            $pdf->Write(10, 'X');
                            break;
                        // lower right teeth
                    }
                }
            }
    
            $pdf->AddPage();
    
            $pdf->setSourceFile("pdf_patient_record/Date.pdf");
            // import page 1
            $tplId = $pdf->importPage(1);
            // use the imported page and place it at point 10,10 with a width of 100 mm
            $pdf->useTemplate($tplId, 5, 8, 200);
    
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(0, 0, 0); //red
            //Treatment Rendered
            $y=42;
            $y_1=42;
            $query0 = mysqli_query($conn, "SELECT * FROM patient_records WHERE patient_id = '$id' ");
            while($row0=mysqli_fetch_assoc($query0)){
                //Name
                $pdf->SetXY(15, $y); // Set position where text will be inserted
                $pdf->Write(10, $row0['date']);
                
                //Fee
                $pdf->SetXY(134, $y); // Set position where text will be inserted
                $pdf->Write(10, $row0['fee']);
                //Paid
                $pdf->SetXY(155, $y); // Set position where text will be inserted
                $pdf->Write(10, $row0['fee']);
                //Balance
                $pdf->SetXY(175, $y); // Set position where text will be inserted
    
                // $pdf->Write(10, $row0['balance']);
    
                $services = explode(',',$row0['service_id']);
                $teeth = explode(',',$row0['tooth_no']);
                $i=0;
    
                foreach($services as $service){
                    $query=mysqli_query($conn, "SELECT * FROM services WHERE service_id='$service' ");
                    $row=mysqli_fetch_assoc($query);
                    
                    $pdf->SetXY(80, $y); // Set position where text will be inserted
                    $pdf->Write(10, $row['service_name']);
             
                    $y+=7;
                }
    
                foreach($teeth as $tooth){
                    #Tooth
                    $pdf->SetXY(43, $y_1); // Set position where text will be inserted
                    $pdf->Write(10, $tooth); 
    
                    $y_1+=7; 
                } 
            } 
        } 
        $pdf->Output('F', 'pdf_patient_record/doc_'.$id.'.pdf');  
    }    
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">

        <div class="card mt-3">
            <div class="card-header">
            <h4 class="">Health Record</h4>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row float-right">
                            </div>
                        </div>
                        <div class="card-body">
                            <!--  -->
                            <div class="row">
                               
                                <iframe id="frame" src="pdf_patient_record/doc_<?php echo $id; ?>.pdf" alt="PDF not available" frameborder="0" style="width:100%; height: 90vh"></iframe>  
                            </div>
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
        // $.ajax({
        //     type:'POST',
        //     url: "view-PDF.php",
        // }).done(function(data){
        //     alert(data);
        // })
    })

</script>