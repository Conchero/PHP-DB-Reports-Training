<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require "{$_SERVER["DOCUMENT_ROOT"]}/vendor/autoload.php";


class MailService
{

    public static function CreateAndSendMail(string $title, string $body)
    {
        try {
            $mail = new PHPMailer(true);

            // SMTP config
            $mail->isSMTP();
            $mail->Host = "sandbox.smtp.mailtrap.io";
            $mail->SMTPAuth = true;
            $mail->Username = "c872fc36f12158";
            $mail->Password = "e7264d2ecabc67";
            $mail->SMTPSecure = 'tls';
            $mail->Port = 2525;

            // Sender and recipient settings
            $mail->setFrom('info@mailtrap.io', 'Mailtrap');
            $mail->addReplyTo('info@mailtrap.io', 'Mailtrap');
            $mail->addAddress('recipient1@mailtrap.io', 'Tim'); // Replace with recipient's email and name

            // Setting the email content
            $mail->isHTML(false); // Set email format to plain text
            $mail->Subject = "{$title}";
            $mail->Body = "{$body}";

            if ($mail->send()) {
                echo 'Message has been sent';
            } else {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
    }
}
