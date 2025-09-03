<?php
// mailer.php — PHPMailer wrapper
declare(strict_types=1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/vendor/autoload.php'; // opcional se o usuário instalar phpmailer via composer
if (!class_exists(PHPMailer::class)) {
  // fallback mínimo com sendmail() se PHPMailer não estiver disponível
  function send_mail(string $to, string $subject, string $html): bool {
    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: ".(getenv('MAIL_FROM') ?: 'MicAuth <no-reply@example.com>')."\r\n";
    return mail($to, $subject, $html, $headers);
  }
  return;
}

function send_mail(string $to, string $subject, string $html): bool {
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
    $mail->Port = (int)(getenv('SMTP_PORT') ?: 587);
    $mail->SMTPSecure = getenv('SMTP_SECURE') ?: PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPAuth = true;
    $mail->Username = getenv('SMTP_USER') ?: '';
    $mail->Password = getenv('SMTP_PASS') ?: '';
    $mail->CharSet = 'UTF-8';

    $from = getenv('MAIL_FROM') ?: 'MicAuth <no-reply@example.com>';
    if (str_contains($from, '<')) {
      [$name,$addr] = explode('<',$from,2);
      $addr = rtrim($addr,'>');
      $mail->setFrom(trim($addr), trim($name));
    } else {
      $mail->setFrom($from, 'MicAuth');
    }
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $html;
    $mail->AltBody = strip_tags($html);
    $mail->send();
    return true;
  } catch (\Throwable $e) {
    error_log('Mailer error: '.$e->getMessage());
    return false;
  }
}
