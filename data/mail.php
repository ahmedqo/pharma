<?php

$headers  = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
$headers .= "From: " . $_POST["email"] . "\r\n";
$headers .= "Reply-To: " . $_POST["email"] . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();
$headers .= "X-Priority: 1" . "\r\n";

$to = "commerciale@promedplanet.com";
$subject = "Contact: " .  $_POST["last_name"] . " " .  $_POST["first_name"] . ", phone: " .  $_POST["phone"];
$body =  $_POST["message"];

mail($to, $subject, $body, $headers);
header('Location: /contact.html');
