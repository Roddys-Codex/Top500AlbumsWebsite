<?php


// Can be setup to use the QUB O365 SMTP Mail Servers.
// You must use your student account to authenticate.
// For Multi-factor Authenication setup and use an O365 App Password.
//
// Replace the placeholders:
// <SENDEREMAILADDRESS> your QUB email address
// <SENDERNAME> your QUB email address
// 
// <RECIPENTEMAIL> a test email address to send to - use your qub email address address
// <RECIPIENTNAME> a recipient name to test with

$emailAddress = $_POST['emailAddressSend'];

$endpointUser = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?findUserWithEmail=true&emailAddress=$emailAddress";

$resourceUser = file_get_contents($endpointUser, false, stream_context_create());

$dataUser = json_decode($resourceUser, true);

$userName = $dataUser['username'];
$userID = $dataUser['user_id'];

$length = 10;    
$temporaryPassword = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);

$endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?updateSettings";

    $postdata = http_build_query(
        array(
            'currentUserID' => $userID,
            'newPassword' => $temporaryPassword
        )
    );

    $opts = array(

        'http' => array(
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content'=> $postdata
        )
    );

$context = stream_context_create($opts);
$resource = file_get_contents($endpoint, false, $context);

//Use the following exact namespaces no matter which directory your phpmailer files are in.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Now include the followingfiles based on the correct file path. 
// SMTP.php is required to enable SMTP.
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true); 

$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.qub.ac.uk';  				  // Specify QUB O365 SMTP servers
$mail->SMTPAuth = false;                              // Enable SMTP authentication
// $mail->Username = 'droddy03';         // SMTP username
// $mail->Password = 'Tour password for SMTP';         // SMTP password 
// $mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
$mail->Port = 25;                                     // TCP port to connect to

//FROM
$mail->From = 'droddy03@qub.ac.uk';                 // user your QUB email address
$mail->FromName = 'top500AlbumsWebsite';                     // use your name or app name

// RECIPIENTS
$mail->addAddress($emailAddress, $userName); // Add a recipient - use your address to test
//$mail->addAddress('ellen@example.com');                // Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

// MESSAGE DETAILS
$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');  // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Password Reset';
$mail->Body    = 'Your temporary password is <b>'.$temporaryPassword.'</b> and your username is "'.$userName.'".';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}