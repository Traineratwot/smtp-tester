<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;

	ini_set('display_errors', 1);
	error_reporting(E_ERROR);
	require_once 'vendor/autoload.php';
	ob_end_clean();
	$a = [
			'TO_EMAIL'   => $_POST['TO_EMAIL'] ?? 'Traineratwot@yandex.ru',
			'FROM_EMAIL' => $_POST['FROM_EMAIL'] ?? 'no-reply@technolight.ru',
			'FROM_NAME'  => $_POST['FROM_NAME'],
			'SMTP'       => !isset($_POST['SMTP']) || (bool)$_POST['SMTP'],
			'HOST'       => $_POST['HOST'] ?? 'mail.technolight.ru',
			'AUTH'       => $_POST['AUTH'],
			'USERNAME'   => $_POST['USERNAME'],
			'PASSWORD'   => $_POST['PASSWORD'],
			'SECURE'     => $_POST['SECURE'],
			'PORT'       => $_POST['PORT'] ?? 587,
			'BODY'       => $_POST['BODY'] ?? 'Test',
	];
	echo '<pre><code>';
	print_r($a);
	echo '</code></pre>';
	define('TO_EMAIL', $a['TO_EMAIL']);
	define('FROM_EMAIL', $a['FROM_EMAIL']);
	define('FROM_NAME', $a['FROM_NAME']);
	define('SMTP', $a['SMTP']);
	define('HOST', $a['HOST']);
	define('AUTH', $a['AUTH']);
	define('USERNAME', $a['USERNAME']);
	define('PASSWORD', $a['PASSWORD']);
	define('SECURE', $a['SECURE']);
	define('PORT', $a['PORT']);
	define('BODY', $a['BODY']);

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

?>
<body class="container-fluid">
<!-- CSS only -->
<link
		href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi"
		crossorigin="anonymous"
>
<!-- JavaScript Bundle with Popper -->
<script
		src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"
></script>
<style>
	p, label, input, select, textarea, button {
		width : 100%
		}
</style>
<div style="border: 1px solid black">
	<?php
		if (FROM_EMAIL && isset($_POST['submit'])) {

			echo '<h1>ЛОГ:</h1><pre><code>';

			var_dump(mail2(TO_EMAIL, BODY, BODY));
			echo '</code></pre>';

		}
	?>
</div>
<form method="post" action="<?=$_SERVER['REQUEST_URI']?>" style="width: 50vw">
	<p>
		<label class="form-label">
			TO_EMAIL
			<input required class="form-control" value="<?= TO_EMAIL ?>" name="TO_EMAIL" placeholder="TO_EMAIL">
		</label>
	</p>
	<p>
		<label>
			FROM_EMAIL
			<input required class="form-control" value="<?= FROM_EMAIL ?>" name="FROM_EMAIL" placeholder="FROM_EMAIL">
		</label>
	</p>
	<p>
		<label>
			FROM_NAME
			<input class="form-control" value="<?= FROM_NAME ?>" name="FROM_NAME" placeholder="FROM_NAME">
		</label>
	</p>
	<p>
		<label>
			SMTP
			<input class="form-check-input" type="checkbox" <?= SMTP ? 'checked' : '' ?> name="SMTP" placeholder="SMTP">
		</label>
	</p>
	<p>
		<label>
			HOST
			<input required class="form-control" value="<?= HOST ?>" name="HOST" placeholder="HOST">
		</label>
	</p>
	<p>
		<label>
			AUTH
			<input class="form-control" value="<?= AUTH ?>" name="AUTH" placeholder="AUTH">
		</label>
	</p>
	<p>
		<label>
			USERNAME
			<input class="form-control" value="<?= USERNAME ?>" name="USERNAME" placeholder="USERNAME">
		</label>
	</p>
	<p>
		<label>
			PASSWORD
			<input class="form-control" value="<?= PASSWORD ?>" name="PASSWORD" placeholder="PASSWORD">
		</label>
	</p>
	<p>
		<label>
			SECURE
			<select class="form-control" name="SECURE" placeholder="SECURE">
				<option <?= !SECURE ? 'selected' : '' ?> value="">нет</option>
				<option <?= SECURE == PHPMailer::ENCRYPTION_STARTTLS ? 'selected' : '' ?> value="<?= PHPMailer::ENCRYPTION_STARTTLS ?>">tls (STARTTLS)</option>
				<option <?= SECURE == PHPMailer::ENCRYPTION_SMTPS ? 'selected' : '' ?> value="<?= PHPMailer::ENCRYPTION_SMTPS ?>">ssl (SMTPS)</option>
			</select>
		</label>
	</p>
	<p>
		<label>
			PORT
			<input class="form-control" value="<?= PORT ?>" name="PORT" placeholder="PORT">
		</label>
	</p>
	<p>
		<label>
			BODY
			<textarea class="form-control" name="BODY" placeholder="BODY"><?= BODY ?></textarea>
		</label>
	</p>
	<label>
		<button class="btn btn-info" type="submit" value="send" name="submit" placeholder="send"> send</button>
	</label>
</form>
</body>

