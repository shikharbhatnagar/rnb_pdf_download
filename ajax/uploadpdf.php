<?php
$path = preg_replace('/wp-content.*$/','',__DIR__);
require_once($path."wp-load.php");
//require_once($path."\\wp-includes\\template-loader.php" );
require_once( ABSPATH . 'wp-includes/template-loader.php' );
//require_once($path."\\wp-admin\\includes\\file.php");
require_once( ABSPATH . 'wp-admin/includes/file.php' );
$pdffile = $_FILES['userFile'];
// echo "FILE VAR DUMP";
// var_dump($pdffile);
$upload_overrides = array( 'test_form' => false, 'alternet_text' );
$upload_result = wp_handle_upload($pdffile, $upload_overrides);
if(array_key_exists('url', $upload_result)){
	echo $upload_result['url'];
}
else{
	echo "error";
}
/*$path = preg_replace('/wp-content.*$/','',__DIR__);
require_once($path."wp-load.php");
require_once($path."\\wp-includes\\template-loader.php" );
require_once($path."\\wp-admin\\includes\\file.php");
$pdffile = $_FILES['userFile'];
// var_dump($pdffile);
$upload_overrides = array( 'test_form' => false, 'alternet_text' );
$upload_result = wp_handle_upload($pdffile, $upload_overrides);
if(array_key_exists('url', $upload_result)){
	echo $upload_result['url'];
}
else{
	echo "error";
}*/
?>