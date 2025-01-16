<?php
 error_reporting(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../vendor/autoload.php';

include_once 'config/core.php';
include_once 'config/conf.php';
include_once 'config/database.php';



function send_veify_code($email, $name, $token)
{
    $conf = new Conf();

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Mailer = "smtp";
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail = SetupMail($mail, $conf);

    $mail->IsHTML(true);

    $mail->SetFrom("feliix.it@gmail.com", "Feliix.System");
    $mail->AddReplyTo("feliix.it@gmail.com", "Feliix.System");

    $mail->AddAddress($email, $name);

    $mail->Subject = "[EmpoSelf Verification]";
    $content =  "<p>Dear " . $name . ",</p>";
    $content = $content . "<p>Your verify code is: " . $token . "</p>";
    $content = $content . "Please use this code to verify your email address.";


    $mail->MsgHTML($content);
    if($mail->Send()) {

        return true;
//        echo "Error while sending Email.";
//        var_dump($mail);
    } else {

        return false;
//        echo "Email sent successfully";
    }
}


function SetupMail($mail, $conf)
{
    // $mail->SMTPDebug  = 0;
    // $mail->SMTPAuth   = true;
    // $mail->SMTPSecure = "ssl";
    // $mail->Port       = 465;
    // $mail->SMTPKeepAlive = true;
    // $mail->Host       = $conf::$mail_host;
    // $mail->Username   = $conf::$mail_username;
    // $mail->Password   = $conf::$mail_password;


    $mail->SMTPDebug  = 0;
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;
    $mail->SMTPKeepAlive = true;
    $mail->Host       = 'smtp.ethereal.email';
    $mail->Username   = 'jermey.wilkinson@ethereal.email';
    $mail->Password   = 'zXX3N6QwJ5AYZUjbKe';

    // $mail->SMTPDebug  = 0;
    // $mail->SMTPAuth   = true;
    // $mail->SMTPSecure = "tls";
    // $mail->Port       = 587;
    // $mail->SMTPKeepAlive = true;
    // $mail->Host       = 'smtp.ethereal.email';
    // $mail->Username   = 'calista.lubowitz@ethereal.email';
    // $mail->Password   = 'VzkRWsx6FszvrQ1ZTW';

    return $mail;

}

?>