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
            define('WP_SMS_Notifier_PLUGIN_SETTING', 'wp_sms_notifier_plugin_setting' );
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
            //self::wp_sms_notifier_admin_scripts();

            ?>

            <h2>WP SMS Notifier</h2>
            <div class="container wp_sms_notifier_wrapper">
                <form action="<?php echo get_admin_url(); ?>admin-post.php">
                    <?php $wp_sms_notifier_setting = get_option(WP_SMS_NOTIFIER_SETTING); ?>
                    <ul>
                        <li>
                            <label for="<?php echo WP_SMS_NOTIFIER_SETTING; ?>" class="phone_number">Phone Number</label>
                            <input type="text" name="<?php echo WP_SMS_NOTIFIER_SETTING; ?>" <?php if ($wp_sms_notifier_setting) { echo 'value="' .$wp_sms_notifier_setting. '"'; } ?> />
                        </li>

                        <li>
                            <label for="<?php echo WP_SMS_NOTIFIER_SETTING; ?>" class="wireless_carrier">Wireless Carrier</label>
                            <select name="<?php echo WP_SMS_NOTIFIER_SETTING; ?>" id="wireless-carrier">
                                <option <?php if ($wp_sms_notifier_setting) { echo 'value="' .$wp_sms_notifier_setting. '"'; } ?>>Verizon</option>
                                <option <?php if ($wp_sms_notifier_setting) { echo 'value="' .$wp_sms_notifier_setting. '"'; } ?>>AT&T</option>
                                <option <?php if ($wp_sms_notifier_setting) { echo 'value="' .$wp_sms_notifier_setting. '"'; } ?>>Sprint</option>
                                <option <?php if ($wp_sms_notifier_setting) { echo 'value="' .$wp_sms_notifier_setting. '"'; } ?>>T-Mobile</option>
                            </select>
                        </li>

                        <li>
                            <label for="<?php echo WP_SMS_NOTIFIER_SETTING; ?>" class"sms_message">Message</label>
                            <textarea name="sms_message" rows="8" cols="40"></textarea>
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

        public static function save_wp_sms_notifer() {
            $wp_sms_notifier_setting = sanitize_text_field($_POST[WP_SMS_NOTIFIER_SETTING]);
            update_option(WP_SMS_NOTIFIER_SETTING, $wp_sms_notifier_setting);

            $redirect_url = get_admin_url(). 'admin.php?page='.WP_SMS_NOTIFIER_BASENAME;
            wp_redirect($redirect_url);
            exit;
        }
    }
    $class['WP_SMS_Notifier'] = new WP_SMS_Notifier();
}
