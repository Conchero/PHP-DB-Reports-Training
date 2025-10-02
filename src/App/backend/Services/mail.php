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
            $mail->Host = "live.smtp.mailtrap.io";
            $mail->SMTPAuth = true;
            $mail->Username = "api";
            $mail->Password = "19386964cf65c0739fe63a7bbf9ead81";
            $mail->SMTPSecure = 'tls';
            $mail->Port = 2525;

            // Sender and recipient settings
            $mail->setFrom('hello@demomailtrap.co', 'Mailtrap');
            $mail->addReplyTo('hello@demomailtrap.co', 'Mailtrap');
            $mail->addAddress('florian.parra@hotmail.com', 'Flo'); // Replace with recipient's email and name

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
