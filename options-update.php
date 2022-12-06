<?php
function rnb_option_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "rnb_options";
    $id = $_GET["id"];
    $name = $_POST["optiontitle"];
    if (isset($_POST['update'])) {
        $wpdb->update(
                $table_name,
                array('voption_title' => $name, 'noption_order' => 1, 'voption_type' => $_POST["optiontype"]),
                array('noption_id' => $id),
                array('%s', '%s', '%s'),
                array('%s')
        );
    }
    else if (isset($_POST['delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE noption_id = %s", $id));
    } else {
        $option = $wpdb->get_results($wpdb->prepare("SELECT noption_id, voption_title, noption_order, voption_type from $table_name where noption_id=%s", $id));
        foreach ($option as $s) {
            $name = $s->voption_title;
			$orderid = $s->noption_order;
            $belongto = $s->voption_type;
        }
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/rnb-pdf-download/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Options</h2>
        <?php if ($_POST['delete']) { ?>
            <div class="updated"><p>option deleted</p></div>
            <a href="<?php echo admin_url('admin.php?page=rnb_option_list') ?>">&laquo; Back to option list</a>
        <?php } else if ($_POST['update']) { ?>
            <div class="updated"><p>option updated</p></div>
            <a href="<?php echo admin_url('admin.php?page=rnb_option_list') ?>">&laquo; Back to option list</a>
        <?php } else { ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <table class='wp-list-table widefat fixed'>
                    <tr>
                        <th class="ss-th-width">Option Name</th>
                        <td><input type="text" name="optiontitle" value="<?php echo $name; ?>" class="ss-field-width" placeholder="eg. Jiwaji option, Gwalior, MP"></td>
                    </tr>
                    <tr>
                    <th class="ss-th-width">Option Belongs To</th>
                    <td>
                        <select name="optiontype" class="ss-field-width">
                            <option value="university" <?php echo ($belongto=='university'?'selected':''); ?>>University</option>
                            <option value="course" <?php echo ($belongto=='course'?'selected':''); ?>>Course</option>
                        </select>
                    </td>
                </tr>
                </table>
                <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Are you sure?')">
            </form>
        <?php } ?>
    </div>
    <?php
} ?>