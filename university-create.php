<?php
function rnb_university_create() {
    if (isset($_POST['universitytitle'])) {
        $university = $_POST["universitytitle"];
        global $wpdb;
        $table_name = $wpdb->prefix . "rnb_universities";
		if(!empty($university))
		{
        	$res = str_replace( array( '\'', '"', ',' , ';', '<', '>', '(', ')', '.', '|', '/', ':', '\\' ), '', $university);
			$res = strtolower($res);
			$vslug = str_replace( ' ', '-', $res);
			$res = $wpdb->insert(
					$table_name,
					array('vuniversity_title' => $university, 'nuniversity_order' => 1, 'vslug' => $vslug),
					array('%s', '%s', '%s')
			);
			$message.= $res." University Created";
		}
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/rnb-pdf-download/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Add New University</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <table class='wp-list-table widefat fixed'>
				<tr>
                    <th class="ss-th-width">University Name</th>
                    <td>
						<input type="text" name="universitytitle" class="ss-field-width" placeholder="eg. Jiwaji University, Gwalior, MP">
					</td>
                </tr>
            </table>
            <input type="submit" name="insert" value="Save" class="button">
        </form>
    </div>
    <?php
}?>