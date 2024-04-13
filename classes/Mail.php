<?php

require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';
require 'vendor/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
  public static function send($fullname, $subject, $emailTo, $template)
  {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = MAIL_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = MAIL_USERNAME;
    $mail->Password = MAIL_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = MAIL_PORT;
    $mail->isHTML(true);

    $mail->setFrom(MAIL_SENDER, WEBSITE_TITLE);
    $mail->addAddress($emailTo, $fullname);
    $mail->addReplyTo(MAIL_SENDER, WEBSITE_TITLE);
    $mail->Subject = $subject;

    $mail->Body = $template;

    if ($mail->send()) {
      return true;
    } else {
      throw new Exception('Имаше проблем при изпращането на имейла: ' . $mail->ErrorInfo);
    }
  }

  public static function createTemplate($data, $templateName)
  {
    $template = file_get_contents("email-templates/$templateName.html");
    
    foreach($data as $key => $value) {
      $keys[] = "{{{$key}}}";
      $values[] = "$value";
    }
    
    $template = str_replace($keys, $values, $template);
  
    return $template;
  }
}
