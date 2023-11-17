<?php
function rnb_visitor_list() {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/rnb-pdf-download/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Visitors</h2><button type="button" onclick="tableToCSV()">Download</button>
        <br class="clear">
        <?php
		global $wpdb;
        $table_name = $wpdb->prefix . "rnb_visitor_data";
        $rows = $wpdb->get_results("SELECT nvid, vname,	vphone,	vemail,	vurl, jdata, dvisit_date from $table_name order by nvid desc");
        ?>
        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
				<th class="manage-column ss-list-width" colspan="5" width="70%"></th>
				<th class="manage-column ss-list-width" width="30%"></th>
            </tr>
            <tr>
				<th class="manage-column ss-list-width" width="5%" style="font-weight:bold;">#</th>
				<th class="manage-column ss-list-width" width="5%" style="font-weight:bold;">Date</th>
                <th class="manage-column ss-list-width" width="20%" style="font-weight:bold;">Name</th>
                <th class="manage-column ss-list-width" width="20%" style="font-weight:bold;">Phone</th>
                <th class="manage-column ss-list-width" width="20%" style="font-weight:bold;">Email</th>
                <th class="manage-column ss-list-width" width="30%" style="font-weight:bold;">Services Looked For</th>
            </tr>
            <?php $i=1; foreach ($rows as $row) { 
                $url_exploded = explode("/",urldecode($row->vurl));
            ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $i++; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->dvisit_date; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->vname; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->vphone; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->vemail; ?></td>
                    <td class="manage-column ss-list-width"><?php echo substr($url_exploded[count($url_exploded)-1],0,-4); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
} ?>