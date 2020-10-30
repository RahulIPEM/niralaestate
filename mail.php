<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 * This uses traditional id & password authentication - look at the gmail_xoauth.phps
 * example to see how to use XOAUTH2.
 * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
 */

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require './vendor/autoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer();

try
{
    //code...
    $user_name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $user_email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $user_phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
    $content = filter_var($_POST["content"], FILTER_SANITIZE_STRING);

    if (empty($user_name))
    {
        $empty[] = "<b>Name</b>";
    }
    if (empty($user_email))
    {
        $empty[] = "<b>Email</b>";
    }
    if (empty($user_phone))
    {
        $empty[] = "<b>Phone Number</b>";
    }
    if (empty($content))
    {
        $empty[] = "<b>Comments</b>";
    }

    if (!empty($empty))
    {
        $output = json_encode(array(
            'type' => 'error',
            'text' => implode(", ", $empty) . ' Required!'
        ));
        die($output);
    }

    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL))
    { //email validation
        $output = json_encode(array(
            'type' => 'error',
            'text' => '<b>' . $user_email . '</b> is an invalid Email, please correct it.'
        ));
        die($output);
    }

    $html = "<table><tr><td>Name:</td><td>" . $user_name . "</td></tr><tr><td>Email:</td><td>" . $user_email . "</td></tr><tr><td>Mobile:</td><td>" . $user_phone . "</td></tr><tr><td>Comment:</td><td>" . $content . "</td></tr></table>";

    //Tell PHPMailer to use SMTP
    $mail->isSMTP();

    //Enable SMTP debugging
    // SMTP::DEBUG_OFF = off (for production use)
    // SMTP::DEBUG_CLIENT = client messages
    // SMTP::DEBUG_SERVER = client and server messages
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    //Set the hostname of the mail server
    $mail->Host = 'smtp.gmail.com';
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;

    //Set the encryption mechanism to use - STARTTLS or SMTPS
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;

    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = 'kundans48@gmail.com';

    //Password to use for SMTP authentication
    $mail->Password = 'ramadhar@123';

    //Set who the message is to be sent from
    $mail->setFrom($user_email, $user_name);

    //Set an alternative reply-to address
    $mail->addReplyTo($user_email, $user_name);

    //Set who the message is to be sent to
    $mail->addAddress('rs5802@gmail.com', 'John Doe');

    //Set the subject line
    $mail->Subject = 'Query from ' . $user_name;

    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    // $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
    $mail->isHTML(true); // Set email format to HTML
    $mail->Body = $html;

    //Replace the plain text body with one created manually
    $mail->AltBody = 'This is a plain-text message body';

    //Attach an image file
    // $mail->addAttachment('images/phpmailer_mini.png');
    //send the message, check for errors
    if (!$mail->send())
    {
        // echo 'Mailer Error: ' . $mail->ErrorInfo;
        $output = json_encode(array(
            'type' => 'error',
            'text' => 'Unable to send email, please contact to support.'
        ));
        die($output);
    }
    else
    {
        // echo 'Message sent!';
        $output = json_encode(array(
            'type' => 'message',
            'text' => 'Hi ' . $name . ', thank you for the Showing Interest. We will get back to you shortly.'
        ));
        die($output);
        //Section 2: IMAP
        //Uncomment these to save your message in the 'Sent Mail' folder.
        #if (save_mail($mail)) {
        #    echo "Message saved!";
        #}
        
    }
}
catch(\Throwable $th)
{
    //throw $th;
    echo json_encode(array(
        'type' => 'error',
        'text' => 'Unable to send email, please contact' . $th
    ));
}

//Section 2: IMAP
//IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
//Function to call which uses the PHP imap_*() functions to save messages: https://php.net/manual/en/book.imap.php
//You can use imap_getmailboxes($imapStream, '/imap/ssl', '*' ) to get a list of available folders or labels, this can
//be useful if you are trying to get this working on a non-Gmail IMAP server.
function save_mail($mail)
{
    //You can change 'Sent Mail' to any other folder or tag
    $path = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail';

    //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
    $imapStream = imap_open($path, $mail->Username, $mail->Password);

    $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
    imap_close($imapStream);

    return $result;
}
?>
