<?php
$path = preg_replace('/wp-content.*$/','',__DIR__);
//require_once($path."wp-includes/pluggable.php" );
require_once($path."wp-load.php");
function sendmail_new(){
	$to = $_POST['emailid'];
	$otp = $_POST['otp'];
	$subject = "Good interview OTP";
	$message = 'OPT to download the pdf is <b>'.$otp.'</b>';  
	$header = "From:mygoodinterview <noreply@mygoodinterview.com> \r\n";
	// $header .= "Cc:afgh@somedomain.com \r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-type: text/html; charset=UTF-8\r\n";
	//$mailres = mail ($to,$subject,strip_tags($message),$header);
	$mailres = wp_mail( $to, $subject, strip_tags($message), $header);
	if( $mailres == true ) {
		echo "Mail sent successfully...".$mailres;
	}else {
		echo "Resend OTP.".$mailres;
	}
}
sendmail_new();
?>