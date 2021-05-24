<?php

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
        add_meta_box('time', 'Time', ['DBS_Booking', 'add_meta_time'], 'dbs_booking', 'normal', 'high');
        add_meta_box('payment', 'Payment', ['DBS_Booking', 'add_meta_payment'], 'dbs_booking', 'normal', 'high');
    }

    // meta box for customer email --------------------
    static function add_meta_email()
    {
        global $post;
        $email = get_post_meta($post->ID, 'email', true);
        echo "<input type='email' name='email' value='$email'>";
    }

    // date meta box for booking ----------------------
    static function add_meta_date()
    {
        global $post;
        $date = get_post_meta($post->ID, 'date', true);
        echo "<input type='date' name='date' value='$date'>";
    }

    // provider meta box for booking ------------------
    static function add_meta_provider()
    {
        global $post;
        $current_provider_id = get_post_meta($post->ID, 'provider_id', true);
        echo "<select name='provider_id'>";
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
    static function add_meta_time()
    {
        global $post;
        $current_time = get_post_meta($post->ID, 'time', true);
        echo "<select name='time'>";
        foreach (DukesBookingSystem::$valid_times as $time) {
            $selected = '';
            if ($current_time == $time) {
                $selected = 'selected';
            }
            echo "<option value='$time' $selected>$time</option>";
        }
        echo '</select>';
    }

    // add meta payment -----------------------------------
    static function add_meta_payment()
    {
        global $post;
        $options = [
            'unpaid' => 'Unpaid',
            'creditcard' => 'Paid (credit card)',
            'debit' => 'Paid (debit)',
        ];
        $current_status = get_post_meta($post->ID, 'payment', true);
        echo "<select name='payment'>";
        foreach ($options as $key => $value) {
            echo "<option value='$key'>$value</option>";
        }
        echo '</select>';
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

    // create title from $_post variables -----------------
    static function create_title() {
        $date = $_POST['date'];
        $provider_id = $_POST['provider_id'];
        $time = $_POST['time'];
        $email = $_POST['email'];
        $post_title = "$date $provider_id $time $email";
        return $post_title;
    }

    // save/insert booking --------------------------------
    static function save()
    {
        $date = $_POST['date'];
        $email = $_POST['email'];
        foreach ($_POST['times'] as $p => $provider) {
            foreach ($provider as $time) {
                $post_array = [
                    'post_status' => 'publish',
                    'post_title' => "$date $email $p $time",
                    'post_type' => 'dbs_booking'
                ];
                define('DBS_SCHEDULE_SAVE', 1);
                $ID = wp_insert_post($post_array, true);
                update_post_meta($ID, 'date', $date);
                update_post_meta($ID, 'email', $email);
                update_post_meta($ID, 'provider_id', $p);
                update_post_meta($ID, 'time', $time);
            }
        }
    }

    // save post hook -------------------------------------
    static function save_post($post_id, $post, $update)
    {
        if ( !$update ) {
            return;
        }
        // we'll handle it manually if done from schedule
        if ( defined('DBS_SCHEDULE_SAVE') ) {
            return;
        }
        // don't do anything if we're deleting
        if ( $post->post_status == 'trash' ) {
            return;
        }
        // don't do anything if we're updating title 
        if ( defined('DBS_UPDATING_TITLE') ) {
            return;
        }
        $date = $_POST['date'];
        $email = $_POST['email'];
        $provider_id = $_POST['provider_id'];
        $time = $_POST['time'];
        $paid = $_POST['paid'];
        define('DBS_UPDATING_TITLE', 1);
        $post->post_title = "$date $email $provider_id $time";
        wp_update_post($post);
        update_post_meta($post_id, 'date', $date);
        update_post_meta($post_id, 'email', $email);
        update_post_meta($post_id, 'provider_id', $provider_id);
        update_post_meta($post_id, 'time', $time);
        update_post_meta($post_id, 'paid', $paid);
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
            if (current_user_can('administrator')) {
                DBS_Booking::save();
            } else {
            }
        }
        if ($_POST['origin'] == 'admin') {
            $url = admin_url('admin.php') . '?' . http_build_query($params);
        } else {
            $url = site_url() . '/booking/today' . '?' . http_build_query($params);
        }
        header("Location: $url");
    }

    // update meta info (for after saving) ----------------
    function update_meta()
    {
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
    
}

add_action('add_meta_boxes_dbs_booking', ['DBS_Booking', 'add_meta_boxes']);
add_action('admin_post_booking_submit', ['DBS_Booking', 'submit']);
add_action('admin_post_nopriv_booking_submit', ['DBS_Booking', 'submit']);
add_action('save_post_dbs_booking', ['DBS_Booking', 'save_post'], 10, 3);
