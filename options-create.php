<?php
function rnb_option_create() {
    if (isset($_POST['optiontitle'])) {
        $option = $_POST["optiontitle"];
        global $wpdb;
        $table_name = $wpdb->prefix . "rnb_options";
		if(!empty($option))
		{
			$res = $wpdb->insert(
					$table_name,
					array('voption_title' => $option, 'noption_order' => 1, 'voption_type' => $_POST["optiontype"]),
					array('%s', '%s', '%s')
			);
			$message.= $res." Option Created";
		}
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/rnb-pdf-download/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Add New Option</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <table class='wp-list-table widefat fixed'>
				<tr>
                    <th class="ss-th-width">Option Name</th>
                    <td>
						<input type="text" name="optiontitle" class="ss-field-width" placeholder="eg. Jiwaji option, Gwalior, MP">
					</td>
                </tr>
                <tr>
                    <th class="ss-th-width">Option Belongs To</th>
                    <td>
                        <select name="optiontype" class="ss-field-width">
                            <option value="university">University</option>
                            <option value="course">Course</option>
                        </select>
                    </td>
                </tr>
            </table>
        	<br>
            <input type="submit" name="insert" value="Save" class="button">
        </form>
    </div>
    <?php
}?>