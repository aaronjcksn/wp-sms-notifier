<?php

/**
 * Plugin Name: WP SMS Notifier
 * Plugin URI: http://aaronjcksn.net
 * Description: This is a plugin will allow you to send sms notifications from your WordPress site
 * Version: 1.3
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

        public static function wp_sms_notifier_admin_tabs( $current = 'add_new' ) {
            $tabs = array('add_new' => 'Add New Number', 'wp_sms_number' => 'Phone Number', 'pages' => 'Message' );
            echo '<h2 style="font-size: 22px; font-weight: bold; margin: 10px 0 40px;">WP SMS Notifier</h2>';
            echo '<h2 class="nav-tab-wrapper">';
            foreach($tabs as $tab => $name) {
                $class = ($tab == $current) ? 'nav-tab-active' : '';
                echo "<a class='nav-tab $class' href='?page=".WP_SMS_Notifer_BASENAME."&tab=$tab'>$name</a>";
            }
            echo '</h2>';
        }

        public static function wp_sms_notifier_page() {
            //self::wp_sms_notifier_admin_scripts();'
            self::fetch_wp_feed();

            global $pagenow;

            if(isset($_GET['tab'])) {
                self::wp_sms_notifier_admin_tabs($_GET['tab']);
                $pagenow = $_GET['tab'];
            } else {
                self::wp_sms_notifier_admin_tabs('add_new');
                $pagenow = 'add_new';
            }

            ?>

            <h2>WP SMS Notifier</h2>
            <div class="container wp_sms_notifier_wrapper">
                <form method="post" action="<?php echo get_admin_url(); ?>admin-post.php">
                    <?php $wp_sms_notifier_phone_number = get_option(WP_SMS_NOTIFIER_PHONE_NUMBER); ?>
                    <?php $wp_sms_notifier_carrier = get_option(WP_SMS_NOTIFIER_CARRIER); ?>

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
            $feed_url = 'https://wordpress.org/news/feed/';
            $content = file_get_contents($feed_url);
            $x = new SimpleXMLElement($content);
            $i = 0;

            echo "<ul>";
            foreach($x->channel->item as $entry) {
                if($i == 3) break;
                echo "<li><a href='$entry->link' title='$entry->title'>$title</a>
                        $entry->description
                    </li>";
                $i++;

            }
            echo "</ul>";
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



