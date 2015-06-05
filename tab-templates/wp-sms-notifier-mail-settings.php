<?php
    if( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }
?>
<form method="post" action="<?php echo get_admin_url(); ?>admin-post.php" class="settings-form">
    <?php
    $wp_sms_notifier_settings_email = get_option(WP_SMS_NOTIFIER_SETTINGS_EMAIL);
    $wp_sms_notifier_settings_name = get_option(WP_SMS_NOTIFIER_SETTINGS_NAME);
    $wp_sms_notifier_settings_smtp_host = get_option(WP_SMS_NOTIFIER_SETTINGS_SMPT_HOST);
    $wp_sms_notifier_settings_encryption = get_option(WP_SMS_NOTIFIER_SETTINGS_ENCRYPTION);
    $wp_sms_notifier_settings_smpt_port = get_option(WP_SMS_NOTIFIER_SETTINGS_SMPT_PORT);
    $wp_sms_notifier_settings_auth = get_option(WP_SMS_NOTIFIER_SETTINGS_AUTH);
    $wp_sms_notifier_settings_username = get_option(WP_SMS_NOTIFIER_SETTINGS_SMTP_USERNAME);
    $wp_sms_notifier_settings_password = get_option(WP_SMS_NOTIFIER_SETTINGS_SMTP_PASSWORD);
    ?>
    <ul class="settings_form">
        <li>
            <label for="<?php echo WP_SMS_NOTIFIER_SETTINGS_EMAIL; ?>">From Email</label>
            <input type="text" name="<?php echo WP_SMS_NOTIFIER_SETTINGS_EMAIL; ?>" value="<?php echo $wp_sms_notifier_settings_email; ?>" />
        </li>
        <li>
            <label for="<?php echo WP_SMS_NOTIFIER_SETTINGS_NAME; ?>">From Name</label>
            <input type="text" name="<?php echo WP_SMS_NOTIFIER_SETTINGS_NAME; ?>" value="<?php echo $wp_sms_notifier_settings_name; ?>" />
        </li>
        <li>
            <label for="<?php echo WP_SMS_NOTIFIER_SETTINGS_SMPT_HOST; ?>">SMTP Host</label>
            <input type="text" name="<?php echo WP_SMS_NOTIFIER_SETTINGS_SMPT_HOST; ?>" value="<?php $wp_sms_notifier_settings_smtp_host; ?>" />
        </li>
        <li>
            <label for="<?php echo WP_SMS_NOTIFIER_SETTINGS_ENCRYPTION; ?> ">Encryption Type</label>
            <input type="radio" name="<?php if($wp_sms_notifier_settings_encryption == 'none') : echo 'selected="selected"'; ?>" value="none" /> None
            <input type="radio" name="<?php if($wp_sms_notifier_settings_encryption == 'ssl') : echo 'selected="selected"'; ?>" value="ssl"/> SSL
            <input type="radio" name="<?php if($wp_sms_notifier_settings_encryption == 'tls') : echo 'selected="selected"'; ?>" value="tls"/> TLS
        </li>
        <li>
            <label for="<?php echo WP_SMS_NOTIFIER_SETTINGS_SMPT_PORT; ?>">SMTP Port</label>
            <input type="text" name="<?php echo WP_SMS_NOTIFIER_SETTINGS_SMPT_PORT; ?>" value="<?php $wp_sms_notifier_settings_smtp_host; ?>" />
        </li>
        <li>
            <label for="<?php echo WP_SMS_NOTIFIER_SETTINGS_AUTH; ?> ">SMTP Authentication</label>
            <input type="radio" name="<?php if($wp_sms_notifier_settings_auth == 'no') : echo 'selected="selected"'; ?>" value="no" /> NO
            <input type="radio" name="<?php if($wp_sms_notifier_settings_auth == 'yes') : echo 'selected="selected"'; ?>" value="yes"/> YES
        </li>
        <li>
            <label for="<?php echo WP_SMS_NOTIFIER_SETTINGS_SMTP_USERNAME; ?>">SMTP Username</label>
            <input type="text" name="settings_smtp_username" />
        </li>
        <li>
            <label for="<?php echo WP_SMS_NOTIFIER_SETTINGS_SMTP_PASSWORD; ?>">SMTP Password</label>
            <input type="password" name="<?php echo WP_SMS_NOTIFIER_SETTINGS_SMTP_PASSWORD; ?>" value="<?php echo $wp_sms_notifier_settings_password; ?>"/>
        </li>
        <li>
            <?php submit_button('Save Updates', 'primary', 'save_wp_sms_notifier_hosts_settings'); ?>
            <input type="hidden" name="wp_sms_submit" value="submit"/>
        </li>
    </ul>
</form>



