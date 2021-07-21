<?php
// Файлы phpmailer
	require 'PHPMailer/PHPMailer.php';
	require 'PHPMailer/SMTP.php';
	require 'PHPMailer/Exception.php';

// Переменные, которые отправляет пользователь
	if (isset($_POST['name'])) {
		$name = $_POST['name'];
	}
	
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
	}
	
	if (isset($_POST['hand'])) {
		$hand = $_POST['hand'];
	}
	
	if (isset($_POST['message'])) {
		$message = $_POST['message'];
	}
	
	if (isset($_POST['age'])) {
		$age = $_POST['age'];
	}
	
	if (isset($_POST['image'])) {
		$image = $_POST['image'];
	}

//	$body = '';
//
//	if (isset($_FILES['image']['tmp_name'])) {
//		//путь загрузки файла
//		$filePath = __DIR__ . '/src/files/' . $_FILES['image']['name'];
//		//грузим файл
//
//		if (copy($_FILES['image']['tmp_name'], $filePath)) {
//			$fileAttach = $filePath;
//			$body .= 'Фото в приложении';
//		}
//	}

// Формирование самого письма
	$title = "ajax form title";
	if (isset($name) && !empty($name) && isset($number) && !empty($number) && isset($email) && !empty($email)) {
		$body = "
		<b>Имя:</b> $name<br>
		<b>Почта:</b> $email<br><br>
		<b>Правша или левша:</b><br>$hand
		<b>Сообщение:</b><br>$message
		<b>Возраст:</b><br>$age
		<b>Фото:</b><br>";
	}

// Настройки PHPMailer
	$mail = new PHPMailer\PHPMailer\PHPMailer();
	try {
		$mail->isSMTP();
		$mail->CharSet = "UTF-8";
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 2;
		$mail->Debugoutput = function ($str, $level) {
			$GLOBALS['status'][] = $str;
		};
		
		// Настройки вашей почты
		$mail->Host = 'smtp.gmail.ru'; // SMTP сервера вашей почты
		$mail->Username = ''; // Логин на почте
		$mail->Password = ''; // Пароль на почте
		$mail->SMTPSecure = 'ssl';
		$mail->Port = 465;
		$mail->setFrom('', 'root@localhost'); // Адрес самой почты и имя отправителя
//		$mail->addAttachment($fileAttach);
		
		// Получатель письма
		$mail->addAddress('gitgit ');

// Отправка сообщения
		$mail->isHTML(true);
		$mail->Subject = $title;
		$mail->Body = $body;
		
		
		if (isset($name) && !empty($name) && isset($email) && !empty($email)) {
// Проверяем отравленность сообщения
			if ($mail->send()) {
				$result = "success";
			} else {
				$result = "error";
			}
		}
	} catch (Exception $e) {
		$result = "error";
		$status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
	}

// Отображение результата
	header('Content-type: application/json');
	echo json_encode(["result" => $result, "status" => $status]);