<?php
if (!isset($_POST['fakemail'])) {
	echo "<center>\n";
	echo "<form name='spam' method='post' action='http://www.simplyian.com/arsenal/fakemail/index.php'>\n";
	echo "<input type='text' value='Send to which email?' name='to' /><br />\n";
	echo "<input type='text' value='Send from which email?' name='from' /><br />\n";
	echo "<input type='text' value='Send with what subject?' name='subject' /><br />\n";
	echo "<textarea name='message'>";
	echo "With what message?";
	echo "</textarea><br />\n";
	echo "<input type='submit' name='fakemail' value='FakeMail Them!' /><br />\n";
	echo "</form>\n";
	echo "</center>\n";
} else {
	$to = $_POST['to'];
	$from = $_POST['from'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];
		sendemail($to,$to,$from,$from,$subject,$message);
	echo "FakeMail successful.";
}
function sendemail($toname,$toemail,$fromname,$fromemail,$subject,$message,$type="plain",$cc="",$bcc="") {

	global $settings, $locale;
	
	require_once "phpmailer_include.php";
	
	$mail = new PHPMailer();
	$mail->SetLanguage("en", "");

	if ($settings['smtp_host']=="") {
		$mail->IsMAIL();
	} else {
		$mail->IsSMTP();
		$mail->Host = $settings['smtp_host'];
		$mail->SMTPAuth = true;
		$mail->Username = $settings['smtp_username'];
		$mail->Password = $settings['smtp_password'];
	}
	
	$mail->CharSet = $locale['charset'];
	$mail->From = $fromemail;
	$mail->FromName = $fromname;
	$mail->AddAddress($toemail, $toname);
	$mail->AddReplyTo($fromemail, $fromname);
	if ($cc) { 
		$cc = explode(", ", $cc);
		foreach ($cc as $ccaddress) {
			$mail->AddCC($ccaddress);
		}
	}
	if ($bcc) {
		$bcc = explode(", ", $bcc);
		foreach ($bcc as $bccaddress) {
			$mail->AddBCC($bccaddress);
		}
	}
	if ($type == "plain") {
		$mail->IsHTML(false);
	} else {
		$mail->IsHTML(true);
	}
	
	$mail->Subject = $subject;
	$mail->Body = $message;
	
	if(!$mail->Send()) {
		$mail->ErrorInfo;
		$mail->ClearAllRecipients();
		$mail->ClearReplyTos();
		return false;
	} else {
		$mail->ClearAllRecipients(); 
		$mail->ClearReplyTos();
		return true;
	}

}
?>