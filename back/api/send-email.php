<?php

    require __DIR__ . "/../vendor/autoload.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    class Sender {

        public function send_email($to, $message) {
            //* Error may appear. Cause it is work only within container
            $mail = new PHPMailer(true);

            // Setting smtp information
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->IsHTML(true); 

            // Information of smtp server and user setting in .env file
            $mail->Host = $_ENV['SMTP_HOST'];
            //* notice that if you use SSL google port, need to change encryption type
            $mail->Encryption = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['SMPT_PORT'];

            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASSWORD'];

            $mail->setFrom($_ENV['SMTP_FROM'], $_ENV['SMTP_NAME']);
            $mail->addAddress($to, "CHUCK NORRIS JOKE");

            $mail->Subject = "TEST";
            $mail->Body = $message;

            
            $mail->send();

            // echo "email sent";
        }
    }