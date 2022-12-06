<?php
function rnb_course_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "rnb_courses";
    $id = $_GET["id"];
    $name = $_POST["coursetitle"];
    if (isset($_POST['update'])) {
        $wpdb->update(
                $table_name,
                array('vcourse_title' => $name, 'ncourse_order' => 1),
                array('ncourse_id' => $id),
                array('%s', '%s'),
                array('%s')
        );
    }
    else if (isset($_POST['delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE ncourse_id = %s", $id));
    } else {
        $course = $wpdb->get_results($wpdb->prepare("SELECT ncourse_id, vcourse_title, ncourse_order from $table_name where ncourse_id=%s", $id));
        foreach ($course as $s) {
            $name = $s->vcourse_title;
			$orderid = $s->ncourse_order;
        }
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/rnb-pdf-download/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Courses</h2>
        <?php if ($_POST['delete']) { ?>
            <div class="updated"><p>Course deleted</p></div>
            <a href="<?php echo admin_url('admin.php?page=rnb_course_list') ?>">&laquo; Back to course list</a>
        <?php } else if ($_POST['update']) { ?>
            <div class="updated"><p>Course updated</p></div>
            <a href="<?php echo admin_url('admin.php?page=rnb_course_list') ?>">&laquo; Back to course list</a>
        <?php } else { ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <table class='wp-list-table widefat fixed'>
                    <tr>
                        <th class="ss-th-width">Course Name</th>
                        <td><input type="text" name="coursetitle" value="<?php echo $name; ?>" class="ss-field-width" placeholder="eg. Jiwaji course, Gwalior, MP"></td>
                    </tr>
                </table>
                <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Are you sure?')">
            </form>
        <?php } ?>
    </div>
    <?php
} ?>