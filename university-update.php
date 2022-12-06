<?php
function rnb_university_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "rnb_universities";
    $id = $_GET["id"];
    $name = $_POST["universitytitle"];
    if (isset($_POST['update'])) {
        $wpdb->update(
                $table_name,
                array('vuniversity_title' => $name, 'nuniversity_order' => 1),
                array('nuniversity_id' => $id),
                array('%s', '%s'),
                array('%s')
        );
    }
    else if (isset($_POST['delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE nuniversity_id = %s", $id));
    } else {
        $university = $wpdb->get_results($wpdb->prepare("SELECT nuniversity_id, vuniversity_title, nuniversity_order from $table_name where nuniversity_id=%s", $id));
        foreach ($university as $s) {
            $name = $s->vuniversity_title;
			$orderid = $s->nuniversity_order;
        }
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/rnb-pdf-download/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Universities</h2>
        <?php if ($_POST['delete']) { ?>
            <div class="updated"><p>University deleted</p></div>
            <a href="<?php echo admin_url('admin.php?page=rnb_university_list') ?>">&laquo; Back to university list</a>
        <?php } else if ($_POST['update']) { ?>
            <div class="updated"><p>University updated</p></div>
            <a href="<?php echo admin_url('admin.php?page=rnb_university_list') ?>">&laquo; Back to university list</a>
        <?php } else { ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <table class='wp-list-table widefat fixed'>
                    <tr>
                        <th class="ss-th-width">University Name</th>
                        <td><input type="text" name="universitytitle" value="<?php echo $name; ?>" class="ss-field-width" placeholder="eg. Jiwaji University, Gwalior, MP"></td>
                    </tr>
                </table>
                <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Are you sure?')">
            </form>
        <?php } ?>
    </div>
    <?php
} ?>