<?php 
//get_header();
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
<style>
mark.a0 {
	color: black;
	padding: 5px;
	background: greenyellow;
}

mark.a1 {
	color: black;
	padding: 5px;
	background: cyan;
}

mark.a2 {
	color: black;
	padding: 5px;
	background: red;
}

mark.a3 {
	color: white;
	padding: 5px;
	background: green;
}

mark.a4 {
	color: black;
	padding: 5px;
	background: pink;
}
</style>
<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
<!---- mark the searched start--->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"
		integrity=
"sha512-5CYOlHXGh6QpOFA/TeTylKLWfB3ftPsde7AnmhuitiTX4K5SqCLBeKro6sPS8ilsz1Q4NRx3v8Ko2IBiszzdww=="
		crossorigin="anonymous">
	</script>
<link rel="stylesheet" href=
"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
		integrity=
"sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ"
		crossorigin="anonymous">
<!---- mark the searched end--->  
<?php
global $wpdb;
$table_name1 = $wpdb->prefix . "rnb_universities";
$table_name2 = $wpdb->prefix . "rnb_options";
$table_name3 = $wpdb->prefix . "rnb_pdf";
// $universityrows  = $wpdb->get_results("SELECT nuniversity_id, vuniversity_title, vslug
// 					FROM $table_name1 
// 					ORDER BY vuniversity_title ASC", ARRAY_A);
// $upload_dir = wp_upload_dir();

