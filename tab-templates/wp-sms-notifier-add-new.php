<?php
if ( ! defined( 'ABSPATH') ) {
    exit; // Exit if accessed directly
}
?>

<div class="container wp_sms_notifier_wrapper">
    <form method="post" action="<?php //echo get_admin_url(); ?>admin-post.php">
        <?php
        $wp_sms_notifier_phone_number = get_option(WP_SMS_NOTIFIER_PHONE_NUMBER);
        $wp_sms_notifier_carrier = get_option(WP_SMS_NOTIFIER_CARRIER);
        ?>
        <ul>
            <li>
                <label for="<?php echo WP_SMS_NOTIFIER_PHONE_NUMBER; ?>" class="phone_number">Phone Number</label>
                <input type="text" name="<?php echo WP_SMS_NOTIFIER_PHONE_NUMBER; ?>" value="<?php echo $wp_sms_notifier_phone_number; ?>" />
            </li>
            <li>
                <label for="<?php echo WP_SMS_NOTIFIER_CARRIER; ?>" class="wireless_carrier">Wireless Carrier</label>
                <select name="<?php echo WP_SMS_NOTIFIER_CARRIER; ?>" id="wireless-carrier">
                    <option value="verizon" <?php if ($wp_sms_notifier_carrier == 'verizon') { echo 'selected="selected"'; } ?>>Verizon</option>
                    <option value="att" <?php if ($wp_sms_notifier_carrier == 'att') { echo 'selected="selected"'; } ?>>AT&T</option>
                    <option value="sprint" <?php if ($wp_sms_notifier_carrier == 'sprint') { echo 'selected="selected"'; } ?>>Sprint</option>
                    <option value="t-mobile" <?php if ($wp_sms_notifier_carrier == 't-mobile') { echo 'selected="selected"'; } ?>>T-Mobile</option>
                </select>
            </li>
            <li>
                <?php submit_button('Save Settings', 'primary', 'save_wp_sms_notifier'); ?>
                <input type="hidden" name="action" value="save_wp_sms_notifier">
            </li>
        </ul>
    </form>
</div>