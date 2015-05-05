<?php

/**
 * Plugin Name: WP SMS Notifier
 * Plugin URI: http://aaronjcksn.net
 * Description: This is a plugin will allow you to send sms notifications from your WordPress site
 * Version: 1.0
 * Author: Aaron Jackson
 * Author URI: http://aaronjcksn.net
 * License: A short license name. Example: GPL2
 */


if ( ! defined( 'ABSPATH') ) {
    exit; // Exit if accessed directly
}

if ( !class_exists('WP_SMS_Notifier') ) {

    class WP_SMS_Notifier {
        function __construct() {
            self::define_constants();
            self::load_hooks();
        }

        /*************************************************
         * Plugin Contstants
         **************************************************/

        public static function define_constants() {
            define('WP_SMS_Notifer_PATH', plugins_url( ' ', __FILE__) );
            define('WP_SMS_Notifer_BASENAME', plugin_basename( __FILE__ ) );
            define('WP_SMS_NOTIFIER_SETTING', 'wp_sms_notifier_plugin_setting' );
            define('WP_SMS_NOTIFIER_PHONE_NUMBER', 'wp_sms_notifier_plugin_phone_number' );
            define('WP_SMS_NOTIFIER_CARRIER', 'wp_sms_notifier_plugin_carrier' );
            define('WP_SMS_NOTIFIER_MESSAGE', 'wp_sms_notifier_plugin_message' );
        }

        public static function load_hooks() {

            add_action('admin_menu', array(__CLASS__, 'wp_sms_notifier_create_menu') );

            add_action('admin_init', array(__CLASS__, 'initialize_admin_posts') );
        }



        /*************************************************
         * Admin Area
         **************************************************/

        public static function wp_sms_notifier_create_menu() {
            add_menu_page("WP SMS Notifier", "WP SMS Notifier", 'administrator', __FILE__, array(__CLASS__, 'wp_sms_notifier_page'), '');
            add_action('admin_init', array(__CLASS__, 'register_wp_sms_notifier_settings'));
        }

        public static function wp_sms_notifier_page() {
            //self::wp_sms_notifier_admin_scripts();'
            self::fetch_wp_feed();

            ?>

            <h2>WP SMS Notifier</h2>
            <div class="container wp_sms_notifier_wrapper">
                <form method="post" action="<?php echo get_admin_url(); ?>admin-post.php">
                    <?php $wp_sms_notifier_phone_number = get_option(WP_SMS_NOTIFIER_PHONE_NUMBER); ?>
                    <?php $wp_sms_notifier_carrier = get_option(WP_SMS_NOTIFIER_CARRIER); ?>
                    <?php $wp_sms_notifier_message = get_option(WP_SMS_NOTIFIER_MESSAGE); ?>


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
                            <label for="<?php echo WP_SMS_NOTIFIER_MESSAGE; ?>" class"sms_message">Message</label>
                            <textarea name="<?php echo WP_SMS_NOTIFIER_MESSAGE; ?>" rows="8" cols="40" value="<?php echo $wp_sms_notifier_message; ?>"><?php echo $wp_sms_notifier_message; ?></textarea>
                        </li>

                        <li>
                            <?php submit_button('Save Settings', 'primary', 'save_wp_sms_notifier'); ?>
                            <input type="hidden" name="action" value="save_wp_sms_notifier">
                        </li>
                    </ul>
                </form>
            </div>
        <?php
        }

        public static function register_wp_sms_notifier_settings() {
            // Settings

        }

        // Admin Hooks
        public static function initialize_admin_posts() {
            add_action('admin_post_save_wp_sms_notifier', array(__CLASS__, 'save_wp_sms_notifier') ); // If the user is logged in
            add_action('admin_post_nopriv_save_wp_sms_notifier', array(__CLASS__, 'save_wp_sms_notifier') ); // If the user is not logged in
        }

        public static function save_wp_sms_notifier() {
            $wp_sms_notifier_phone_number = sanitize_text_field($_POST[WP_SMS_NOTIFIER_PHONE_NUMBER]);
            update_option(WP_SMS_NOTIFIER_PHONE_NUMBER, $wp_sms_notifier_phone_number);

            $wp_sms_notifier_carrier = esc_attr($_POST[WP_SMS_NOTIFIER_CARRIER]);
            update_option(WP_SMS_NOTIFIER_CARRIER, $wp_sms_notifier_carrier);

            $wp_sms_notifier_message = esc_textarea($_POST[WP_SMS_NOTIFIER_MESSAGE]);
            update_option(WP_SMS_NOTIFIER_MESSAGE, $wp_sms_notifier_message);

            $redirect_url = get_admin_url(). 'admin.php?page='.WP_SMS_Notifer_BASENAME;
            wp_redirect($redirect_url);
            exit;
        }

        // Carrier Settings
        public static function wp_sms_email() {
           return get_option(WP_SMS_NOTIFIER_CARRIER);
        }

        // Fetching WP Blog Feed
        public static function fetch_wp_feed() {
            $feed = file_get_contents('https://wordpress.org/news/feed/');
            $rss_feed = simplexml_load_string($feed);
            $json = json_encode($rss_feed);
            $array = json_decode($json, true);
            ?>

            
            <pre>
                <?php print_r($array); ?>
            </pre>
            <?php
        }

        // SMS Gateways
        public static function wp_sms_setgateway($sms_gateway) {
            switch ($sms_gateway) {
                case 'verizon':
                    return 'vzwpix.com';
                    break;

                case 'att':
                    return 'txt.att.net';
                    break;

                case 'sprint':
                    return 'messaging.sprintpcs.com';
                    break;

                case 't-mobile':
                    return 'tmomail.net';
                    break;
            }
        }



    }
    $class['WP_SMS_Notifier'] = new WP_SMS_Notifier();
    $class['WP_SMS_Notifier']::wp_sms_email();


}



