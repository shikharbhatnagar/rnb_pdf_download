<?php
function rnb_course_create() {
    global $wpdb;
    if (isset($_POST['coursetitle'])) {
        $course = $_POST["coursetitle"];
        $stream = $_POST["selstream"];
        
        $table_name = $wpdb->prefix . "rnb_courses";
		if(!empty($course))
		{	
        	$res = str_replace( array( '\'', '"', ',' , ';', '<', '>', '(', ')', '.', '|', '/', ':', '\\' ), '', $course);
			$res = strtolower($res);
			$vslug = str_replace( ' ', '-', $res);
			$res = $wpdb->insert(
					$table_name,
					array('vcourse_title' => $course, 'ncourse_order' => 1, 'ncategory_id' => $stream, 'vslug' => $vslug),
					array('%s', '%s', '%s', '%s')
			);
			$message.= $res." Course Created";
		}
    }
    
    $table_name4 = $wpdb->prefix . "rnb_category";

    $categoryrows  = $wpdb->get_results("SELECT ncategory_id, vcategory_title FROM $table_name4 ORDER BY vcategory_title ASC", ARRAY_A);
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/rnb-pdf-download/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Add New Course</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <table class='wp-list-table widefat fixed'>
				<tr>
                    <th class="ss-th-width">Stream</th>
                    <td>
                        <select name="selstream" class="ss-field-width">
                            <?php
                            foreach($categoryrows as $catkey => $catvalue){ 
                                echo "<option value='".$catvalue['ncategory_id']."'>".$catvalue['vcategory_title']."</option>";
                            }
                            ?>
                        </select>
					</td>
                </tr>
                <tr>
                    <th class="ss-th-width">Course Name</th>
                    <td>
						<input type="text" name="coursetitle" class="ss-field-width" placeholder="eg. BCA or MCA">
					</td>
                </tr>
            </table>
            <input type="submit" name="insert" value="Save" class="button">
        </form>
    </div>
    <?php
}?>