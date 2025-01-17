<?php
$to = "office@ajinfotek.in,umersiddiqui62@gmail.com";
$subject = "Vandana";
// $txt = "Name: $Name<br>Email: $Email<br>Phone: $Phone<br>Message: $Message";
$txt = "hello";


$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


// Send email and insert data
if (mail($to, $subject, $txt, $headers)) {
    
    echo json_encode(['status' => 'success', 'message' => 'Your message has been sent successfully.']);
    
    exit;
} else {
    $response = ['status' => 'error', 'message' => 'Error sending email.'];
    exit;
}
?>
