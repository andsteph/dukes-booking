<?php

require_once(plugin_dir_path(__FILE__) . '../post-types/dbs_booking.php');

class DBS_Booking
{
    // ====================================================
    // static methods 
    // ====================================================

    // add meta boxes -------------------------------------
    static function add_meta_boxes()
    {
        add_meta_box('email', 'Email', ['DBS_Booking', 'add_meta_email'], 'dbs_booking', 'normal', 'high');
        add_meta_box('date', 'Date', ['DBS_Booking', 'add_meta_date'], 'dbs_booking', 'normal', 'high');
        add_meta_box('provider', 'Provder', ['DBS_Booking', 'add_meta_provider'], 'dbs_booking', 'normal', 'high');
        //add_meta_box('blocks', 'Blocks', ['DBS_Booking', 'add_meta_blocks'], 'dbs_booking', 'normal', 'high');
    }

    // meta box for customer email --------------------
    static function add_meta_email()
    {
        global $post;
        $email = $_GET['email'];
        echo "<input type='email' name='email' value='$email' readonly>";
    }

    // customer meta box for booking ------------------
    static function add_meta_customer_id()
    {
        global $post;
        $new_user_url = site_url() . '/wp-admin/user-new.php';
        echo "<p><a href='$new_user_url'>New Customer</a></p>";
        echo "<select name='customer_id' size='10' style='min-width: 300px'>";
        $user_id = get_post_meta($post->ID, 'customer_id', true);
        foreach (DBS_Customer::get_all() as $customer) {
            $selected = '';
            if ($user_id == $customer->ID) {
                $selected = 'selected';
            }
            $email = $customer->data->user_email;
            $name = $customer->data->display_name;
            echo "<option value='$customer->ID' $selected>$email - $name</option>";
        }
        echo "</select>";
    }

    // date meta box for booking ----------------------
    static function add_meta_date()
    {
        if (array_key_exists('date', $_GET)) {
            $current_date = $_GET['date'];
            $disabled = 'disabled';
            echo "<input type='hidden' name='date' value='$current_date'>";
        } else {
            global $post;
            $current_date = get_post_meta($post->ID, 'date', true);
            if ($current_date == '') {
                $current_date = date('Y-m-d');
            }
            $disabled = '';
        }
        echo "<input type='date' name='date' value='$current_date' $disabled>";
    }

    // provider meta box for booking ------------------
    static function add_meta_provider()
    {
        if (array_key_exists('provider_id', $_GET)) {
            $disabled = 'disabled';
            $current_provider_id = $_GET['provider_id'];
            echo "<input type='hidden' name='provider_id' value='$current_provider_id'>";
        } else {
            global $post;
            $current_provider_id = get_post_meta($post->ID, 'provider_id', true);
            $disabled = '';
        }
        echo "<select name='provider_id' $disabled>";
        foreach (DBS_Provider::get_all() as $provider) {
            $selected = '';
            if ($current_provider_id == $provider->ID) {
                $selected = 'selected';
            }
            echo "<option value='$provider->ID' $selected>$provider->name</option>";
        }
        echo '</select>';
    }

    // add meta box for blocks ----------------------------
    static function add_meta_blocks()
    {
        /*
        print_r($_GET['date']);
        print_r($_GET['provider_id']);
        print_r($_GET['cells']);
        */
    }

    // get bookings for date ------------------------------
    static function get_by_date($date)
    {
        $bookings = [];
        $meta_query = [
            ['key' => 'date', 'value' => $date, 'compare' => '='],
        ];
        $args = [
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'post_type' => 'dbs_booking',
            'meta_query' => $meta_query
        ];
        $query = new WP_Query($args);
        foreach ($query->posts as $post) {
            $booking = new DBS_Booking($post->ID);
            array_push($bookings, $booking);
        }
        return $bookings;
    }

    // get bookings for day and provider ------------------
    static function get_by_date_and_provider($date, $provider_id)
    {
        $bookings = [];
        $meta_query = [
            ['key' => 'date', 'value' => $date, 'compare' => '='],
            ['key' => 'provider_id', 'value' => $provider_id, 'compare' => '='],
        ];
        $args = [
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'post_type' => 'dbs_booking',
            'meta_query' => $meta_query
        ];
        $query = new WP_Query($args);
        foreach ($query->posts as $post) {
            $booking = new DBS_Booking($post->ID);
            array_push($bookings, $booking);
        }
        return $bookings;
    }

