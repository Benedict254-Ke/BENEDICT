<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ Correct PHPMailer paths (based on your folder screenshot)
require "../phpmailer/src/PHPMailer.php";
require "../phpmailer/src/SMTP.php";
require "../phpmailer/src/Exception.php";

require "../config/db.php";

function notifySubscribers($title, $description, $demo_link, $github_link) {
    global $conn;

    // ✅ Get all subscribers
    $result = $conn->query("SELECT email FROM newsletter");

    if ($result->num_rows === 0) {
        return;
    }

    // ✅ Send email to each subscriber
    while ($row = $result->fetch_assoc()) {
        $email = $row['email'];

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 2;  // Show SMTP errors
        $mail->Debugoutput = 'html'; // Format output

    
        try {
            // ✅ SMTP config (edit these)
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "lulub8929@gmail.com";      // ✅ change
            $mail->Password = "efbx vfok xjuu wwqr";         // ✅ change
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;

            // ✅ Email content
            $mail->setFrom("lulub8929@gmail.com", "Lulu B Newsletter");
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "🔥 New Project Released: $title";

            $mail->Body = "
                <h2>New Project Published!</h2>
                <p><strong>$title</strong></p>
                <p>$description</p>

                <p>
                    🔗 <a href='$demo_link'>Live Demo</a><br>
                    💻 <a href='$github_link'>GitHub Repo</a>
                </p>

                <br>
                <p>Regards,<br><strong>Lulu B</strong></p>
            ";

            $mail->send();
        } catch (Exception $e) {
            // You can log error if needed
        }
    }
}
?>
