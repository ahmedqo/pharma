<?php


$to = "commerciale@promedplanet.com";
$subject = "From :" . $_POST["email"] . ", Contact: " .  $_POST["last_name"] . " " .  $_POST["first_name"] . ", phone: " .  $_POST["phone"];
$body =  $_POST["message"];
mail($to, $subject, $body);
header('Location: /contact.html');
