<?php
    session_start();
    include 'config/connection.php';

    // $id = $_SESSION['id'];
    // $position = $_SESSION['position'];

    $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['patient_id'])));  

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

        // set the source file
        $pdf->setSourceFile("pdf_patient_record/caps.pdf");

        // import page 1
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

        echo $pdf->Output('F', 'pdf_patient_record/doc_'.$id.'.pdf');
        }   
    }    
?>
<?php
    // session_start();
    // include 'config/connection.php';

    // $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['patient_id'])));

    // require_once('fpdf.php');
    // require_once('fpdi/src/autoload.php');

    // use setasign\Fpdi\FPDI;

    // // $pdf = new FPDF();
    // class _PDF extends FPDI
    // {
    // var $angle=0;

    //     function Rotate($angle,$x=-1,$y=-1)
    //     {
    //         if($x==-1)
    //             $x=$this->x;
    //         if($y==-1)
    //             $y=$this->y;
    //         if($this->angle!=0)
    //             $this->_out('Q');
    //         $this->angle=$angle;
    //         if($angle!=0)
    //         {
    //             $angle*=M_PI/180;
    //             $c=cos($angle);
    //             $s=sin($angle);
    //             $cx=$x*$this->k;
    //             $cy=($this->h-$y)*$this->k;
    //             $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    //         }
    //     }

    //     function _endpage()
    //     {
    //         if($this->angle!=0)
    //         {
    //             $this->angle=0;
    //             $this->_out('Q');
    //         }
    //         parent::_endpage();
    //     }

    //     function RotatedText($x,$y,$txt,$angle)
    //     {
    //         //Text rotated around its origin
    //         $this->Rotate($angle,$x,$y);
    //         $this->Text($x,$y,$txt);
    //         $this->Rotate(0);
    //     }

    // // function RotatedImage($file,$x,$y,$w,$h,$angle)
    // // {
    // //     //Image rotated around its upper-left corner
    // //     $this->Rotate($angle,$x,$y);
    // //     $this->Image($file,$x,$y,$w,$h);
    // //     $this->Rotate(0);
    // // }
    // }
    
    // $stmt1 = $conn->prepare("SELECT * FROM register_patient WHERE register_id = ? ");
    // $stmt1->bind_param('s', $id);
    // $stmt1->execute();
    // $result1 = $stmt1->get_result();
    // if(mysqli_num_rows($result1) > 0){
    //     if($row1 = $result1->fetch_assoc()){
    //     // initiate FPDI
    //     $pdf = new _PDF('P');
        
    //     // add a page
    //     $pdf->AddPage();

    //     // set the source file
    //     $pdf->setSourceFile("Doc1.pdf");

    //     // import page 1
    //     $tplId = $pdf->importPage(1);
    //     // use the imported page and place it at point 10,10 with a width of 100 mm
    //     $pdf->useTemplate($tplId, 7, 5, 200);

    //     // Now, you can add additional content on top of the existing PDF page
    //     $pdf->SetFont('Arial', 'B', 10);
    //     // $pdf->SetTextColor(148, 0, 211);
    //     //Chief Complaint
    //     // $pdf->SetXY(50, 10); // Set position where text will be inserted
    //     // $pdf->Write(10, 'This is some added text on top of the existing PDF.');
    //     // //Other findings
    //     // $pdf->SetXY(49, 19); // Set position where text will be inserted
    //     // $pdf->Write(10, 'This is some added text on top of the existing PDF.');
    //     // // Now, you can add additional content on top of the existing PDF page
    //     $pdf->SetFont('Arial', 'B', 9);
    //     // $pdf->SetTextColor(148, 0, 211); 
    //     //Treatment Rendered
    //     $y=40;
    //     $y_1=40;
    //     $fee = '';
    //     $balance = '';
    //     $query0 = mysqli_query($conn, "SELECT * FROM patient_records WHERE patient_id = '$id' ");
    //     while($row0=mysqli_fetch_assoc($query0)){
    //         //Date
    //         $pdf->SetXY(9, $y); // Set position where text will be inserted
    //         $pdf->Write(10, $row0['date']);

    //         $services = explode(',',$row0['service_id']);
    //         $teeth = explode(',',$row0['tooth_no']);
    //         $i=0;

    //         foreach($services as $service){
    //             $query=mysqli_query($conn, "SELECT * FROM services WHERE service_id='$service' ");
    //             $row=mysqli_fetch_assoc($query);
         
    //             if($y > 278){
    //                 $y=40;
    //                 $pdf->AddPage();
    //                 $tplId = $pdf->importPage(1);
    //                 $pdf->useTemplate($tplId, 2, 5, 200);
    //                 $pdf->SetFont('Arial', 'B', 10);
    //                 $pdf->SetTextColor(148, 0, 211);
    //                 $pdf->SetXY(67, $y); // Set position where text will be inserted
    //                 $pdf->Write(10, $row['service_name']);
    //             }
    //             else{
    //                 $pdf->SetXY(67, $y); // Set position where text will be inserted
    //                 $pdf->Write(10, $row['service_name']);
    //             }

    //             $y+=7;
    //         }

    //         foreach($teeth as $tooth){
    //             #Tooth
    //             $pdf->SetXY(31, $y_1); // Set position where text will be inserted
    //             $pdf->Write(10, $tooth); 

    //             $y_1+=7; 
    //         } 
    //         $fee = $row0['fee'];
    //         $balance = $row0['balance'];
    //     }

    //     //Fee
    //     $pdf->SetXY(136, 40); // Set position where text will be inserted
    //     $pdf->Write(10, $fee);
    //     //Paid
    //     $pdf->SetXY(156, 40); // Set position where text will be inserted
    //     $pdf->Write(10, $fee);
    //     //Balance
    //     $pdf->SetXY(175, 40); // Set position where text will be inserted
    //     $pdf->Write(10, $balance);


    //     $pdf->AddPage();
        
    //     // import page 2
    //     $tplId = $pdf->importPage(2);
    //     // use the imported page and place it at point 10,10 with a width of 100 mm
    //     $pdf->useTemplate($tplId, 5, 8, 200);

    //     // Now, you can add additional content on top of the existing PDF page
    //     $pdf->SetFont('Arial', 'B', 11);
    //     // $pdf->SetTextColor(148, 0, 211);
    //     // $pdf->SetXY(50, 50); // Set position where text will be inserted
    //     // $pdf->Write(10, 'This is some added text on top of the existing PDF.');
        
    //     //Patient Name
    //     $pdf->RotatedText(180,28,strtoupper($row1['first_name']),-90);
    //     //Patient Address
    //     $pdf->RotatedText(180,130,strtoupper($row1['address']),-90);
    //     //Patient Telephone
    //     $pdf->RotatedText(180, 250,'Hello!',-90);
    //     //Patient Age
    //     $pdf->RotatedText(173,25,$row1['age'],-90);
    //     //Patient Sex
    //     $pdf->RotatedText(173,60,$row1['sex'],-90);
    //     //Patient Status
    //     $pdf->RotatedText(173,118,$row1['marital_status'],-90);
    //     //Patient Occupation
    //     $pdf->RotatedText(173,180,$row1['occupation'],-90);
    //     //Patient Phone Number
    //     $pdf->RotatedText(173,250,$row1['contact_no'],-90);
    //     //Patient Office Address
    //     $pdf->RotatedText(166,48,$row1['office_address'],-90);
    //     //Patient Office Telephone No.
    //     $pdf->RotatedText(166,215,'',-90);
    //     //Date
    //     $pdf->RotatedText(167,260,'',-90);
    //     //Set Fontsize 
    //     $pdf->SetFont('Arial', 'B', 20);
    //     // Se Font color
    //     $pdf->SetTextColor(255, 0, 0); //red
    //     $query0 = mysqli_query($conn, "SELECT * FROM patient_records WHERE patient_id = '$id' ");
    //     while($row0=mysqli_fetch_assoc($query0)){
    //         $teeth = explode(",",$row0['tooth_no']);
    //         foreach($teeth as $tooth){
    //             switch($tooth){
    //                 // upper left teeth
    //                 case '18':
    //                     $pdf->RotatedText(133,102,'X',-90); 
    //                     break;
    //                 case '17':
    //                     $pdf->RotatedText(133,115,'X',-90); 
    //                     break;
    //                 case '16':
    //                     $pdf->RotatedText(133,132,'X',-90); 
    //                     break;
    //                 case '15':
    //                     $pdf->RotatedText(133,145,'X',-90); 
    //                     break;
    //                 case '14':
    //                     $pdf->RotatedText(133,154,'X',-90); 
    //                     break;
    //                 case '13':
    //                     $pdf->RotatedText(133,165,'X',-90); 
    //                     break;
    //                 case '12':
    //                     $pdf->RotatedText(133,174,'X',-90); 
    //                     break;
    //                 case '11':
    //                     $pdf->RotatedText(133,186,'X',-90); 
    //                     break;
    //                 // upper left teeth
    //                 // upper right teeth
    //                 case '21':
    //                     $pdf->RotatedText(133,199,'X',-90); 
    //                     break;
    //                 case '22':
    //                     $pdf->RotatedText(133,213,'X',-90); 
    //                     break;
    //                 case '23':
    //                     $pdf->RotatedText(133,222,'X',-90); 
    //                     break;
    //                 case '24':
    //                     $pdf->RotatedText(133,234,'X',-90); 
    //                     break;
    //                 case '25':
    //                     $pdf->RotatedText(133,243,'X',-90); 
    //                     break;
    //                 case '26':
    //                     $pdf->RotatedText(133,253,'X',-90); 
    //                     break;
    //                 case '27':
    //                     $pdf->RotatedText(133,267,'X',-90); 
    //                     break;
    //                 case '28':
    //                     $pdf->RotatedText(133,280,'X',-90); 
    //                     break;
    //                 // upper right teeth

    //                 // lower left teeth
    //                 case '48':
    //                     $pdf->RotatedText(113,102,'X',-90); 
    //                     break;
    //                 case '47':
    //                     $pdf->RotatedText(113,115,'X',-90); 
    //                     break;
    //                 case '46':
    //                     $pdf->RotatedText(113,132,'X',-90); 
    //                     break;
    //                 case '45':
    //                     $pdf->RotatedText(113,145,'X',-90); 
    //                     break;
    //                 case '44':
    //                     $pdf->RotatedText(113,154,'X',-90); 
    //                     break;
    //                 case '43':
    //                     $pdf->RotatedText(113,165,'X',-90); 
    //                     break;
    //                 case '42':
    //                     $pdf->RotatedText(113,174,'X',-90); 
    //                     break;
    //                 case '41':
    //                     $pdf->RotatedText(113,186,'X',-90); 
    //                     break;
    //                 // lower left teeth
    //                 // lower right teeth
    //                 case '31':
    //                     $pdf->RotatedText(113,199,'X',-90); 
    //                     break;
    //                 case '32':
    //                     $pdf->RotatedText(113,213,'X',-90); 
    //                     break;
    //                 case '33':
    //                     $pdf->RotatedText(113,222,'X',-90); 
    //                     break;
    //                 case '34':
    //                     $pdf->RotatedText(113,234,'X',-90); 
    //                     break;
    //                 case '35':
    //                     $pdf->RotatedText(113,243,'X',-90); 
    //                     break;
    //                 case '36':
    //                     $pdf->RotatedText(113,253,'X',-90); 
    //                     break;
    //                 case '37':
    //                     $pdf->RotatedText(113,267,'X',-90); 
    //                     break;
    //                 case '38':
    //                     $pdf->RotatedText(113,280,'X',-90); 
    //                     break;
    //                 // lower right teeth
    //                 ################## SMALL TEETH ################### SMALL TEETH 
    //                 // upper left teeth
    //                 case '55':
    //                     $pdf->RotatedText(59,133,'X',-90); 
    //                     break;
    //                 case '54':
    //                     $pdf->RotatedText(59,152,'X',-90); 
    //                     break;
    //                 case '53':
    //                     $pdf->RotatedText(59,163,'X',-90); 
    //                     break;
    //                 case '52':
    //                     $pdf->RotatedText(59,175,'X',-90); 
    //                     break;
    //                 case '51':
    //                     $pdf->RotatedText(59,186,'X',-90); 
    //                     break;
    //                 // upper left teeth
    //                 // upper right teeth
    //                 case '61':
    //                     $pdf->RotatedText(59,197,'X',-90); 
    //                     break;
    //                 case '62':
    //                     $pdf->RotatedText(59,209,'X',-90); 
    //                     break;
    //                 case '63':
    //                     $pdf->RotatedText(59,220,'X',-90); 
    //                     break;
    //                 case '64':
    //                     $pdf->RotatedText(59,234,'X',-90); 
    //                     break;
    //                 case '65':
    //                     $pdf->RotatedText(59,251,'X',-90); 
    //                     break;
    //                 // upper right teeth

    //                 // lower left teeth
    //                 case '85':
    //                     $pdf->RotatedText(39,134,'X',-90); 
    //                     break;
    //                 case '84':
    //                     $pdf->RotatedText(39,151,'X',-90); 
    //                     break;
    //                 case '83':
    //                     $pdf->RotatedText(39,163,'X',-90); 
    //                     break;
    //                 case '82':
    //                     $pdf->RotatedText(39,175,'X',-90); 
    //                     break;
    //                 case '81':
    //                     $pdf->RotatedText(39,185,'X',-90); 
    //                     break;
    //                 // lower left teeth
    //                 // lower right teeth
    //                 case '71':
    //                     $pdf->RotatedText(39,196,'X',-90); 
    //                     break;
    //                 case '72':
    //                     $pdf->RotatedText(39,207,'X',-90); 
    //                     break;
    //                 case '73':
    //                     $pdf->RotatedText(39,219,'X',-90); 
    //                     break;
    //                 case '74':
    //                     $pdf->RotatedText(39,233,'X',-90); 
    //                     break;
    //                 case '75':
    //                     $pdf->RotatedText(39,250,'X',-90); 
    //                     break;
    //                 // lower right teeth
    //             }
    //         }
    //     }

    //     //Line
    //     // $pdf->SetLineWidth(1);
    //     // $pdf->SetDrawColor(148, 0, 211);
    //     // $pdf->Line(50,100,50,90);
        
    //     $pdf->Output('F', 'pdf_patient_record/doc_'.$id.'.pdf');

    //     // $pdf->SetLineWidth(20);
    //     // $pdf->SetXY(50, 70);
    //     // $pdf->Write(10, 'You can add more content here, like images, etc.');
    //     }   
    // }    
?>