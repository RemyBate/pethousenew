<?php
// Simple mail helper that uses PHPMailer if SMTP is enabled, otherwise falls back to mail().
require_once __DIR__ . '/mail-config.php';

function pupnest_send_mail(string $toEmail, string $toName, string $subject, string $bodyText, string $replyEmail = '', string $replyName = ''): bool {
  if (defined('SMTP_ENABLED') && SMTP_ENABLED) {
    // Try PHPMailer if available
    $phpmailerPath = __DIR__ . '/phpmailer/PHPMailer.php';
    $smtpPath = __DIR__ . '/phpmailer/SMTP.php';
    $exceptionPath = __DIR__ . '/phpmailer/Exception.php';
    if (file_exists($phpmailerPath) && file_exists($smtpPath) && file_exists($exceptionPath)) {
      require_once $exceptionPath;
      require_once $phpmailerPath;
      require_once $smtpPath;
      try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->Port = SMTP_PORT;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = SMTP_ENCRYPTION;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;

        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($toEmail, $toName);
        if ($replyEmail !== '') {
          $mail->addReplyTo($replyEmail, ($replyName !== '' ? $replyName : $replyEmail));
        }
        $mail->Subject = $subject;
        $mail->Body = $bodyText;
        $mail->AltBody = $bodyText;
        $mail->isHTML(false);
        $mail->send();
        return true;
      } catch (Throwable $e) {
        // fall through to mail()
      }
    }
  }

  // Fallback to mail()
  $headers = [];
  $headers[] = 'MIME-Version: 1.0';
  $headers[] = 'Content-Type: text/plain; charset=UTF-8';
  $headers[] = 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM_EMAIL . '>';
  if ($replyEmail !== '') {
    $headers[] = 'Reply-To: ' . ($replyName !== '' ? "$replyName <$replyEmail>" : $replyEmail);
  }
  return @mail($toEmail, $subject, $bodyText, implode("\r\n", $headers));
}


