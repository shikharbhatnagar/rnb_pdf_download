<?php
function rnb_course_upload() {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "rnb_courses";
    $table_name2 = $wpdb->prefix . "rnb_pdf";
    $table_name3 = $wpdb->prefix . "rnb_options";

    $id = $_GET["id"];
    $name = $_POST["universitytitle"];
    if (isset($_POST['insert'])) {
        $optionids = $_POST['optionid'];
        $optionpdf = $_POST['optionpdf'];
        $optionpreview = $_POST['optionpreview'];
        $ar = array();
        $ar2 = array();
        foreach($optionids as $optkey => $optvalues){
            $ar[$optvalues] =  $optionpdf[$optkey];
            $ar2[$optvalues] =  $optionpreview[$optkey];
        }
        $wpdb->insert(
                $table_name2,
                array('nuniversity_id' => $id, 'vpdf_data' => json_encode($ar), 'vimage_data' => json_encode($ar2), 'vpdf_type' => 'course'),
                array('%s', '%s', '%s')
        );
    }
    else if (isset($_POST['update'])) {
        $optionids = $_POST['optionid'];
        $optionpdf = $_POST['optionpdf'];
        $optionpreview = $_POST['optionpreview'];
        $ar = array();
        $ar2 = array();
        foreach($optionids as $optkey => $optvalues){
            $ar[$optvalues] =  $optionpdf[$optkey];
            $ar2[$optvalues] =  $optionpreview[$optkey];
        }
        $wpdb->update(
                $table_name2,
                array('vpdf_data' => json_encode($ar), 'vimage_data' => json_encode($ar2), 'vpdf_type' => 'course'),
                array('nuniversity_id' => $id),
                array('%s','%s'),
                array('%s')
        );
    }
    else { 
        $university = $wpdb->get_results($wpdb->prepare("SELECT t1.ncourse_id, t1.vcourse_title, t2.npdf_id, t2.vpdf_data, t2.vimage_data FROM $table_name1 t1 LEFT JOIN $table_name2 t2 ON t2.nuniversity_id = t1.ncourse_id WHERE t1.ncourse_id=%s AND t2.vpdf_type='course'", $id));
        foreach ($university as $s) {
            $course_title   = $s->vcourse_title;
			$university_id      = $s->ncourse_id;
            $university_pdfid   = $s->npdf_id;
            $university_pdfdata = $s->vpdf_data;
            $university_imagedata = $s->vimage_data;
        }
        $haspdf = 0;
        $haspreview = 0;
        $university_pdfdata_array = array();
        $university_imagedata_array = array();
        if(!empty($university_pdfdata)){ 
            $haspdf = 1; 
            $university_pdfdata_array = json_decode($university_pdfdata, 1);
        }

        if(!empty($university_imagedata)){ 
            $haspreview = 1; 
            $university_imagedata_array = json_decode($university_imagedata, 1);
        }
        $options = $wpdb->get_results("SELECT noption_id, voption_title, noption_order, voption_type from $table_name3 WHERE voption_type='course' order by noption_order");
        $newoptions = json_decode(json_encode($options), true);
        foreach($newoptions as $okey => $ovalue){
            $found = 0;
            $imgfound = 0;
            $pdf_row = '0';
            $image_row = '0';
            if($haspdf > 0){
                foreach ($university_pdfdata_array as $pdfkey => $pdfrow) { 
                    if($ovalue['noption_id'] == $pdfkey){
                        $found = 1;
                        $pdf_row = $pdfrow;
                        break;
                    }
                }
            }
            if($haspreview > 0){
                foreach ($university_imagedata_array as $imgkey => $imgrow) { 
                    if($ovalue['noption_id'] == $imgkey){
                        $imgfound = 1;
                        $image_row = $imgrow; 
                        break;
                    }
                }
            }
            $newoptions[$okey]['pdfurl'] = $pdf_row;   
            $newoptions[$okey]['previewurl'] = $image_row;  
        }
        $options_temp = json_encode($newoptions);
        $options = json_decode($options_temp);
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/rnb-pdf-download/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2><?php echo "Manage PDF Courses - ".$course_title; ?></h2>
        <?php if ($_POST['insert']) { ?>
            <div class="updated"><p>PDF uploaded</p></div>
            <a href="<?php echo admin_url('admin.php?page=rnb_course_list') ?>">&laquo; Back to course list</a>
        <?php }else if ($_POST['update']) { ?>
            <div class="updated"><p>PDF uploaded</p></div>
            <a href="<?php echo admin_url('admin.php?page=rnb_course_list') ?>">&laquo; Back to course list</a>
        <?php } else { ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
                <table class='wp-list-table widefat fixed striped posts'>
                <tr>
                    <th class="manage-column ss-list-width" width="5%">&nbsp;</th>
                    <th class="manage-column ss-list-width" width="5%">ID</th>
                    <th class="manage-column ss-list-width" width="30%">Option Title</th>
                    <th class="manage-column ss-list-width" width="30%">Upload PDF File</th>
                    <th width="25%">&nbsp;</th>
                </tr>
                <?php $j=0; $i=1; foreach ($options as $row) { 
                    $pdflink = "<a href='".$row->pdfurl."'>Uploaded</a>"; 
                    $imagelink = "<a href='".$row->previewurl."'>Image</a>"; ?>
                    <tr>
                        <td class="manage-column ss-list-width">&nbsp;</td>
                        <td class="manage-column ss-list-width"><?php echo $i++; ?></td>
                        <td class="manage-column ss-list-width"><?php echo $row->voption_title; ?>
                            <input type="text" name="optionid[]" value="<?php echo $row->noption_id; ?>">
                            <input type="text" name="optionpdf[]" value="<?php echo ($row->pdfurl!='0'?$row->pdfurl:'0'); ?>">
                            <input type="text" name="optionpreview[]" value="<?php echo ($row->previewurl!='0'?$row->previewurl:'0'); ?>">
                        </td>
                        <td class="manage-column ss-list-width" id="<?php echo 'td-pdf_'.$row->noption_id; ?>" tdindex="<?php echo $j; ?>"><input type="file" accept=".pdf" name="<?php echo 'pdf_'.$row->noption_id; ?>" id="<?php echo 'pdf_'.$row->noption_id; ?>"></td>
                        <td id="<?php echo 'td-upload-pdf_'.$row->noption_id; ?>"><?php echo ($row->pdfurl!='0'?$pdflink:''); ?></td>

                        <td class="manage-column ss-list-width" id="<?php echo 'td-image_'.$row->noption_id; ?>" tdindex="<?php echo $j; ?>"><input type="file" accept=".png" name="<?php echo 'image_'.$row->noption_id; ?>" id="<?php echo 'image_'.$row->noption_id; ?>"></td>
                        <td id="<?php echo 'td-upload-image_'.$row->noption_id; ?>"><?php echo ($row->previewurl!='0'?$imagelink:''); ?></td>
                    </tr>
                <?php $j++; } ?>
                </table>
                <?php
                if(empty($university_pdfid)){
                    echo "<input type='submit' name='insert' value='Save' class='button'>";
                }
                else{
                    echo "<input type='submit' name='update' value='Save' class='button'>";
                }
                ?>
            </form>
        <?php } ?>
    </div>
    <?php
} ?>