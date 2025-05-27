<?php
    session_start();
    include 'config/connection.php';
    require_once __DIR__.'/vendor/autoload.php';
    
    use Infobip\Configuration;
    use  Infobip\Api\SmsApi;
    use  Infobip\Model\SmsDestination;
    use  Infobip\Model\SmsTextualMessage;
    use  Infobip\Model\SmsAdvancedTextualRequest;
    use Infobip\Resources\SMS\Models\Destination;
    
    try{
        $b_name="";
        $_appointment_date="";
        $branch="";
        $p_name="";
        $patient_id ="";
    
        $branch = base64_decode(urldecode($_GET['b']));
        $_appointment_date = base64_decode(urldecode(($_GET['d'])));
    
        if(isset($_GET['patient_id'])){
            $patient_id = base64_decode(urldecode(($_GET['patient_id'])));
        }

        $_appointment_date = "$_appointment_date";

        $stmt0 = $conn->prepare("SELECT * FROM branch WHERE branch_id = ?");
        $stmt0->bind_param('s',$branch);
        $stmt0->execute();
        $result0=$stmt0->get_result();
        $row0=$result0->fetch_assoc();
        $location = strtoupper($row0['location']);

        // $apikey = 'a2473fa68bb306ea4ef450c9de65722f-fcf1a136-5cf6-4058-a3ce-a4e279200905';
        $apikey = 'ecd118b79b6404e350513c4b04062c7c-5e2330ad-5932-45d8-8798-87baf01a2442';
        $host = '51pqqj.api.infobip.com';
        // $host = '9kd91v.api.infobip.com';
        $number = "+639128819773";
        // $number = "+639513579589";
        $_message = "Good day, we have set your appointment at 'D 13th Smile Dental Clinic-$location on $_appointment_date. See you soon!";

        $configuration = new Configuration(host: $host, apiKey: $apikey);

        $api = new SmsApi(config: $configuration);

        $destination = new SmsDestination(to: $number);

        $message = new SmsTextualMessage(
            destinations: [$destination],
            text: $_message
        );

        $req = new SmsAdvancedTextualRequest(messages:[$message]);
        
        $res = $api->sendSmsMessage($req);
    
        echo "Message sent successfully!";
    
        
        header('location: home.php');    
    }
    catch(Exception $e){
        echo $e->getMessage();
    }

?>

