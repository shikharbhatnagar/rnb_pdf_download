<?php
/*
Plugin Name: RNB PDF Download
Description: This is to achieve the functionality of download PDF after selectingoptions and fill up the form.
Version: 1.1
Author: Shikhar Bhatnagar
Author URI: http://rnbinfotech.com
*/
function rnb_options_install() {
    global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$sql1 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."rnb_universities (
	  `nuniversity_id` int(11) NOT NULL AUTO_INCREMENT,
	  `vuniversity_title` varchar(50) NOT NULL,
	  `nuniversity_order` int(11) NOT NULL,
	  PRIMARY KEY (`nuniversity_id`)
	) ENGINE = InnoDB;";
	$sql2 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."rnb_courses (
	  `ncourse_id` int(11) NOT NULL AUTO_INCREMENT,
	  `vcourse_title` varchar(50) NOT NULL,
	  `ncourse_order` int(11) NOT NULL,
	  PRIMARY KEY (`ncourse_id`)
	) ENGINE=InnoDB;" ;
	$sql3 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."rnb_options (
	  `noption_id` int(11) NOT NULL AUTO_INCREMENT,
	  `voption_title` varchar(50) NOT NULL,
	  `noption_order` int(11) NOT NULL,
	  `voption_type` varchar(50) NOT NULL,
	  PRIMARY KEY (`noption_id`)
	) ENGINE=InnoDB;" ;
    $sql4 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."rnb_pdf (
    	`npdf_id` INT NOT NULL AUTO_INCREMENT , 
    	`nuniversity_id` INT NOT NULL , 
    	`vpdf_data` JSON NOT NULL , 
    	PRIMARY KEY (`npdf_id`), 
    	UNIQUE (`nuniversity_id`)) ENGINE = InnoDB;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta($sql1);
	dbDelta($sql2);
	dbDelta($sql3);
	dbDelta($sql4);
}
register_activation_hook(__FILE__, 'rnb_options_install');
add_action('admin_menu','rnb_pdf_modifymenu');
function rnb_pdf_modifymenu() {
	add_menu_page('University',
	'RNB University',
	'manage_options',
	'rnb_university_list',
	'rnb_university_list'
	);
	add_submenu_page('rnb_university_list',
	'Add New University',
	'Add New',
	'manage_options',
	'rnb_university_create',
	'rnb_university_create');
	add_submenu_page(null,
	'Update University',
	'Update',
	'manage_options',
	'rnb_university_update',
	'rnb_university_update');
	add_submenu_page(null,
	'Upload PDF',
	'Upload',
	'manage_options',
	'rnb_university_upload',
	'rnb_university_upload');

	add_menu_page('Courses',
	'RNB Courses',
	'manage_options',
	'rnb_course_list',
	'rnb_course_list'
	);
	add_submenu_page('rnb_course_list',
	'Add New Course',
	'Add New',
	'manage_options',
	'rnb_course_create',
	'rnb_course_create');
	add_submenu_page(null,
	'Update Course',
	'Update',
	'manage_options',
	'rnb_course_update',
	'rnb_course_update');
	add_submenu_page(null,
	'Upload PDF',
	'Upload',
	'manage_options',
	'rnb_course_upload',
	'rnb_course_upload');

	add_menu_page('Options',
	'RNB Options',
	'manage_options',
	'rnb_option_list',
	'rnb_option_list'
	);
	add_submenu_page('rnb_option_list',
	'Add New Option',
	'Add New',
	'manage_options',
	'rnb_option_create',
	'rnb_option_create');
	add_submenu_page(null,
	'Update Option',
	'Update',
	'manage_options',
	'rnb_option_update',
	'rnb_option_update');
}
// function rnb_schools_show_events(){
// 	eventList();
// }
// add_shortcode('RNBEVENTLIST', 'rnb_schools_show_events');
// function rnb_schools_alumni_form(){
// 	rnb_alumni_create();
// }
// add_shortcode('RNBALUMNIFORM', 'rnb_schools_alumni_form');
// function my_admin_scripts() {
//     wp_enqueue_script( 'my-great-script', plugin_dir_url( __FILE__ ) . '/js/rnbscript.js', array( 'jquery' ), '1.0.0', true );
// }
// add_action( 'admin_enqueue_scripts', 'my_admin_scripts' );


function my_template_array(){
	$temp = [];
	$temps['rnb-pdf-download.php'] = 'My Special Template';
	return $temps;
}
function my_template_register($page_templates, $theme, $post){
	$templates = my_template_array();
	foreach($templates as $tk => $tv){
		$page_templates[$tk] = $tv;
	}
	return $page_templates;
}
add_filter('theme_page_templates','my_template_register', 10, 3);

function my_template_select($template){
	global $post, $wp_query,$wpdb;
	$page_temp_slug = get_page_template_slug( $post->ID );
	$templates = my_template_array();
	if(isset($templates[$page_temp_slug])){
		$template = plugin_dir_path(__FILE__).'templates/'.$page_temp_slug;
	}
	return $template;
}
add_filter('template_include','my_template_select',99);

add_action( 'admin_head', 'my_header_scripts' );
function my_header_scripts(){
  	?>
  	<script>
	window.addEventListener("change", (e) => {
		// console.log("You knocked? e:",e);
		if(e.type == 'change'){
			if(e.path[0].type == 'file'){
				let eid = e.target.name;
				var file_input = jQuery(document).find('input[type="file"]');
				var fileindex = -1;
				for(var j=0; j<file_input.length; j++){
					if(file_input[j].id == eid){
						fileindex = j;
					}              
				}
				let selected_file = file_input[fileindex].files[0];
				let tdupload = 'td-'+eid;
				let tduploadedid = 'td-upload-'+eid;
				let td_ele = document.getElementById(tduploadedid);
				let td = document.getElementById(tdupload);
				let tdindex = td.getAttribute('tdindex');
				if(selected_file != undefined){
					var data = new FormData();
					data.append('userFile', selected_file);
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
					  if(this.responseText != 'error'){
						let urlbox = document.getElementsByName("optionpdf[]");

						if(e.path[0].attributes[1].value == '.png'){
							urlbox = document.getElementsByName("optionpreview[]");
						}
						let temp_response = this.responseText;
						let temp_response_ary  = temp_response.split('wp-content/uploads/');
						urlbox[tdindex].value = temp_response_ary[1];
						td_ele.innerHTML = "<a href='"+temp_response_ary[2]+"'>Uploaded</a>";
					  }
					}
					};
					xhttp.open("POST", location.origin + "/wp-content/plugins/rnb-pdf-download/ajax/uploadpdf.php", true);
					xhttp.setRequestHeader("enctype","multipart/form-data");
					xhttp.send(data);
				}
			}
		}
		else{ 
			console.log("Not a change"); 
		}
	});
	</script>
  	<?php
}

wp_enqueue_style( 'showhidestyle', plugin_dir_url( __FILE__ ) . '/css/showhidestyle.css', '1.0.0', 'all' );
define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'university-list.php');
require_once(ROOTDIR . 'university-create.php');
require_once(ROOTDIR . 'university-update.php');
require_once(ROOTDIR . 'course-list.php');
require_once(ROOTDIR . 'course-create.php');
require_once(ROOTDIR . 'course-update.php');
require_once(ROOTDIR . 'options-create.php');
require_once(ROOTDIR . 'options-list.php');
require_once(ROOTDIR . 'options-update.php');
require_once(ROOTDIR . 'university-upload.php');
require_once(ROOTDIR . 'course-upload.php');
?>