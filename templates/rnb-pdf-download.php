<?php 
get_header();
require_once('lib/class.pdf2text.php');
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
$universityrows  = $wpdb->get_results("SELECT t1.nuniversity_id, t1.vuniversity_title, t2.npdf_id, t2.vpdf_data, t2.vimage_data
					FROM $table_name1 t1
					JOIN $table_name3 t2 on t2.nuniversity_id = t1.nuniversity_id
					WHERE t2.vpdf_type='university'
					ORDER BY t1.vuniversity_title ASC", ARRAY_A);
foreach($universityrows as $ukey => $uvalue){ 
	$universityrows[$ukey]['vpdf_data'] = json_decode($universityrows[$ukey]['vpdf_data'], 1);
	$universityrows[$ukey]['vimage_data'] = json_decode($universityrows[$ukey]['vimage_data'], 1);
}	

$courserows  = $wpdb->get_results("SELECT t1.ncourse_id, t1.vcourse_title, t2.npdf_id, t2.vpdf_data, t2.vimage_data 
					FROM wp_rnb_courses t1 
					JOIN wp_rnb_pdf t2 on t2.nuniversity_id = t1.ncourse_id 
					WHERE t2.vpdf_type='course' 
					ORDER BY t1.vcourse_title ASC", ARRAY_A);
foreach($courserows as $ckey => $cvalue){ 
	$courserows[$ckey]['vpdf_data'] = json_decode($courserows[$ckey]['vpdf_data'], 1);
	$courserows[$ckey]['vimage_data'] = json_decode($courserows[$ckey]['vimage_data'], 1);
}	

//echo "University:<pre>"; print_r($universityrows); echo "</pre>";
//echo "Course:<pre>"; print_r($courserows); echo "</pre>";
$optionrows = $wpdb->get_results("SELECT noption_id, voption_title, noption_order, voption_type from $table_name2 order by noption_order");

//echo "<pre>"; print_r($optionrows); echo "</pre>";

$upload_dir = wp_upload_dir();
?>
<div class="container">
  <div class="row align-items-start">
	  <div class="col-4 text-start">
        
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#university" name="university"><strong>University</strong></a></li>
            <li><a data-toggle="tab" href="#courses" name="course"><strong>Subject</strong></a></li>
        </ul>

        <div class="tab-content">
            <div id="university" class="tab-pane fade in active">
                <div class="rnb-university-scroll-bar" style="height: 600px; overflow: scroll;">
        	      	<ul class="list-group">
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
        				<?php 
        	      		foreach($courserows as $ckey => $cvalue){ ?>
        					<li class="list-group-item university" aria-current="true" course-id="<?php echo $cvalue['ncourse_id']; ?>"><?php echo $cvalue['vcourse_title']; ?></li>
        				<?php } ?>
        			</ul>
        		</div>	
            </div>
        </div>
    </div>
    <div class="col-8">
    	<div class="rnb-main-content" id="main-content">
      		
      	</div>
    </div>
<!--     <div class="col text-start">
    	<div class="rnb-university-scroll-bar">
	      	<ul class="list-group">
	      		<?php 
	      		foreach($universityrows as $ukey => $uvalue){ ?>
					<li class="list-group-item university" aria-current="true" university-id="<?php echo $uvalue['nuniversity_id']; ?>"><?php echo $uvalue['vuniversity_title']; ?></li>
				<?php } ?>
			</ul>
		</div>	
    </div>
    <div class="col-6">
    	<div class="rnb-main-content" id="main-content">
      		
      	</div>
    </div>
    <div class="col text-end">
    	<div class="rnb-course-scroll-bar">
	      	<ul class="list-group">
				<li class="list-group-item active" aria-current="true">An active item</li>
				<li class="list-group-item">A second item</li>
				<li class="list-group-item">A third item</li>
				<li class="list-group-item">A fourth item</li>
				<li class="list-group-item">And a fifth one</li>
			</ul>
		</div>	
    </div> -->
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<script>
let hostname = location.hostname;
var currentTab = 0; // Current tab is set to be the first tab (0)
var university_id = 0;
var course_id = 0;
var pdfFile = '';
var otp = 'A-2132';
var tab_type = 'university-id';
window.addEventListener("click", (e) => {
    console.log("You knocked? e:",e);
	
	var university_data = 	'<?php echo json_encode($universityrows); ?>';
	var options_data 	=	'<?php echo json_encode($optionrows); ?>';
    var course_data = '<?php echo json_encode($courserows); ?>';
	
    var university_data_array = JSON.parse(university_data);
	var options_data_array = JSON.parse(options_data);
	var course_data_array = JSON.parse(course_data);
	
	var upload_base_url = '<?php echo $upload_dir['baseurl']; ?>';
	
	// console.log("Type:", e.path[0].attributes[2].name);
	
    if(e.path[0].attributes[2].name == 'university-id'){
		
		tab_type = e.path[0].attributes[2].name;
		
		let all_li = document.getElementsByClassName("list-group-item");
        
        for(var ii=0; ii<all_li.length; ii++){
            all_li[ii].classList.remove("active");
        }
        
        e.path[0].classList.add("active");
		
    	otp = generateOTP();

    	currentTab = 0;

  		let options_section = '';
  		university_id = e.path[0].attributes[2].value;

  		//options_section += '<h1>Download '+university_id+'</h1>';
	  	options_section += '<div class="tab" id="tabpanes">';
		options_section += '<h3><u>Document Type</u></h3>';

		options_data_array.map((optv,opti) => {

			if(optv.voption_type == 'university'){
            	
            	if(tab_type == 'university-id'){

            		let pdfdataa = university_data_array.map((univ,unii) => {
                		if(univ.nuniversity_id == university_id){
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
									options_section += '<input type="radio" for="regForm" value="'+optv.noption_id+'" name="seloptions" seluniversity="'+university_id+'">';
									options_section += '</div>';
									options_section += '</div>';
									options_section += '</div>';
                                
                        		}
                    		}
                		}		
            		});
        		}

			}

		});

		options_section += '</div>';
    
	  	options_section	+=	'<div class="tab" id="pdftab">Sample content:';
	  	if(Number(university_id) <= 3 ){
	      options_section   +=  '<br><strong>Title1</strong><br><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><br>';
	    }
	    else if(Number(university_id) > 3 ){
	     options_section   +=  '<br><strong>Title1</strong><br><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><br>';
	    }
	  	options_section 	+=	'</div>';

	  	options_section 	+=	'<div class="tab">General info:';
	  	options_section 	+=	'<p><input id="visitor-name" name="visitor-name" placeholder="Your name"></p>';
	  	options_section 	+=	'<p><input id="visitor-mobile" name="visitor-mobile" placeholder="Your mobile" onkeypress="javascript:return isNumber(event)"></p>';
	  	options_section 	+=	'<p><input id="visitor-email" name="visitor-email" placeholder="Your email"></p>';
	  	options_section 	+=	'</div>';

	  	options_section 	+=	'<div class="tab">Verify:';
	  	options_section 	+=	'<p><input id="visitor-otp" name="visitor-otp" placeholder="OTP"></p>';
	  	options_section   	+=  '<p>';
	    options_section   	+=  '<button id="visitor-resendotp" name="visitor-resendotp" type="button">Resend OTP</button>';
	    options_section   	+=  '<button id="visitor-verify" name="visitor-verify" type="button">Verify</button>';
	    options_section   	+=  '</p>';
	  	options_section 	+=	'</div>';

	  	options_section 	+=	'<div style="overflow:auto;">';
	  	options_section 	+=	'<div style="float:right;">';
	  	options_section 	+=	'<button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>';
	  	options_section 	+=	'<button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>';
	  	options_section 	+=	'</div>';
	  	options_section 	+=	'</div>';

	  				
	  	options_section 	+=	'<div style="text-align:center;margin-top:40px;">';
	  	options_section 	+=	'<span class="step"></span>';
	  	options_section 	+=	'<span class="step"></span>';
	  	options_section 	+=	'<span class="step"></span>';
	  	options_section 	+=	'<span class="step"></span>';
	  	options_section 	+=	'</div>';
 		
 		document.getElementById('main-content').innerHTML = options_section;
		showTab(currentTab);
    }
	else if(e.path[0].attributes[2].name == 'course-id'){
		
		tab_type = e.path[0].attributes[2].name;
		
		let all_li = document.getElementsByClassName("list-group-item");
        
        for(var ii=0; ii<all_li.length; ii++){
            all_li[ii].classList.remove("active");
        }
        
        e.path[0].classList.add("active");
		
    	otp = generateOTP();

    	currentTab = 0;

  		let options_section = '';
		//console.log("course id:", e.path[0].attributes[2].value);
  		course_id = e.path[0].attributes[2].value;

  		options_section += '<h1>Download '+course_id+'</h1>';
	  	options_section += '<div class="tab" id="tabpanes">';
		options_section += '<h4><u>Select an option</u></h4>';

		options_data_array.map((optv,opti) => {

			if(optv.voption_type == 'course'){

				options_section += '<div id="tab1">';
				options_section += '<div class="position-relative row form-group">';
				options_section += '<label for="txtname" class="col-sm-3 col-form-label">'+optv.voption_title+'</label>';
				options_section += '<div class="col-sm-9">';
				options_section += '<input type="radio" for="regForm" value="'+optv.noption_id+'" name="seloptions" seluniversity="'+university_id+'">';
				options_section += '</div>';
				options_section += '</div>';
				options_section += '</div>';

			}

		});

		options_section += '</div>';

	  	options_section	+=	'<div class="tab" id="pdftab">Sample content:';
	  	if(Number(course_id) <= 3 ){
	      options_section   +=  '<br><strong>Title1</strong><br><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><br>';
	    }
	    else if(Number(course_id) > 3 ){
	     options_section   +=  '<br><strong>Title1</strong><br><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><br>';
	    }
	  	options_section 	+=	'</div>';

	  	options_section 	+=	'<div class="tab">General info:';
	  	options_section 	+=	'<p><input id="visitor-name" name="visitor-name" placeholder="Your name"></p>';
	  	options_section 	+=	'<p><input id="visitor-mobile" name="visitor-mobile" placeholder="Your mobile" onkeypress="javascript:return isNumber(event)"></p>';
	  	options_section 	+=	'<p><input id="visitor-email" name="visitor-email" placeholder="Your email"></p>';
	  	options_section 	+=	'</div>';

	  	options_section 	+=	'<div class="tab">Verify:';
	  	options_section 	+=	'<p><input id="visitor-otp" name="visitor-otp" placeholder="OTP"></p>';
	  	options_section   	+=  '<p>';
	    options_section   	+=  '<button id="visitor-resendotp" name="visitor-resendotp" type="button">Resend OTP</button>';
	    options_section   	+=  '<button id="visitor-verify" name="visitor-verify" type="button">Verify</button>';
	    options_section   	+=  '</p>';
	  	options_section 	+=	'</div>';

	  	options_section 	+=	'<div style="overflow:auto;">';
	  	options_section 	+=	'<div style="float:right;">';
	  	options_section 	+=	'<button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>';
	  	options_section 	+=	'<button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>';
	  	options_section 	+=	'</div>';
	  	options_section 	+=	'</div>';

	  				
	  	options_section 	+=	'<div style="text-align:center;margin-top:40px;">';
	  	options_section 	+=	'<span class="step"></span>';
	  	options_section 	+=	'<span class="step"></span>';
	  	options_section 	+=	'<span class="step"></span>';
	  	options_section 	+=	'<span class="step"></span>';
	  	options_section 	+=	'</div>';
 		
 		document.getElementById('main-content').innerHTML = options_section;
		showTab(currentTab);
    }
    else if(e.path[0].type == 'radio'){
    	pdfFile = '';
		imageFile = '';
    	let selected_optionid = e.path[0].attributes[2].value;
		if(tab_type == 'university-id'){
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
		}
		else if(tab_type == 'course-id'){
			//console.log("course_data_array:",course_data_array);
			let pdfdata2 = course_data_array.map((coursev,coursei) => {
				if(coursev.ncourse_id == course_id){
					return coursev.vpdf_data;
				}
			});
			console.log("pdfdata2:",pdfdata2);
			pdfdata2.forEach((pdfv,pdfi) => {
				if(pdfv != undefined){
					for (const optid in pdfv) {
						if(optid == selected_optionid){
							pdfFile = pdfv[optid];
						}
					}
				}	
			});
			
			let imagedata = course_data_array.map((coursev,coursei) => {
				if(coursev.ncourse_id == course_id){
					return coursev.vimage_data;
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
		}
		//console.log("pdfFile:",pdfFile);
    	pdfObj = document.getElementById("pdftab");
		
		let preview_imgurl = upload_base_url + "/" + imageFile;
    	let features = 'Sample content: for Option:'+selected_optionid+' Features:'+university_id+'<br>';
		features += '<img src="'+preview_imgurl+'" class="rounded mx-auto d-block" alt="some text">';
    	pdfObj.innerHTML = features;
    	//pdfObj.innerHTML = '<object type="application/pdf" for="regForm" id="pdfobj" data="http://localhost/wordpress/wp-content/uploads/'+pdf+'" width="100%" height="300" style="height: 85vh;">No Support</object>';
    }
    else if(e.path[0].id == 'visitor-verify'){
    	//console.log("Visitor verify:", e.path[0].id);
    	let otpbox = document.getElementById("visitor-otp");
    	//console.log("otpbox = OTP:", otpbox+" = "+otp);
    	if(otpbox.value==otp){
    		let pdfMessage = '<div class="alert alert-success" role="alert">';
  			pdfMessage += '<h4 class="alert-heading">Well done!</h4>';
 			pdfMessage += '<p>Aww yeah, you successfully verified. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>'
  			pdfMessage += '<hr>';
  			pdfMessage += '<p class="mb-0"><button class="btn btn-secondary" onclick="download()">Download</button></p>';
			pdfMessage += '</div>';
			document.getElementById('main-content').innerHTML = pdfMessage;
    	}
    	else{
    		alert("Not correct");
    	}
    }
    else if(e.path[0].id == 'visitor-resendotp'){
    	let eml = document.getElementById("visitor-email");
    	sendOTP(eml.value, otp);
    }
});

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
            if(i == 2){
                var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if(y[i].value.match(mailformat) == null){
                    valid = false;
                }
                else{
              	    visitoremail = y[i].value;
                }
            }
        }
        
    	if(valid==true){
    		sendOTP(visitoremail, otp);
    	}
        
    }
    else if(Number(currentTab) == 3){
      
    }
  	return valid; // return the valid status
}
/*function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");

  var visitoremail = '';
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false:
      valid = false;
    }

    if(i == 2){
      var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      if(y[i].value.match(mailformat) == null){
        valid = false;
      }
      else{
      	visitoremail = y[i].value;
      }
    }
  }

  var cont=0;
  if(currentTab == 0){
    for (i = 0; i < y.length; i++) {
      if(y[i].checked == false){        
        cont++;
      }
    }
    if(Number(cont) == Number(y.length)){
      valid = false;
    }
  }

  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";

    if(currentTab == 2){
    	if(valid==true){
    		console.log("Send OTP mail to ",visitoremail);
    		sendOTP(visitoremail, otp);
    	}
    }
  }
  return valid; // return the valid status
}*/

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
<?php get_footer(); ?>