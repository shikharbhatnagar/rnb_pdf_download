<?php
$path = preg_replace('/wp-content.*$/','',__DIR__);
//require_once($path."wp-includes/pluggable.php" );
require_once($path."wp-load.php");
function sendmail_new(){
	$to = $_POST['emailid'];
	$pdfurl = $_POST['url'];
	$name = $_POST['vname'];
	$phone = $_POST['vphone'];
	$pdfFiletext = $_POST['pdfFiletext'];

	$pdfFiletext = ucwords(str_replace("-", " ", $pdfFiletext));
	$subject = 'PHD Guides : Download '.$pdfFiletext;
    $message = '<html><body><p>Hello '.$name.',</p><br><p>We have received your request for download : ( '.$pdfFiletext.' ). You can download the full document <a href="'.$pdfurl.'">here</a>.</p><br><p>You can also contact our consulting team if you have any other queries regarding this document or your research proposal.<br><p>Please read our <a href="https://phdguides.org/terms-and-conditions/" target="_blank">Terms & Conditions</a></p></p>
<br><p><strong>Thanks for visiting us<br>Team PHDGUIDES</strong></p></body></html>';
	//$message = '<html><body><p>Click on download to see the PDF</p><a href="'.$pdfurl.'">Download</a></body></html>';
	$header = "From:mygoodinterview <noreply@mygoodinterview.com> \r\n";
	// $header .= "Cc:afgh@somedomain.com \r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-type: text/html; charset=UTF-8\r\n";
	//$mailres = mail ($to,$subject,strip_tags($message),$header);
	$mailres = wp_mail( $to, $subject, $message, $header);
	//strip_tags($message)
	if( $mailres == true ) {
		echo "Mail sent successfully...".$mailres." ".$message."<br>";
    	$subject = 'New Download at PHD Guides: '.$pdfFiletext;
        $message = '<html><body><table style="width:500px;"><tr><td><strong>Name:</strong></td><td>'.$name.'</td></tr><tr><td><strong>Phone:</strong></td><td>'.$phone.'</td></tr><tr><td><strong>Email:</strong></td><td>'.$to.'</td></tr><tr><td><strong>Downloaded:</strong></td><td>'.$pdfFiletext.'</td></tr><tr><td><strong>Download link:</strong></td><td>'.$pdfurl.'</td></tr></table></body></html>';
    	//$message = '<html><body><p>Click on download to see the PDF</p><a href="'.$pdfurl.'">Download</a></body></html>';
    	$header = "From:mygoodinterview <noreply@mygoodinterview.com> \r\n";
    	$header .= "MIME-Version: 1.0\r\n";
    	$header .= "Content-type: text/html; charset=UTF-8\r\n";
    	$to = 'myphdguides@gmail.com';
    	$mailres = wp_mail( $to, $subject, $message, $header);
		saveVisitor($name,$phone,$to,$pdfurl);
	}else {
		echo "Unable to send email:".$mailres;
	}
}
sendmail_new();

function saveVisitor($na,$ph,$em,$ur){
    global $wpdb;
    $table_name = $wpdb->prefix . "rnb_visitor_data";
	$wpdb->insert(
				$table_name,
				array('vname' => $na, 'vphone' => $ph, 'vemail' => $em, 'vurl' => $ur ),
				array('%s', '%s', '%s', '%s')
		);
}
?>