$site  = $wpdb->get_results("SELECT option_name, option_value
                    FROM wp_options
                    WHERE option_name='siteurl'", ARRAY_A);
$dbsiteurl = $site[0]['option_value'];

$universityrows = $wpdb->get_results("SELECT t1.nuniversity_id, t1.vuniversity_title, t1.vslug, t2.npdf_id, t2.vpdf_data, t2.vimage_data, t2.vpdf_type
					FROM $table_name1 t1
					JOIN $table_name3 t2 on t2.nuniversity_id = t1.nuniversity_id
					WHERE t2.vpdf_type='university'
					ORDER BY t1.vuniversity_title ASC", ARRAY_A);
foreach($universityrows as $ukey => $uvalue){
	$vpdfdata = json_decode($universityrows[$ukey]['vpdf_data'], 1);
	foreach($vpdfdata as $vp => $vpval){
    	$opt_row = $wpdb->get_results("SELECT noption_id, voption_title, noption_order, voption_type, vslug from $table_name2 WHERE noption_id=".$vp." and voption_type='university' order by noption_order", ARRAY_A);
    	$universityrows[$ukey]['optionslug'] = $opt_row[0]['vslug']; break;
    }
}
//echo json_encode($universityrows);
?>
<div class="container">
    <!--<div class="row align-items-start">-->
	   <!-- <div class="col-12 text-start">-->
    <!--        <img src="http://phdguides.org/wp-content/uploads/2022/12/banner02.jpg" class="img-fluid">-->
    <!--    </div>-->
    <!--</div>-->
	<div class="row align-items-start"><br>
		<div class="col-12 text-start">
			<div class="alert alert-warning text-center" role="alert">
				<strong><a href="http://phdguides.org/research-writing-samples/" target="_blank" class="btn btn-primary" style="font-weight:bold;">Also see our Research Writing Sample</a></strong>
			</div>
		</div>
    </div>
    <div class="row align-items-start">
        <div class="col-12 text-start text-center" style="margin-bottom:10px;"><h1>University Formats & Guidelines</h1></div>
		<div class="col-12 text-start text-center"><h4>Explore the Different Types of PhD Review Formats: Which One is Right for You?</h4></div>
        <div class="col-12 text-start text-center" style="margin-bottom:10px;">The Complete University Guide helps PhD students to make right Assignment formats, Review Formats, Synopsis Formats, University thesis formats and Thesis Formats according to different universities. Here you can find our PdF formats and guidelines for proceedings papers for your PhD. PhD Guides are here to guide you to prepare your work with accuracy. Download suitable absolutely free resources according to your requirement.</div>
    </div>
    <div class="row align-items-start">
        <div class="col-2 text-start">&nbsp;</div>
        <div class="col-8 text-start text-center">
            <nav class="navbar navbar-light bg-light">
              <form class="form-inline">
                <input class="form-control" id="searched" type="search" placeholder="University name" aria-label="Search" style="    width: 84%;border-color:#0275d8;" onkeyup="myFunction()">&nbsp;&nbsp;
                <button class="btn btn-primary my-2 my-sm-0" type="button" onclick="highlight('0');">Search</button>
              </form>
            </nav>
        </div>
        <div class="col-2 text-start">&nbsp;</div>
    </div>
    <div class="row align-items-start">
        <div class="col-12"><hr></div>
    </div>
    <!--<div class="row align-items-start universities-list">
        <?php
        /*foreach($universityrows as $ukey => $uvalue){ 
        	if($count == 0)
            {
                echo '<div class="col text-start">';
                echo '<ul class="list-group" style="margin-left: 0px;">';
            }
    
	        echo '<li class="list-group-item"><a href="https://phdguides.org/options?id='. $uvalue['nuniversity_id'].'&tab_type=university-id">'.$uvalue['vuniversity_title'].'</a></li>';
	
        	if($count == 4){
        	    echo "</ul></div>";
                $count=0;
        	}
        	else{
        	    $count++;
        	}
        }*/
        ?>
    </div>-->
    
    <div class="container universities-list">
        <?php
        $cols=0;
        $div='';
        $li='';
        $modby = 10;
        foreach($universityrows as $ukey => $uvalue){
            // echo "<pre>"; print_r($uvalue); echo "</pre>";
        	$ukey=$ukey+1;
        	if(($ukey%$modby) == 0){
        	    //$li .= '<li class="list-group-item"><a href="'.$dbsiteurl.'/university?id='. $uvalue['nuniversity_id'].'&tab_type=university-id&optid=na&pg='.str_replace(' ','-',trim($uvalue['vuniversity_title'])).'" target="_blank">'.$uvalue['vuniversity_title'].'</a></li>';
        	    $li .= '<li class="list-group-item"><a href="'.$dbsiteurl.'/university-formats-guidelines/'. $uvalue['vslug'].'/'.$uvalue['optionslug'].'" target="_blank">'.$uvalue['vuniversity_title'].'</a></li>';
            	$div .= '<div class="col-sm text-start">';
        	    $div .= '<ul class="list-group" style="margin-left: 0px;">';
        	    $div .= $li;
        	    $div .= "</ul>";
        	    $div .= "</div>";
        	    $cols++;
        	    $li = '';
        	}
        	else{
        	    //$li .= '<li class="list-group-item"><a href="'.$dbsiteurl.'/university?id='. $uvalue['nuniversity_id'].'&tab_type=university-id&optid=na&pg='.str_replace(' ','-',trim($uvalue['vuniversity_title'])).'" target="_blank">'.$uvalue['vuniversity_title'].'</a></li>';
        	    //$li .= '<li class="list-group-item"><a href="'.$dbsiteurl.'/options/'. $uvalue['nuniversity_id'].'/university-id/na/'.strtolower(str_replace(' ','-',trim($uvalue['vuniversity_title']))).'" target="_blank">'.$uvalue['vuniversity_title'].'</a></li>';
            	$li .= '<li class="list-group-item"><a href="'.$dbsiteurl.'/university-formats-guidelines/'. $uvalue['vslug'].'/'.$uvalue['optionslug'].'" target="_blank">'.$uvalue['vuniversity_title'].'</a></li>';
            }
        	
        	if($cols==5){
        	    echo '<div class="container">';
		        echo '<div class="row align-items-start">';
        	    echo $div;
        	    echo '</div>';
        	    echo '</div>';
        	    $div = '';
        	    $li = '';
        	    $cols = 0;
        	}
        	
        	
        }
        ?>
    </div>
</div>

</div>
<script>
function myFunction() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("searched");
    filter = input.value.toUpperCase();
    ul = document.getElementsByClassName("list-group");
    for (var u = 0; u < ul.length; u++) {
      li = ul[u].getElementsByTagName("li");
      for (i = 0; i < li.length; i++) {
          a = li[i].getElementsByTagName("a")[0];
          console.log(a)
          txtValue = a.textContent || a.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
              li[i].style.display = "";
          } else {
              li[i].style.display = "none";
          }
      }
    }
}
function highlight(param) {
	// Select the whole paragraph
	var ob = new Mark(document.querySelector(".universities-list"));
	// First unmark the highlighted word or letter
	ob.unmark();
	// Highlight letter or word
	ob.mark(
		document.getElementById("searched").value,
		{ className: 'a' + param }
	);
}
</script>
<?php //get_footer(); ?>