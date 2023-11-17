<?php 
// if ( get_query_var( 'university_name' ) ) {
//     $slug = get_query_var( 'university_name' );
// }
// echo "SHIKHAR BHATNAGAR<br>";
// echo $univ_param; // = get_query_var( 'university_name' );
// echo $optionname_param; // = get_query_var( 'university_option_name' );

//get_header();
require_once('lib/class.pdf2text.php');
//$tab_type = $_GET['tab_type'];
//$tab_type_id = $_GET['id'];
//$pg_param = $_GET['pg'];
//$optid_param = $_GET['optid'];
?>
<style>
/* Style the form */
#regForm {
  background-color: #ffffff;
  margin: 100px auto;
  padding: 40px;
  width: 70%;
  min-width: 300px;
}

/* Style the input fields */
input {
  padding: 10px;
  width: 100%;
  font-size: 17px;
  font-family: Raleway;
  border: 1px solid #aaaaaa;
}

/* Mark input boxes that gets an error on validation: */
input.invalid {
  background-color: #ffdddd;
}

/* Hide all steps by default: */
.tab {
  display: none;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

/* Mark the active step: */
.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #04AA6D;
}
li.list-group-item {
    cursor: pointer;
}
.position-relative.row.form-group {
    text-transform: capitalize;
}
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<!---- tabbed pane style start--->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!---- tabbed pane style end--->  
<?php
global $wpdb;
$table_name1 = $wpdb->prefix . "rnb_universities";
$table_name2 = $wpdb->prefix . "rnb_options";
$table_name3 = $wpdb->prefix . "rnb_pdf";

