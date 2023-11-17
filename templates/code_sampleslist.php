<?php 
// get_header();
// require_once('lib/class.pdf2text.php');
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
li.list-group-item {
    text-transform: capitalize;
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
$table_name1 = $wpdb->prefix . "rnb_courses";
$table_name2 = $wpdb->prefix . "rnb_options";
$table_name3 = $wpdb->prefix . "rnb_pdf";
$table_name4 = $wpdb->prefix . "rnb_category";

//$courserows
// $courserows  = $wpdb->get_results("SELECT t1.ncourse_id, t1.vcourse_title, t1.ncategory_id, t2.vcategory_title
// 					FROM $table_name1 t1
// 					JOIN $table_name4 t2 on t2.ncategory_id = t1.ncategory_id
// 					ORDER BY t1.ncategory_id ASC", ARRAY_A);
$upload_dir = wp_upload_dir();
$site  = $wpdb->get_results("SELECT option_name, option_value
                    FROM wp_options
                    WHERE option_name='siteurl'", ARRAY_A);
$dbsiteurl = $site[0]['option_value'];

$courserows = $wpdb->get_results("SELECT t1.ncourse_id, t1.vcourse_title, t1.ncategory_id, t1.vslug, t4.vcategory_title, t4.vslug as ctslug, t3.npdf_id, t3.vpdf_data, t3.vimage_data, t3.vpdf_type
					FROM $table_name1 t1
                    JOIN $table_name4 t4 on t4.ncategory_id = t1.ncategory_id
					JOIN $table_name3 t3 on t3.nuniversity_id = t1.ncourse_id
					WHERE t3.vpdf_type='samples'
					ORDER BY t1.vcourse_title ASC", ARRAY_A);
foreach($courserows as $ukey => $uvalue){
	$vpdfdata = json_decode($courserows[$ukey]['vpdf_data'], 1);
	foreach($vpdfdata as $vp => $vpval){
    	$opt_row = $wpdb->get_results("SELECT noption_id, voption_title, noption_order, voption_type, vslug from $table_name2 WHERE noption_id=".$vp." and voption_type='sample' order by noption_order", ARRAY_A);
    	$courserows[$ukey]['optionslug'] = $opt_row[0]['vslug']; break;
    }
}
?>
<div class="container">
	<div class="row align-items-start"><br>
		<div class="col-12 text-start">
			<div class="alert alert-warning text-center" role="alert">
				<strong><a href="http://phdguides.org/university-formats-guidelines/" target="_blank" class="btn btn-primary" style="font-weight:bold;">Also see our University Formats &amp; Guidelines</a></strong>
			</div>
		</div>
    </div>
    <div class="row align-items-start">
        <div class="col-12 text-start text-center" style="margin-bottom:10px;"><h1>Research Writing Sample</h1></div>
        <div class="col-12 text-start text-center" style="margin-bottom:10px;">If you’re getting ready to write your dissertation, thesis, or research project for your PhD, our free research writing samples are a great way to start. Here you can find review article samples and research article samples and some examples to which you can refer for your purposes. You can download our free sample PDF templates related to streams and subjects in seconds. We have a massive sample database, depending on the PhD programme, you can find and download appropriate PDF samples. Download suitable absolutely free resources according to your requirement.</div>       
    </div>
    <div class="row align-items-start">
        <div class="col-2 text-start">&nbsp;</div>
        <div class="col-8 text-start text-center">
            <nav class="navbar navbar-light bg-light">
              <form class="form-inline">
                <input class="form-control" id="searched" type="search" placeholder="Subject name" aria-label="Search" style="    width: 84%;border-color:#0275d8;" onkeyup="myFunction()">&nbsp;&nbsp;
                <button class="btn btn-primary my-2 my-sm-0" type="button" onclick="highlight('0');">Search</button>
              </form>
            </nav>
        </div>
        <div class="col-2 text-start">&nbsp;</div>
    </div>
    <div class="row align-items-start">
        <div class="col-12"><hr></div>
    </div>
    
    <div class="container sample-list">
        <?php
        $courserows1=array();
        $catg = '';

        foreach($courserows as $ckey => $cvalues){
            if($catg!=$cvalues['vcategory_title']){
                $catg=$cvalues['vcategory_title'];
                $courserows1[$courserows[$ckey]['vcategory_title']][]  = $courserows[$ckey];
            }
            else{
                $courserows1[$courserows[$ckey]['vcategory_title']][]  = $courserows[$ckey];  
            }
        }
        //echo "<pre>"; print_r($courserows1); echo "</pre>";
        
        $catgcount=0; $div=''; $indiv='';

        foreach($courserows1 as $ckey => $cvalues){
            // echo "Course title:".$cvalues[0]['vcourse_title'];
            // echo "<pre>"; print_r($cvalues); echo "</pre>";
            $stream = $ckey;
            $catgcount++;
            $indiv .= '<div class="col-sm text-start">';
            $indiv .= '<ul class="list-group" style="margin-left: 0px;">';
            $indiv  .= '<li class="list-group-item" style="background:#000464;"><strong><a  style="color:#abe0df;text-transform:uppercase;">'.$stream.'</a></strong></li>';
            foreach($cvalues as $cvkey => $cvvalues){
                // $indiv .= '<li class="list-group-item"><a href="'.$dbsiteurl.'/options?id='. $cvvalues['ncourse_id'].'&tab_type=sample-id&optid=na&pg='.str_replace(' ','-',trim($cvvalues['vcourse_title'])).'" target="_blank">'.$cvvalues['vcourse_title'].'</a></li>';    
                // $indiv .= '<li class="list-group-item"><a href="'.$dbsiteurl.'/subject/'.$cvvalues['ctslug'].'/'.$cvvalues['vslug'].'/'.$cvvalues['optionslug'].'" target="_blank">'.$cvvalues['vcourse_title'].'</a></li>';    
                $indiv .= '<li class="list-group-item"><a href="'.$dbsiteurl.'/research-writing-samples/'.$cvvalues['vslug'].'/'.$cvvalues['optionslug'].'" target="_blank">'.$cvvalues['vcourse_title'].'</a></li>';    
            }
            $indiv .= '</ul>';
            $indiv .= '</div>';
            if($catgcount == 3){
                $catgcount=0;
                $div .= '<div class="container universities-list">';
                $div .= '<div class="container">';
                $div .= '<div class="row align-items-start">';
                $div .= $indiv;
                $div .= '</div>';
                $div .= '</div>';
                $div .= '</div>';
                echo $div;
                $div=$indiv='';
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
	var ob = new Mark(document.querySelector(".sample-list"));
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