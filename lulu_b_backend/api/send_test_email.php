<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ Load PHPMailer
require "../phpmailer/src/PHPMailer.php";
require "../phpmailer/src/SMTP.php";
require "../phpmailer/src/Exception.php";

$mail = new PHPMailer(true);

try {
    // ✅ SMTP Debug (shows errors)
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'html';

    // ✅ SMTP Settings
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;

    // ✅ Your Gmail and App Password
    $mail->Username = "lulub8929@gmail.com";     // <<< CHANGE THIS
    $mail->Password = "efbx vfok xjuu wwqr";     // <<< YOUR APP PASSWORD

    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    // ✅ Sender Email
    $mail->setFrom("lulub8929@gmail.com", "Lulu B Website"); // <<< CHANGE THIS

    // ✅ Who receives the test email
    $mail->addAddress("lulub8929@gmail.com"); // <<< SEND TO YOURSELF

    // ✅ Email Content
    $mail->isHTML(true);
    $mail->Subject = "✅ Test Email From Lulu B Website";
    $mail->Body = "
        <h2>Email Test Successful ✅</h2>
        <p>This is a test email sent from your PHP backend.</p>
    ";

    // ✅ Send email
    $mail->send();

    echo "✅ Test email sent! Check your inbox or spam folder.";
} 
catch (Exception $e) {
    echo "❌ Email could not be sent.<br>";
    echo "Mailer Error: " . $mail->ErrorInfo;
}
?>
