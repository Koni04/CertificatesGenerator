<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
     // Debuggin State
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;

     // SMTP Gmail Host Domain
    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = "smtp.gmail.com";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    //SMPT App password

     $mail->Username = "delgadoargie04@gmail.com"; // your gmail.com
     $mail->Password = "prbcpvspdvqvdamf"; // you apps password/needed to access 2 step verification

    // Attach the file
    $attachmentPath = 'path/to/your/file.pdf'; // Replace with the actual file path
    $mail->addAttachment($attachmentPath);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Subject';
    $mail->Body    = 'Message body';

    // Send email
    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}