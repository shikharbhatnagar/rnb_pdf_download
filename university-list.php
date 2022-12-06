<?php
function rnb_university_list() {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/rnb-pdf-download/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Universities</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=rnb_university_create'); ?>">Add New</a>
            </div>
            <br class="clear">
        </div>
        <?php
		global $wpdb;
        $table_name = $wpdb->prefix . "rnb_universities";
		if (isset($_POST['delete'])) { ?>
			<div class="updated"><p>University deleted</p></div>
			<?php
			$allchecks = $_POST["selAll"];
			for($c=0;$c<count($allchecks);$c++)
			{
				$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE nuniversity_id = %s", $allchecks[$c]));
			}
		}
        $rows = $wpdb->get_results("SELECT nuniversity_id, vuniversity_title, nuniversity_order from $table_name order by nuniversity_order");
        ?>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
				<th class="manage-column ss-list-width" width="5%"><input type="checkbox" name="chkAll" id="chkAll" class="ss-field-width" onClick="toggle(this)"></th>
                <th class="manage-column ss-list-width" width="5%">ID</th>
                <th class="manage-column ss-list-width" width="70%">University Title</th>
                <th width="25%">&nbsp;</th>
            </tr>
            <?php $i=1; foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><input type="checkbox" name="selAll[]" value="<?php echo $row->nuniversity_id; ?>"></td>
                    <td class="manage-column ss-list-width"><?php echo $i++; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->vuniversity_title; ?> (<a href="<?php echo admin_url('admin.php?page=rnb_university_upload&id=' . $row->nuniversity_id); ?>">Manage Uploads</a>)</td>
                    <td><a href="<?php echo admin_url('admin.php?page=rnb_university_update&id=' . $row->nuniversity_id); ?>">Update</a></td>
                </tr>
            <?php } ?>
        </table>
		<input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Are you sure?')">
		</form>
    </div>
    <?php
} ?>