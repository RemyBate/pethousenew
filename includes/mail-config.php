<?php
// Mail configuration for PupNest
// Set SMTP_ENABLED to true and fill in the SMTP_* values to send via SMTP using PHPMailer.
// Leave SMTP_ENABLED as false to fall back to PHP mail(). Note: mail() often does not work on local XAMPP.

define('SMTP_ENABLED', false);
define('SMTP_HOST', '');
define('SMTP_PORT', 587); // 465 for SMTPS, 587 for STARTTLS
define('SMTP_ENCRYPTION', 'tls'); // 'ssl' or 'tls'
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');

// Default From identity
define('MAIL_FROM_EMAIL', 'no-reply@pupnest.local');
define('MAIL_FROM_NAME', 'PupNest');


