<?php

class DBS_Booking
{
    // ====================================================
    // static methods 
    // ====================================================

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

    // save/insert bookings -------------------------------
    static function save($payment='unpaid')
    {
        $date = $_POST['date'];
        $email = $_POST['email'];
        foreach ($_POST['times'] as $p => $provider) {
            foreach ($provider as $time) {
                $post_array = [
                    'post_name' => "$date_$email_$p_$time",
                    'post_status' => 'publish',
                    'post_title' => "$date $email $p $time",
                    'post_type' => 'dbs_booking'
                ];
                $ID = wp_insert_post($post_array, true);
                update_post_meta($ID, 'date', $date);
                update_post_meta($ID, 'email', $email);
                update_post_meta($ID, 'provider_id', $p);
                update_post_meta($ID, 'time', $time);
                update_post_meta($ID, 'payment', $payment);
            }
        }
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
            include plugin_dir_path(__FILE__) . '../includes/credit-card.php';
            die();
            if (is_admin() && current_user_can('administrator')) {
                DBS_Booking::save();
            } else {
                // get payment first 
                // DBS_Booking::save()

            }
        }
        if ($_POST['origin'] == 'admin') {
            $url = admin_url('admin.php') . '?' . http_build_query($params);
        } else {
            $url = site_url() . '/booking/today' . '?' . http_build_query($params);
        }
        header("Location: $url");
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
        $this->payment = get_post_meta($post->ID, 'payment', true);
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

add_action('admin_post_booking_submit', ['DBS_Booking', 'submit']);
add_action('admin_post_nopriv_booking_submit', ['DBS_Booking', 'submit']);
