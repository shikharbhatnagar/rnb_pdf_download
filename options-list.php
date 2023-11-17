<?php
function rnb_option_list() {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/rnb-pdf-download/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Options</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <br><br>
            	<a class="page-title-action" href="<?php echo admin_url('admin.php?page=rnb_option_create'); ?>">Add New</a>
                <br><br>
            </div>
            <br class="clear">
        </div>
        <?php
		global $wpdb;
        $table_name = $wpdb->prefix . "rnb_options";
		if (isset($_POST['delete'])) { ?>
			<div class="updated"><p>Option deleted</p></div>
			<?php
			$allchecks = $_POST["selAll"];
			for($c=0;$c<count($allchecks);$c++)
			{
				$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE noption_id = %s", $allchecks[$c]));
			}
		}
        $rows = $wpdb->get_results("SELECT noption_id, voption_title, noption_order, voption_type from $table_name order by noption_order");
        ?>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
				<th class="manage-column ss-list-width" width="5%"><input type="checkbox" name="chkAll" id="chkAll" class="ss-field-width" onClick="toggle(this)"></th>
                <th class="manage-column ss-list-width" width="5%">ID</th>
                <th class="manage-column ss-list-width" width="70%">Option Title</th>
                <th class="manage-column ss-list-width" width="20%">Option Type</th>
                <th width="20%">&nbsp;</th>
            </tr>
            <?php $i=1; foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><input type="checkbox" name="selAll[]" value="<?php echo $row->noption_id; ?>"></td>
                    <td class="manage-column ss-list-width"><?php echo $i++; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->voption_title; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->voption_type; ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=rnb_option_update&id=' . $row->noption_id); ?>">Update</a></td>
                </tr>
            <?php } ?>
        </table>
		<!--<input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Are you sure?')">-->
		</form>
    </div>
    <?php
} ?>