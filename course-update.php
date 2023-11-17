<?php
function rnb_course_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "rnb_courses";
    $table_name4 = $wpdb->prefix . "rnb_category";
    $id = $_GET["id"];
    $name = $_POST["coursetitle"];
    $stream = $_POST["selstream"];
    if (isset($_POST['update'])) {
    	$res = str_replace( array( '\'', '"', ',' , ';', '<', '>', '(', ')', '.', '|', '/', ':', '\\' ), '', $name);
        $res = strtolower($res);
        $vslug = str_replace( ' ', '-', $res);
        $wpdb->update(
                $table_name,
                array('vcourse_title' => $name, 'ncourse_order' => 1, 'ncategory_id' => $stream, 'vslug' => $vslug),
                array('ncourse_id' => $id),
                array('%s', '%s', '%s', '%s'),
                array('%s')
        );
    }
    else if (isset($_POST['delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE ncourse_id = %s", $id));
    } else {
        $course = $wpdb->get_results($wpdb->prepare("SELECT ncourse_id, vcourse_title, ncourse_order, ncategory_id from $table_name where ncourse_id=%s", $id));
        foreach ($course as $s) {
            $name = $s->vcourse_title;
			$orderid = $s->ncourse_order;
			$nstream = $s->ncategory_id;
        }
    }
   
    $categoryrows  = $wpdb->get_results("SELECT ncategory_id, vcategory_title FROM $table_name4 ORDER BY vcategory_title ASC", ARRAY_A);
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/rnb-pdf-download/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Courses</h2>
        <?php if ($_POST['delete']) { ?>
            <div class="updated"><p>Course deleted</p></div>
        	<br><br>
            <a class="page-title-action" href="<?php echo admin_url('admin.php?page=rnb_course_list') ?>">&laquo; Back to course list</a>
        	<br><br>
        <?php } else if ($_POST['update']) { ?>
            <div class="updated"><p>Course updated</p></div>
        	<br><br>
            <a class="page-title-action" href="<?php echo admin_url('admin.php?page=rnb_course_list') ?>">&laquo; Back to course list</a>
        	<br><br>
        <?php } else { ?>
        	<br><br>
        	<a class="page-title-action" href="<?php echo admin_url('admin.php?page=rnb_course_list') ?>">&laquo; Back to course list</a>
        	<br><br>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <table class='wp-list-table widefat fixed'>
                    <tr>
                        <th class="ss-th-width">Stream</th>
                        <td>
                            <select name="selstream" class="ss-field-width">
                                <?php
                                foreach($categoryrows as $catkey => $catvalue){ 
                                    
                                    $sel = ($catvalue['ncategory_id']==$nstream?'selected':'');
                                    echo "<option value='".$catvalue['ncategory_id']."' ".$sel.">".$catvalue['vcategory_title']."</option>";
                                }
                                ?>
                            </select>
    					</td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Course Name</th>
                        <td><input type="text" name="coursetitle" value="<?php echo $name; ?>" class="ss-field-width" placeholder="eg. Jiwaji course, Gwalior, MP"></td>
                    </tr>
                </table>
                                <br>
                <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Are you sure?')">
            </form>
        <?php } ?>
    </div>
    <?php
} ?>