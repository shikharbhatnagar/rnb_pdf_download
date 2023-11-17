<?php 
get_header();
require_once('lib/class.pdf2text.php');
$univ_param = get_query_var( 'university_name' );
$optionname_param = get_query_var( 'university_option_name' );
if(empty($univ_param)){
	require_once('code_universitylist.php');
}
else if(!empty($univ_param)){
	require_once('code_universitysingle.php');
}
?>
<?php get_footer(); ?>