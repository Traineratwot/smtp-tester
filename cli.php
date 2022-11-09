<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	require_once 'vendor/autoload.php';

	define('TO_EMAIL', 'Traineratwot@yandex.ru');

	define('FROM_EMAIL', 'no-reply@technolight.ru');
	define('FROM_NAME', 'no-reply@technolight.ru');
	define('SMTP', 1);
	define('HOST', 'mail.technolight.ru');
	define('AUTH', 1);
	define('USERNAME', '');
	define('PASSWORD', '');
	define('SECURE', '');
	define('PORT', 587);

	function mail2($to, $subject, $body, $file = [], $options = [])
	{
		$mail = new PHPMailer(TRUE);
		$mail->isHTML(TRUE);
		$mail->setLanguage('ru');
		$mail->CharSet = PHPMailer::CHARSET_UTF8;
		if (!empty($options['from'])) {
			$a     = explode('::', $options['from']);
			$email = $a[0];
			$name  = $a[1];
			$mail->setFrom($email, $name);
		} else {
			$mail->setFrom(FROM_EMAIL, FROM_NAME);
		}
		if (SMTP) {
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host = HOST;                                         //Set the SMTP server to send through
			if (AUTH) {
				$mail->SMTPAuth = TRUE;                                           //Enable SMTP authentication
				$mail->Username = USERNAME;                                       //SMTP username
				$mail->Password = PASSWORD;                                       //SMTP password
			}
			if (SECURE) {
				$mail->SMTPSecure = SECURE;                                  //Enable implicit TLS encryption
			}
			$mail->Port = PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
		}
		if (!is_array($to)) {
			$to = [$to];
		}
		foreach ($to as $too) {

			$a     = explode('::', $too);
			$email = $a[0];
			$name  = $a[1];
			$mail->addAddress($email, $name);
		}
		if (!is_array($file)) {
			$file = [$file];
		}
		foreach ($file as $f) {
			$mail->addAttachment($f);
		}
		$mail->Subject   = $subject;
		$mail->Body      = $body;
		$mail->AltBody   = strip_tags($body);
		$mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;
		$mail->send();
		return TRUE;
	}

	var_dump(mail2(TO_EMAIL, 'Test', 'Test'));