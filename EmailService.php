<?php
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    public function sendEmail($to, $subject, $message) {
        error_log("Email gönderiliyor - Alıcı: " . $to);
        
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'sensiz583423@gmail.com';
            $mail->Password   = 'miau hhjk qffx fock';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('sensiz583423@gmail.com', 'Todo App');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $result = $mail->send();
            error_log("Email gönderim sonucu: Başarılı");
            return true;

        } catch (Exception $e) {
            error_log("Email gönderim hatası: " . $mail->ErrorInfo);
            return false;
        }
    }
}
?> 