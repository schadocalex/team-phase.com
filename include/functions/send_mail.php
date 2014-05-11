<?php

	function send_mail($receiver, $title, $message)
	{
		if(!empty($_SESSION['last_send_mail']))
			$last_send_mail = $_SESSION['last_send_mail'];
		else if(!empty($_COOKIE['last_send_mail']))
			$last_send_mail = $_SESSION['last_send_mail'];
		// else
			$last_send_mail = 0;
		$can_send_mail = (time() - $last_send_mail > 5*60) ? true : false;
	
		if($can_send_mail)
		{
			$headers = "From: \"Team-Phase\"<contact@team-phase.com>\n";
			$headers .= "Reply-to: \"Team-Phase\"<contact@team-phase.com>\n";
			$headers .='Content-Type: text/html; charset="utf-8"'."\n";
			$headers .='Content-Transfer-Encoding: 8bit';
		
			$message = '
			<html>
				<head>
				</head>
				<body>
					' . nl2br($message) . '		
				</body>
			</html>';
			
			$_SESSION['last_send_mail'] = time();
			setcookie("last_send_mail", time(), time() + 3600);
			$_SESSION['success'] = "Thank you, your e-mail have been sent.";
			$can_send_mail = false;
				
			mail($receiver, $title, $message, $headers);
		}
		else
			$_SESSION['error'] = "You already sent an e-mail recently.";
		
	}
	
	
?>