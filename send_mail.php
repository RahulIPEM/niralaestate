<?php
$msg="";

	$name=($_POST['name']);
	$email=($_POST['email']);
	$mobile=($_POST['phone']);
    $comment=($_POST['content']);
	
	$html="<table><tr><td>Name</td><td>".$name."</td></tr><tr><td>Email</td><td>".$email."</td></tr><tr><td>Mobile</td><td>".$mobile."</td></tr><tr><td>Comment</td><td>".$comment."</td></tr></table>";
	
	include('./PHPMailer-master/PHPMailerAutoload.php');
	$mail=new PHPMailer(true);
	$mail->isSMTP();
	$mail->Host="smtp.gmail.com";
	$mail->Port=587;
	$mail->SMTPSecure="tls";
	$mail->SMTPAuth=true;
	$mail->Username="kundans48@gmail.com";
	$mail->Password="ramadhar@123";
	$mail->SetFrom($name);
	$mail->addAddress("rs5802@gmail.com");
	$mail->IsHTML(true);
	$mail->Subject="New Contact Us";
	$mail->Body=$html;
	$mail->SMTPOptions=array('ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false,
		'allow_self_signed'=>false
    ));
	if($mail->send()){
        echo json_encode(array('type'=>'message', 'text' => 'Hi '.$name .', thank you for the Showing Interest. We will get back to you shortly.'));
	    // echo $output;
		//echo "Mail send";
	}else{
        echo json_encode(array('type'=>'error', 'text' => 'Unable to send email, please contact'));
	    // echo $output;
		//echo "Error occur";
	}
?>