$university_slug_rows  = $wpdb->get_results("SELECT t1.nuniversity_id, t1.vuniversity_title, t1.vslug
					FROM $table_name1 t1
                    WHERE vslug='".$univ_param."'", ARRAY_A);

$opt_row = $wpdb->get_results("SELECT noption_id, voption_title, noption_order, voption_type from $table_name2 WHERE vslug='".$optionname_param."' order by noption_order", ARRAY_A);

$optid_param = ($opt_row[0]['noption_id']>0?$opt_row[0]['noption_id']:'na');

$tab_type_id 	= $university_slug_rows[0]['nuniversity_id'];

$universityrows = $wpdb->get_results("SELECT t1.nuniversity_id, t1.vuniversity_title, t1.vslug, t2.npdf_id, t2.vpdf_data, t2.vimage_data, t2.vpdf_type
					FROM $table_name1 t1
					JOIN $table_name3 t2 on t2.nuniversity_id = t1.nuniversity_id
					WHERE t2.vpdf_type='university'
					ORDER BY t1.vuniversity_title ASC", ARRAY_A);
foreach($universityrows as $ukey => $uvalue){ 
	$universityrows[$ukey]['vpdf_data'] = json_decode($universityrows[$ukey]['vpdf_data'], 1);
	$universityrows[$ukey]['vimage_data'] = json_decode($universityrows[$ukey]['vimage_data'], 1);
}	

//echo "University:<pre>"; print_r($universityrows); echo "</pre>";
//echo "Course:<pre>"; print_r($courserows); echo "</pre>";
$optionrows = $wpdb->get_results("SELECT noption_id, voption_title, noption_order, voption_type, vslug from $table_name2 order by noption_order");

$site  = $wpdb->get_results("SELECT option_name, option_value
                    FROM wp_options
                    WHERE option_name='siteurl'", ARRAY_A);
$dbsiteurl = $site[0]['option_value'];

$heading = '';

$heading = '<a href="'.$dbsiteurl.'/university-formats-guidelines">University Formats & Guidelines</a> - '.$university_slug_rows[0]['vuniversity_title'];

$university_opt_pic = '';
$sample_opt_pic = '';
$university_opt_pdf = '';
$sample_opt_pdf = '';
$selected_opt_pdf = '';

if($optid_param != 'na'){
    $pdfdata_ary  = $wpdb->get_results("SELECT vimage_data,vpdf_data
					FROM $table_name3 WHERE vpdf_type='university'
					AND nuniversity_id=".$tab_type_id, ARRAY_A);
	$pdfdata_ary1 = json_decode($pdfdata_ary[0]['vimage_data'], true); 
	$university_opt_pic = $pdfdata_ary1[$optid_param];
	$pdfdata_ary2 = json_decode($pdfdata_ary[0]['vpdf_data'], true);
	$university_opt_pdf = $pdfdata_ary2[$optid_param];
	
	$pdfdata_ary  = $wpdb->get_results("SELECT vimage_data,vpdf_data
					FROM $table_name3 WHERE vpdf_type='samples'
					AND nuniversity_id=".$tab_type_id, ARRAY_A);
	$pdfdata_ary3 = json_decode($pdfdata_ary[0]['vimage_data'], true); 
	$sample_opt_pic = $pdfdata_ary3[$optid_param];
	$pdfdata_ary4 = json_decode($pdfdata_ary[0]['vpdf_data'], true);
	$sample_opt_pdf = $pdfdata_ary4[$optid_param];
}
$upload_dir = wp_upload_dir();
?>
<div class="container">
	<div class="row align-items-start" id="notify"></div>
    <!--<div class="row align-items-start">
	    <div class="col-12 text-start">
            <img src="<?php //echo $dbsiteurl.'/wp-content/uploads/2022/12/banner02.jpg'; ?>" class="img-fluid">
        </div>
    </div>-->
    <div class="row align-items-start">
        <div class="col-12 text-start"><h2 style="text-transform: capitalize;"><p id="h2text"><?= $heading; ?></p></h2></div>
    </div>    
    <div class="row align-items-start">
	  <!--<div class="col-4 text-start">
        
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#university" name="university"><strong>Formats & Guidelines</strong></a></li>
            <li><a data-toggle="tab" href="#courses" name="course"><strong>Samples</strong></a></li>
        </ul>

        <div class="tab-content">
            <div id="university" class="tab-pane fade in active">
                <div class="rnb-university-scroll-bar" style="height: 600px; overflow: scroll;">
        	      	<ul class="list-group">
        	      	    <li class="list-group-item text-center" aria-current="true"><strong>UNIVERSITIES</strong></li>
        	      		<?php 
        	      		foreach($universityrows as $ukey => $uvalue){ ?>
        					<li class="list-group-item university" aria-current="true" university-id="<?php echo $uvalue['nuniversity_id']; ?>"><?php echo $uvalue['vuniversity_title']; ?></li>
        				<?php } ?>
        			</ul>
        		</div>
            </div>
            <div id="courses" class="tab-pane fade">
                <div class="rnb-course-scroll-bar" style="height: 600px; overflow: scroll;">
        	      	<ul class="list-group">
        	      	    <li class="list-group-item text-center" aria-current="true"><strong>SUBJECTS</strong></li>
        				<?php 
        	      		foreach($courserows as $ckey => $cvalue){ ?>
        					<li class="list-group-item university" aria-current="true" course-id="<?php echo $cvalue['ncourse_id']; ?>"><?php echo $cvalue['vcourse_title']; ?></li>
        				<?php } ?>
        			</ul>
        		</div>	
            </div>
        </div>
    </div>-->
    <div class="col-12">
    	<div class="rnb-main-content" id="main-content">
      		
      	</div>
    </div>

  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<script>
var userAgent = navigator.userAgent;
var ele_type = '';
var selected_optionid = '';
let hostname = location.hostname;
var currentTab = 0; // Current tab is set to be the first tab (0)
var university_id = 0;
var course_id = 0;
var pdfFile = '';
var otp = 'A-2132';
var pdfFiletext='';
// window.addEventListener("click", (e) => {

var university_data = 	'<?php echo json_encode($universityrows); ?>';
var options_data 	=	'<?php echo json_encode($optionrows); ?>';
//console.log("options_data:", options_data);
var course_data = '<?php echo json_encode($courserows); ?>';

var university_data_array = JSON.parse(university_data);
var options_data_array = JSON.parse(options_data);
var course_data_array = JSON.parse(course_data);

var upload_base_url = '<?php echo $upload_dir['baseurl']; ?>';

var univoptpic = '<?php echo $university_opt_pic; ?>';
var sampoptpic = '<?php echo $sample_opt_pic; ?>';
var preview_imgurl_onload = '';
var univoptpdf = '<?php echo $university_opt_pdf; ?>';

var sampoptpdf = '<?php echo $sample_opt_pdf; ?>';
var preview_pdfurl_onload = '';

var current_url = new URL(window.location.href);

var search_params = current_url.searchParams;
        
window.addEventListener("load", (e) => {
    console.log("You knocked? e:",e);
	
//if(e.path[0].attributes[2].name == 'university-id'){
//      tab_type = e.path[0].attributes[2].name;
    
    var vnam = '';
    var vphn = '';
    var veml = '';
    var vnameSet = (localStorage.getItem('vname_phd') !== null);
    var vphoneSet = (localStorage.getItem('vphone_phd') !== null);
    var vemailSet = (localStorage.getItem('vemail_phd') !== null);
    
    if (vnameSet)  {
        vnam = localStorage.getItem('vname_phd');
    }
    if (vphoneSet)  {
        vphn = localStorage.getItem('vphone_phd');
    }
    if (vemailSet)  {
        veml = localStorage.getItem('vemail_phd');
    }
    
        var notihtml = '<div class="col-12 text-start"><div class="alert alert-warning text-center" role="alert"><strong><a href="http://phdguides.org/research-writing-samples/" target="_blank" class="btn btn-primary" style="font-weight:bold;">Also see our Research Writing Sample</a></strong></div></div>';
        document.getElementById("notify").innerHTML = notihtml;
		
    	otp = generateOTP();

    	currentTab = 0;

  		let options_section = '';

        university_id = '<?php echo $tab_type_id; ?>';
        
        options_section += '<div class="tab" id="tabpanes">';
		options_section += '<div class="alert alert-warning" role="alert">  Document Type</div>';

		options_data_array.map((optv,opti) => {

			if(optv.voption_type == 'university'){

            		let pdfdataa = university_data_array.map((univ,unii) => {
                		if((univ.nuniversity_id == university_id)  && (univ.vpdf_type == 'university')){
                    		return univ.vpdf_data;
                		}
            		});

                	pdfdataa.forEach((pdfv,pdfi) => {
                		if(pdfv != undefined){
                    		for (const optid in pdfv) {
                        		if(optid == optv.noption_id){

                                	options_section += '<div id="tab1">';
									options_section += '<div class="position-relative row form-group">';
									options_section += '<label for="txtname" class="col-sm-3 col-form-label">'+optv.voption_title+'</label>';
									options_section += '<div class="col-sm-9">';
									//id="'+radid+'"
									if('<?php echo $optid_param; ?>' == optv.noption_id){ 
                                        options_section += '<input type="radio" for="regForm" value="'+optv.vslug+'" data-id="'+optv.noption_id+'" name="seloptions" seluniversity="'+university_id+'" checked="true">';
                        		    }
                        		    else{
                        		        options_section += '<input type="radio" for="regForm" value="'+optv.vslug+'" data-id="'+optv.noption_id+'" name="seloptions" seluniversity="'+university_id+'">';
                        		    }
								// 	options_section += '<input type="radio" for="regForm" value="'+optv.noption_id+'" name="seloptions" seluniversity="'+university_id+'">';
									options_section += '</div>';
									options_section += '</div>';
									options_section += '</div>';
                                    
                                    
                        		}
                    		}
                		}		
            		});
        		

			}

		});

		options_section += '</div>';
        
        preview_imgurl_onload = upload_base_url + "/" + univoptpic; //sampoptpic
        preview_pdfurl_onload = upload_base_url + "/" + univoptpdf; //sampoptpdf

	  	options_section	+=	'<div class="tab" id="pdftab">';
    	options_section += '<div class="alert alert-warning" role="alert">Sample Content</div>';
		options_section += '<img src="'+preview_imgurl_onload+'" class="rounded mx-auto d-block" alt="some text">';
	  	options_section 	+=	'</div>';
        
	  	options_section 	+=	'<div class="tab"><div class="alert alert-warning" role="alert">General Information</div>';
	  	options_section 	+=	'<p><input id="visitor-name" name="visitor-name" placeholder="Your name" value="'+vnam+'"><sml id="name-error" style="font-size: 10px;color: #f00;"></sml></p>';
	  	options_section 	+=	'<p><input id="visitor-mobile" name="visitor-mobile" placeholder="Your mobile" onkeypress="javascript:return isNumber(event)" value="'+vphn+'"><sml id="phone-error" style="font-size: 10px;color: #f00;"></sml></p>';
	  	options_section 	+=	'<p><input id="visitor-email" name="visitor-email" placeholder="Your email" value="'+veml+'"><sml id="email-error" style="font-size: 10px;color: #f00;"></sml></p>';
	  	options_section 	+=	'</div>';
	  	
	  	options_section 	+=	'<div class="tab">';
	  	options_section 	+=	'Loading...<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
	  	options_section 	+=	'</div>';
        options_section 	+= '<div class="col-12">';
        
        options_section 	+=	'<div style="overflow:auto;">';
        options_section     +=  '<table width="80%"><tr>';
        options_section     +=  '<td><div style="float:left;"><button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button></div></td>';
        options_section     +=  '<td><div style="float:right;"><sml id="nextbtn-text" style="font-weight:bold; font-size: 12px;"></sml><button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button></div></td>';
        options_section     +=  '</tr></table>';
	  	options_section 	+=	'</div>';
	  	
        options_section     +=  '</div>';
	  				
	  	options_section 	+=	'<div style="text-align:center;margin-top:40px;">';
	  	options_section 	+=	'<span class="step"></span>';
	  	options_section 	+=	'<span class="step"></span>';
	  	options_section 	+=	'<span class="step"></span>';
	  	options_section 	+=	'<span class="step"></span>';
	  	options_section 	+=	'</div>';
 		
 		document.getElementById('main-content').innerHTML = options_section;
		showTab(currentTab);
//}
});


window.addEventListener("click", (e) => {
    console.log('You knocked:', e);
    userAgent = navigator.userAgent;
    if(userAgent.match(/chrome|chromium|crios/i)){
        //ele_type = e.path[0].type;
        //selected_optionid = e.path[0].attributes[2].value;
        ele_type = e.srcElement.type;
        // selected_optionid = e.originalTarget.attributes[2].value;
        selected_optionid = e.target.attributes[3].value;
        
        for(var ai=0; ai<e.target.attributes.length; ai++){
            // console.log(e.target.attributes[ai].name+" "+e.target.attributes[ai].value);
            if(e.target.attributes[ai].name == 'data-id'){
                selected_optionid = e.target.attributes[ai].value; break;
            }
        }
    }
    else if(userAgent.match(/firefox|fxios/i)){
        ele_type = e.srcElement.type;
        selected_optionid = e.originalTarget.attributes[3].value;
    }
    // console.log("1 selected_optionid",selected_optionid);
    // console.log('type',e.srcElement.type);
    
    // if(e.path[0].type == 'radio'){
    if(ele_type == 'radio'){
    	pdfFile = '';
		imageFile = '';
    	//let selected_optionid = e.path[0].attributes[2].value;

			//console.log("university_data_array:",university_data_array);
			let pdfdata = university_data_array.map((univ,unii) => {
				if(univ.nuniversity_id == university_id){
					return univ.vpdf_data;
				}
			});
			//console.log("pdfdata:",pdfdata);
			pdfdata.forEach((pdfv,pdfi) => {
				if(pdfv != undefined){
					for (const optid in pdfv) {
						if(optid == selected_optionid){
							pdfFile = pdfv[optid];
						}
					}
				}	
			});
			
			let imagedata = university_data_array.map((univ,unii) => {
				if(univ.nuniversity_id == university_id){
					return univ.vimage_data;
				}
			});
			imagedata.forEach((imgv,imgi) => {
				if(imgv != undefined){
					for (const optidi in imgv) {
						if(optidi == selected_optionid){
							imageFile = imgv[optidi];
						}
					}
				}	
			});
		
		console.log("pdfFile:",pdfFile);
        pdfFiletext = pdfFile.replace("2022/11/", "");
        pdfFiletext = pdfFiletext.replace(".pdf", "");
		//document.getElementById("h2text").innerHTML = pdfFiletext;
		
		//var newurl = window.location.href+'&pagename='+str_replace(' ', '-',$pdfFiletext);
		
        search_params.set('university_id', pdfFiletext);
        current_url.search = search_params.toString();
    
        var new_url = current_url.toString();
    	
    	new_url = current_url.origin+"/university-formats-guidelines/<?php echo $univ_param; ?>/"+e.originalTarget.attributes[2].value;
        console.log("new_url",new_url);
        //console.log('selected_optionid', selected_optionid);
		//console.log("imageFile:",imageFile);
    	pdfObj = document.getElementById("pdftab");
		
		let preview_imgurl = upload_base_url + "/" + imageFile;
    	let features = '<div class="alert alert-warning" role="alert">Sample Content</div>';//'Sample content: for Option:'+selected_optionid+' Features:'+university_id+'<br>';
		features += '<img src="'+preview_imgurl+'" class="rounded mx-auto d-block" alt="some text">';
    	pdfObj.innerHTML = features;
    	
    	window.open(new_url, "_self");
    }
    //else if(e.path[0].id == 'visitor-verify'){
    else if(ele_type == 'visitor-verify'){
    	//console.log("Visitor verify:", e.path[0].id);
    	let otpbox = document.getElementById("visitor-otp");
    	//console.log("otpbox = OTP:", otpbox+" = "+otp);
    	if(otpbox.value==otp){
    		let pdfMessage = '<div class="alert alert-success" role="alert">';
  			pdfMessage += '<h4 class="alert-heading">Well done!</h4>';
 			pdfMessage += '<p>Aww yeah, sent you the selected PDF download link on your email id. Thanks</p>'
  			pdfMessage += '<hr>';
  			//pdfMessage += '<p class="mb-0"><button class="btn btn-secondary" onclick="download()">Download</button></p>';
			pdfMessage += '</div>';
			document.getElementById('main-content').innerHTML = pdfMessage;
    	}
    	else{
    		alert("Not correct");
    	}
    }
    //else if(e.path[0].id == 'visitor-resendotp'){
    else if(ele_type == 'visitor-resendotp'){    
    	let eml = document.getElementById("visitor-email");
    	sendOTP(eml.value, otp);
    }
    
});
function sendPDF(vname,vphone,emailid,pdfurl){
	var url = location.origin + "/wp-content/plugins/rnb-pdf-download/ajax/sendPdf.php";
	var data = new FormData();
	data.append('vname', vname);
	data.append('vphone', vphone);
    data.append('emailid', emailid);
    data.append('url', pdfurl);
    // params.has('test')
    // console.log("seaarch_params:", search_params.get('pg'));
    data.append('pdfFiletext', '<?php echo $univ_param." (".$optionname_param.")"; ?>');
    
    localStorage.setItem('vname_phd', vname);
    localStorage.setItem('vphone_phd', vphone);
    localStorage.setItem('vemail_phd', emailid);
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		if(this.responseText != 'error'){
			console.log("response:", this.responseText);
			
			let pdfMessage1 = '<div class="alert alert-success" role="alert">';
  			pdfMessage1 += '<h4 class="alert-heading">Well done!</h4>';
 			pdfMessage1 += '<p>Aww yeah, sent you the selected PDF download link on your email id. Thanks</p>'
  			pdfMessage1 += '<hr>';
			var upload_base_url0 = '<?php echo $upload_dir['baseurl']; ?>';
			var url0 = upload_base_url0 + "/" + pdfFile;
			//pdfMessage1 += '<p class="mb-0"><a href="'+url0+'" class="btn btn-secondary" target="_blank">Download</button></p>';
			
			pdfMessage1 += '</div>';
			document.getElementById('main-content').innerHTML = pdfMessage1;
		}
    }
    };
    xhttp.open("POST", url, true);
    xhttp.setRequestHeader("enctype","multipart/form-data");
    xhttp.send(data);
}
function generateOTP(){
	const random_num = Math.floor(Math.random() * 9000 + 1000);
	const alphabet = "abcdefghijklmnopqrstuvwxyz"
	const single_char = alphabet[Math.floor(Math.random() * alphabet.length)]
	return single_char.toUpperCase()+'-'+random_num;
}

function sendOTP(emailid,otp){
	var url = location.origin + "/wp-content/plugins/rnb-pdf-download/ajax/sendOtp.php";
    var data = new FormData();
    data.append('emailid', emailid);
    data.append('otp', otp);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		if(this.responseText != 'error'){
			console.log("response:", this.responseText);
		}
    }
    };
    xhttp.open("POST", url, true);
    xhttp.setRequestHeader("enctype","multipart/form-data");
    xhttp.send(data);
}

function download(){
	//Set the File URL.
	let upload_base_url = '<?php echo $upload_dir['baseurl']; ?>';
	var url = upload_base_url + "/" + pdfFile;
	var filep = pdfFile.split('/');
	var fileName = filep[2];
	//Create XMLHTTP Request.
	var req = new XMLHttpRequest();
	req.open("GET", url, true);
	req.responseType = "blob";
	req.onload = function () {
	    //Convert the Byte Data to BLOB object.
	    var blob = new Blob([req.response], { type: "application/octetstream" });

	    //Check the Browser type and download the File.
	    var isIE = false || !!document.documentMode;
	    if (isIE) {
	        window.navigator.msSaveBlob(blob, fileName);
	    } else {
	        var url = window.URL || window.webkitURL;
	        link = url.createObjectURL(blob);
	        var a = document.createElement("a");
	        a.setAttribute("download", fileName);
	        a.setAttribute("href", link);
	        document.body.appendChild(a);
	        a.click();
	        document.body.removeChild(a);
	    }
	}
	req.send();
}

function showTab(n) {
  // This function will display the specified tab of the form ...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  // ... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if(n == 2){
      document.getElementById("nextbtn-text").innerHTML = "To download full document click here";
  }
  else{
      document.getElementById("nextbtn-text").innerHTML = "";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").style.display = "none"; //innerHTML = "Submit";
  } else {
    document.getElementById("nextBtn").style.display = "block";
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  // ... and run a function that displays the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // if (n == 1) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form... :
  if (currentTab >= x.length) {
    //...the form gets submitted:
    document.getElementById("regForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");

    var visitoremail = '';
    var visitorname = '';
    var visitorphone = '';
  // A loop that checks every input field in the current tab:
  
    if(Number(currentTab) == 0){
      
        var cont=0;
        for (i = 0; i < y.length; i++) {
          if(y[i].checked == false){        
            cont++;
          }
        }
        console.log("click7 "+cont+" "+y.length);
        if(Number(cont) == Number(y.length)){
          valid = false;
        }
  
    }
    else if(Number(currentTab) == 1){
      
    }
    else if(Number(currentTab) == 2){
        
        for (i = 0; i < y.length; i++) {
            if (y[i].value == "") {
                y[i].className += " invalid";
                valid = false;
            }
            if(i == 0){
                visitorname = y[i].value;
                if(Number(visitorname.length)<3){
                    valid = false;
                    document.getElementById("name-error").innerHTML = "Name must have atleast 3 characters";
                }
                else{
                    document.getElementById("name-error").innerHTML = "";
                }
            }
            else if(i == 1){
                visitorphone = y[i].value;
                if(Number(visitorphone.length)!=10){
                    valid = false;
                    document.getElementById("phone-error").innerHTML = "Phone No. must  be of 10 digits";
                }
                else{
              	    document.getElementById("phone-error").innerHTML = "";
                }
            }
            else if(i == 2){
                var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if(y[i].value.match(mailformat) == null){
                    valid = false;
                    document.getElementById("email-error").innerHTML = "Invalid format of email id";
                }
                else{
              	    visitoremail = y[i].value;
              	    document.getElementById("email-error").innerHTML = "";
                }
            }
        }
        
    	/*if(valid==true){
    	    document.getElementById('main-content').innerHTML = 'Loading...';
    		//sendOTP(visitoremail, otp);
    		//OR
    		var upload_base_url1 = '<?php echo $upload_dir['baseurl']; ?>';
			//var url1 = upload_base_url1 + "/" + pdfFile;
			
			var url_temp = pdfFile.split('/');
    		console.log('url_temp:', url_temp);
			var url1 = upload_base_url1 + "/" + url_temp[0] + "/" + url_temp[1]  + "/" + encodeURIComponent(url_temp[2]);
			console.log('url1:',url1)
			sendPDF(visitorname, visitorphone, visitoremail, url1);
    	}*/
    	
    	if(valid==true){
    	    //console.log('preview_imgurl_onload', preview_imgurl_onload);
    	    //console.log('preview_pdfurl_onload', preview_pdfurl_onload);
    	    document.getElementById('main-content').innerHTML = 'Loading...';
    		var upload_base_url1 = '<?php echo $upload_dir['baseurl']; ?>';
			var url_temp = preview_pdfurl_onload.split('/');
    		console.log('url_temp:', url_temp);
			var url1 = upload_base_url1 + "/" + url_temp[5] + "/" + url_temp[6] + "/" + encodeURIComponent(url_temp[7]);
			//console.log('pdfurl1:',url1)
			sendPDF(visitorname, visitorphone, visitoremail, url1);
    	}
        
    }
    else if(Number(currentTab) == 3){
      
    }
  	return valid; // return the valid status
}

function isNumber(evt) {
  var iKeyCode = (evt.which) ? evt.which : evt.keyCode
  if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57)) {
      return false;
  } else {
      return true;
  }
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class to the current step:
  x[n].className += " active";
}	
</script>	
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<?php //get_footer(); ?>