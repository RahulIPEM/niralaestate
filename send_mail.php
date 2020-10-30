<?php
try {
    //code...
    $return = $_POST;

    $user_name      = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $user_email     = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $user_phone     = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
    $content   = filter_var($_POST["content"], FILTER_SANITIZE_STRING);
    
    if(empty($user_name)) {
		$empty[] = "<b>Name</b>";		
	}
	if(empty($user_email)) {
		$empty[] = "<b>Email</b>";
	}
	if(empty($user_phone)) {
		$empty[] = "<b>Phone Number</b>";
	}	
	if(empty($content)) {
		$empty[] = "<b>Comments</b>";
	}
	
	if(!empty($empty)) {
		$output = json_encode(array('type'=>'error', 'text' => implode(", ",$empty) . ' Required!'));
        die($output);
	}
	
	if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){ //email validation
	    $output = json_encode(array('type'=>'error', 'text' => '<b>'.$user_email.'</b> is an invalid Email, please correct it.'));
		die($output);
	}


    $name=($_POST['name']);
    $email=($_POST['email']);
    $mobile=($_POST['phone']);
    $comment=($_POST['content']);
     
    require './PHPMailer-master/PHPMailerAutoload.php';

    $html="<table><tr><td>Name:</td><td>".$name."</td></tr><tr><td>Email:</td><td>".$email."</td></tr><tr><td>Mobile:</td><td>".$mobile."</td></tr><tr><td>Comment:</td><td>".$comment."</td></tr></table>";
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPAuth = true;
    $mail->Username='emp.investorhomez@gmail.com';
    $mail->Password='empmail2017!';
    $mail->SetFrom($email, $name);
    // change add address email to the receipient you want to sent email
    $mail->addAddress('rs5802@gmail.com', 'investorhomez'); // Add a recipient
    $mail->isHTML(true);
    $mail->Subject = 'Query from '. $name;
    $mail->Body = $html;

    $mail->AltBody = 'Name : ' . $name . ', Email : ' . $email . ', Mobile : ' . $mobile . ', Message:' . $comment;

    if (!$mail->send())
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Unable to send email, please contact to support.'));
        die($output);
    }
    else
    {
        $output = json_encode(array('type'=>'message', 'text' => 'Hi '.$name .', thank you for the Showing Interest. We will get back to you shortly.'));
        die($output);
    }
} catch (Throwable $th) {
    echo json_encode(array('type'=>'error', 'text' => 'Unable to send email, please contact'. $th));
}
?>
