<?php
// Receiving inbox for contact form submissions.
$receiving_email_address = 'attiqvirk419@gmail.com';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo 'Only POST requests are allowed.';
  exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $subject === '' || $message === '') {
  http_response_code(400);
  echo 'All fields are required.';
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo 'Please enter a valid email address.';
  exit;
}

$safe_name = str_replace(["\r", "\n"], '', $name);
$safe_email = str_replace(["\r", "\n"], '', $email);
$safe_subject = str_replace(["\r", "\n"], '', $subject);

$email_subject = 'New Contact Form Message: ' . $safe_subject;
$email_body =
  "You received a new contact form submission.\n\n" .
  "Name: {$safe_name}\n" .
  "Email: {$safe_email}\n" .
  "Subject: {$safe_subject}\n\n" .
  "Message:\n{$message}\n";

$headers = [
  'MIME-Version: 1.0',
  'Content-Type: text/plain; charset=UTF-8',
  "From: {$safe_name} <{$safe_email}>",
  "Reply-To: {$safe_email}"
];

$sent = mail($receiving_email_address, $email_subject, $email_body, implode("\r\n", $headers));

if ($sent) {
  echo 'OK';
} else {
  http_response_code(500);
  echo 'Message could not be sent. Please check server mail configuration.';
}
?>
