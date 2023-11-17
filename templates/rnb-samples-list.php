<?php 
get_header();
require_once('lib/class.pdf2text.php');
//$subjectcategory_param = get_query_var( 'subject_category' );
$subjectname_param = get_query_var( 'subject_name' );
$optionname_param = get_query_var( 'subject_option_name' );
if(empty($subjectname_param)){
	require_once('code_sampleslist.php');
}
else if(!empty($subjectname_param)){
	require_once('code_samplessingle.php');
}
?>
<?php get_footer(); ?>