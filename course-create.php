<?php
function rnb_course_create() {
    if (isset($_POST['coursetitle'])) {
        $course = $_POST["coursetitle"];
        global $wpdb;
        $table_name = $wpdb->prefix . "rnb_courses";
		if(!empty($course))
		{
			$res = $wpdb->insert(
					$table_name,
					array('vcourse_title' => $course, 'ncourse_order' => 1),
					array('%s', '%s')
			);
			$message.= $res." Course Created";
		}
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/rnb-pdf-download/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Add New Course</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <table class='wp-list-table widefat fixed'>
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