    // does a list of bookings match time? ----------------
    static function bookings_have_time($bookings, $time)
    {
        foreach ($bookings as $booking) {
            if ($booking->time == $time) {
                return $booking;
            }
        }
        return false;
    }

    // ajax function to save booking blocks ---------------
    /* I don't think we'll do it with ajax anymore */
    static function save()
    {
        $errors = [];
        // bad emails shouldn't make it to here, but just in case
        if (is_email($_POST['email'])) {
            // build a somewhat readable title
            $date = $_POST['date'];
            $email = $_POST['email'];
            $provider_id = $_POST['provider_id'];
            $errors = [];
            foreach ($_POST['times'] as $time) {
                $post_title = "$date $time $email $provider_id";
                $post_array = [
                    'post_title' => $post_title,
                    'post_status' => 'publish',
                    'post_type' => 'dbs_booking'
                ];
                $ID = wp_insert_post($post_array, true);
                if (gettype($ID) == 'integer') {
                    update_post_meta($ID, 'date', $date);
                    update_post_meta($ID, 'email', $email);
                    update_post_meta($ID, 'provider_id', $provider_id);
                    update_post_meta($ID, 'time', $time);
                    update_post_meta($ID, 'paid', "no");
                } else {
                    array_push($errors, $ID);
                }
            }
        } else {
            array_push($errors, 'not a valid email address');
        }
        echo json_encode($errors);
        wp_die();
    }

    // handle the booking form ----------------------------
    static function submit()
    {
        $errors = [];
        if (!is_email($_POST['email'])) {
            array_push($errors, 'The email is not valid.');
        }
        if (!array_key_exists('times', $_POST)) {
            array_push($errors, 'Please select at least one time slot.');
        }
        $params = [
            'page' => 'dukes-menu',
            'date' => $_POST['date']
        ];
        if (count($errors) > 0) {
            $params['errors'] = $errors;
        } else {
            // if admin - save booking right away 
            // if not admin, attempt payment - save booking if payment successful
            $date = $_POST['date'];
            $email = $_POST['email'];
            foreach ($_POST['times'] as $p => $provider) {
                foreach ($provider as $time) {
                    $post_title = "$date $p $time $email";
                    $post_array = [
                        'post_title' => $post_title,
                        'post_status' => 'publish',
                        'post_type' => 'dbs_booking'
                    ];
                    print_r($post_array);
                    $ID = wp_insert_post($post_array, true);
                    if (gettype($ID) == 'integer') {
                        update_post_meta($ID, 'date', $date);
                        update_post_meta($ID, 'email', $email);
                        update_post_meta($ID, 'provider_id', $p);
                        update_post_meta($ID, 'time', $time);
                        update_post_meta($ID, 'paid', "no");
                    } 
                }
            }
        }
        $url = admin_url('admin.php') . '?' . http_build_query($params);
        header("Location: $url");
        die();
    }

    // ====================================================
    // instance methods 
    // ====================================================

    // get from db and build object -----------------------
    function __construct($ID)
    {
        $post = get_post($ID);
        $this->ID = $ID;
        $this->post = $post;
        $this->email = get_post_meta($post->ID, 'email', true);
        $this->provider_id = get_post_meta($post->ID, 'provider_id', true);
        $this->people = get_post_meta($post->ID, 'people', true);
        $this->date = get_post_meta($post->ID, 'date', true);
        $this->time = get_post_meta($post->ID, 'time', true);
        $this->paid = get_post_meta($post->ID, 'paid', false);
        $this->content = $post->post_content;
    }

    // render (display) booking information ---------------
    function render()
    {
        $customer = get_user_by('ID', $this->customer_id);
        echo $this->time . '<br>';
        echo '<strong>' . $customer->data->user_email . '</strong><br>';
        echo 'people: ' . $this->people . '<br>';
        echo '<br>';
    }

    // short render of booking info -----------------------
    function short_render()
    {
        $customer = get_user_by('ID', $this->customer_id);
        $start = $this->time;
        $end = date('H:i', strtotime($this->time) + DBS_Global::$blocktime);
        echo $start . '-' . $end;
        //echo $customer->data->display_name . '<br>';
        echo '<br>';
    }
}

add_action('add_meta_boxes_dbs_booking', ['DBS_Booking', 'add_meta_boxes']);
