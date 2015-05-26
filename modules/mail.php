<?php 
	require_once('../class.phpmailer.php');
	//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
	
	$mail             = new PHPMailer();
	
	$body             = file_get_contents('contents.html');
	$body             = preg_replace('/[\]/','',$body);
	
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "mail.yourdomain.com"; // SMTP server
	$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
											   // 1 = errors and messages
											   // 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host       = "smtp.gmail.com"; // sets the SMTP server
	$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
	$mail->Username   = "anmolmago"; // SMTP account username
	$mail->Password   = "Sonrajanie123";        // SMTP account password
	
	$mail->SetFrom('donate@artificialcraft.net', 'First Last');
	
	$mail->AddReplyTo("anmolmago@gmail.com","First Last");
	
	$mail->Subject    = "PHPMailer Test Subject via smtp, basic with authentication";
	
	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	
	$mail->MsgHTML($body);
	
	$address = "anmolmago@hotmail.com";
	$mail->AddAddress($address, "John Doe");
	
	$mail->AddAttachment("images/phpmailer.gif");      // attachment
	$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
	
	if(!$mail->Send()) {
	  echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	  echo "Message sent!";
	}
?>