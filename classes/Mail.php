<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'libs/PHPMailer/src/Exception.php';
require_once 'libs/PHPMailer/src/PHPMailer.php';
require_once 'libs/PHPMailer/src/SMTP.php';

class Mail {
    public static function sendMail($subject, $body, $address) {
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.126.com';                         // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'jackob_xm@126.com';                // SMTP username
            $mail->Password = 'jackob314151';                     // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('jackob_xm@126.com', '有朋');
            $mail->addAddress($address);                          // Name is optional

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
            $mail->Body    = $body;

            $mail->send();

        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}

?>