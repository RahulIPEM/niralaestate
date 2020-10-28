<?php
try {
    //code...
    $return = $_POST;

    $name=($_POST['name']);
    $email=($_POST['email']);
    $mobile=($_POST['phone']);
    $comment=($_POST['content']);

    require './PHPMailer-master/PHPMailerAutoload.php';

    $html="<table><tr><td>Name</td><td>".$name."</td></tr><tr><td>Email</td><td>".$email."</td></tr><tr><td>Mobile</td><td>".$mobile."</td></tr><tr><td>Comment</td><td>".$comment."</td></tr></table>";
    $mail = new PHPMailer(true);
    $mail->isSMTP(true);
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username="kundans48@gmail.com";
    $mail->Password="*********";
    $mail->SetFrom($email, $name);
    // change add address email to the receipient you want to sent email
    $mail->addAddress('rs5802@gmail.com', 'Rahul Sharma'); // Add a recipient
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
} catch (\Throwable $th) {
    echo json_encode(array('type'=>'error', 'text' => 'Unable to send email, please contact'. $th));
}
?>
