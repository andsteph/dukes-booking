<?php

/**
 * Plugin Name:	Duke's Booking System
 * Author: Nufas Media (Andrew Stephens)
 * Text Domain: dukes-booking
 * Domain Path: languages
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('write_log')) {

    // for debugging --------------------------------------
    function write_log($log)
    {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}

if (!class_exists('DukesBookingSystem')) {

    include plugin_dir_path(__FILE__) . 'post-types/dbs_booking.php';
    include plugin_dir_path(__FILE__) . 'classes/DBS_Booking.php';
    include plugin_dir_path(__FILE__) . 'post-types/dbs_provider.php';
    include plugin_dir_path(__FILE__) . 'classes/DBS_Provider.php';

    // ====================================================
    // main class for plugin
    // ====================================================

    class DukesBookingSystem
    {
        static $version = '0.1';
        static $timezone = 'America/Toronto';
        static $start_time;
        static $end_time;
        static $block_time;
        static $buffer_time;
        static $block_price;
        static $extra_block_discount;
        static $valid_times = [];

        // ================================================
        // admin pages
        // ================================================

        // home page for plugin ---------------------------
        static function home_page()
        {
            echo '<div class="wrap">';
            echo '<h1>Dukes Booking System</h1>';
            include plugin_dir_path(__FILE__) . 'includes/schedule.php';
            //include plugin_dir_path(__FILE__) . 'DBS_SQuare.php';
            echo '</div>';
        }

        // settings page ----------------------------------
        static function settings_page()
        {
            echo '<div class="wrap">';
            echo '<h1>Duke Booking Settings</h1>';
            $start_time = get_option('dbs_start_time');
            $end_time = get_option('dbs_end_time');
            $block_time = get_option('dbs_block_time');
            $buffer_time = get_option('dbs_buffer_time');
            $block_price = get_option('dbs_block_price');
            $extra_block_discount = get_option('dbs_extra_block_discount');
            include plugin_dir_path(__FILE__) . 'admin/settings.php';
            echo '</div>';
        }

        // submit/save settings ---------------------------
        static function settings_submit()
        {
            update_option('dbs_start_time', $_POST['start_time']);
            update_option('dbs_end_time', $_POST['end_time']);
            update_option('dbs_block_time', $_POST['block_time']);
            update_option('dbs_buffer_time', $_POST['buffer_time']);
            update_option('dbs_block_price', $_POST['block_price']);
            update_option('dbs_extra_block_discount', $_POST['extra_block_discount']);
            $url = admin_url('admin.php') . '/?page=dukes-settings';
            header("Location: $url");
        }

        // ================================================
        // frontend pages 
        // ================================================

        static function booking_page()
        {
            get_header();
            echo '<h1>Booking</h1>';
            include plugin_dir_path(__FILE__) . 'includes/schedule.php';
            get_footer();
        }

        // ================================================
        // our actions on wordpress hooks
        // ================================================

        // for activation hook ----------------------------
        static function activation()
        {
            global $dbs_version;
            add_option('dbs_version', $dbs_version);
            add_option('dbs_start_time', '10:00');
            add_option('dbs_end_time', '14:00');
            add_option('dbs_block_time', 30);
            add_option('dbs_buffer_time', 5);
            //DukesBookingSystem::rewrite_rules();
            //flush_rewrite_rules();
            if (!get_option('dbs_flush_rewrite_rules_flag')) {
                add_option('dbs_flush_rewrite_rules_flag', true);
            }
        }

        // enqueue scripts for admin ----------------------
        static function enqueue_scripts()
        {
            wp_enqueue_style('jquery-modal', plugins_url('node_modules/jquery-modal/jquery.modal.min.css', __FILE__));
            wp_enqueue_style('style', plugins_url('css/style.css', __FILE__));

            //wp_enqueue_script('jquery-modal', plugins_url('node_modules/jquery-modal/jquery.modal.min.js', __FILE__), array('jquery'));
            //wp_enqueue_script('dialogs', plugins_url('js/dialogs.js', __FILE__),array('jquery','jquery-modal'));
            wp_enqueue_script('functions', plugins_url('js/functions.js', __FILE__), array('jquery'));
            $translation_array = [
                'ajax_url' => admin_url('admin-ajax.php'),
                'block_price' => DukesBookingSystem::$block_price,
                'extra_block_discount' => DukesBookingSystem::$extra_block_discount,
                'admin_home' => admin_url('admin.php/?page=dukes-menu'),
                'booking_url' => site_url() . '/booking',
                'is_admin' => is_admin(),
                'date' => date('D M d Y H:i:s O')
            ];
            wp_localize_script('functions', 'php_vars', $translation_array);
        }

        // add pages to the wordpress admin menu ----------
        static function admin_menu()
        {
            add_menu_page('Dukes Booking System', 'Dukes Booking System', 'manage_options', 'dukes-menu', ['DukesBookingSystem', 'home_page']);
            add_submenu_page('dukes-menu', 'Bookings', 'Bookings', 'manage_options', 'edit.php?post_type=dbs_booking');
            add_submenu_page('dukes-menu', 'Providers', 'Providers', 'manage_options', 'edit.php?post_type=dbs_provider');
            add_submenu_page('dukes-menu', 'Settings', 'Settings', 'manage_options', 'dukes-settings', ['DukesBookingSystem', 'settings_page']);
        }

        // initialize plugin ------------------------------
        static function init()
        {
            if (get_option('myplugin_flush_rewrite_rules_flag')) {
                flush_rewrite_rules();
                delete_option('myplugin_flush_rewrite_rules_flag');
            }
        }

        // parse request for booking url ------------------
        static function parse_request(&$wp)
        {
            if (array_key_exists('dbs_booking_page', $wp->query_vars)) {
                DukesBookingSystem::booking_page();
                exit();
            }
            return;
        }

        // update query vars for booking page -------------
        static function query_vars($query_vars)
        {
            $query_vars[] = 'dbs_booking_page';
            $query_vars[] = 'date';
            return $query_vars;
        }

        // rewrite rule for booking page in frontend ------
        static function rewrite_rules()
        {
            add_rewrite_rule('^booking/([^/]*)/?', 'index.php?dbs_booking_page=1&date=$matches[1]', 'top');
        }
    }

    DukesBookingSystem::$start_time = strtotime(get_option('dbs_start_time'));
    DukesBookingSystem::$end_time = strtotime(get_option('dbs_end_time'));
    DukesBookingSystem::$block_time = get_option('dbs_block_time') * 60;
    DukesBookingSystem::$buffer_time = get_option('dbs_buffer_time') * 60;
    DukesBookingSystem::$block_price = get_option('dbs_block_price');
    DukesBookingSystem::$extra_block_discount = get_option('dbs_extra_block_discount') / 100;

    for ($i = DukesBookingSystem::$start_time; $i < DukesBookingSystem::$end_time; $i += DukesBookingSystem::$block_time + DukesBookingSystem::$buffer_time) {
        $time = date('H:i', $i);
        array_push(DukesBookingSystem::$valid_times, $time);
    }

    // on plugin activation -------------------------------
    register_activation_hook(__FILE__, ['DukesBookingSystem', 'activation']);

    // initialize plugin ----------------------------------
    add_action('init', ['DukesBookingSystem', 'init']);

    // set up admin menu ----------------------------------
    add_action('admin_menu', ['DukesBookingSystem', 'admin_menu']);

    // enqueue css and javascript -------------------------
    add_action('admin_enqueue_scripts', ['DukesBookingSystem', 'enqueue_scripts']);
    add_action('wp_enqueue_scripts', ['DukesBookingSystem', 'enqueue_scripts']);

    // add rewrite for booking page (front end) -----------
    add_action('init', ['DukesBookingSystem', 'rewrite_rules']);
    add_filter('query_vars', ['DukesBookingSystem', 'query_vars']);
    add_action('parse_request', ['DukesBookingSystem', 'parse_request']);

    //add_shortcode('dukes_booking_system', ['DukesBookingSystem', 'dbs_shortcode']);

    // ajax actions ---------------------------------------
    /*
    add_action('wp_ajax_save_booking', ['DBS_Booking', 'save']);
    add_action('wp_ajax_nopriv_save_booking', ['DBS_Booking', 'save']);
    */

    add_action('admin_post_booking_submit', ['DBS_Booking', 'submit']);
    add_action('admin_post_nopriv_booking_submit', ['DBS_Booking', 'submit']);

    add_action('admin_post_settings_submit', ['DukesBookingSystem', 'settings_submit']);
}
