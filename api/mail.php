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



function send_reset_veify_code($email, $name, $token)
{
    $conf = new Conf();

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Mailer = "smtp";
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail = SetupMail($mail, $conf);

    $mail->IsHTML(true);

    $mail->SetFrom("feliix.it@gmail.com", "把力量還給你");
    $mail->AddReplyTo("feliix.it@gmail.com", "把力量還給你");

    $mail->AddAddress($email, $name);

    $mail->Subject = "[把力量還給你] 載入資料的驗證碼";
    $content =  "<p>您好 " . $name . ",</p>";
    $content = $content . "<p>「重設密碼」的驗證碼:  " . $token . "</p>";
    $content = $content . "請使用這個驗證碼進行「載入資料」的驗證，謝謝";


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

    $mail->SetFrom("feliix.it@gmail.com", "把力量還給你");
    $mail->AddReplyTo("feliix.it@gmail.com", "把力量還給你");

    $mail->AddAddress($email, $name);

    $mail->Subject = "[把力量還給你] 載入資料的驗證碼";
    $content =  "<p>您好 " . $name . ",</p>";
    $content = $content . "<p>「載入資料」的驗證碼:  " . $token . "</p>";
    $content = $content . "請使用這個驗證碼進行「載入資料」的驗證，謝謝";


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