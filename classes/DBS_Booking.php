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
        //add_meta_box('blocks', 'Blocks', ['DBS_Booking', 'add_meta_blocks'], 'dbs_booking', 'normal', 'high');
    }

    // meta box for customer email --------------------
    static function add_meta_email()
    {
        global $post;
        $email = get_post_meta( $post->ID, 'email', true );
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
        $current_time = get_post_meta( $post->ID, 'time', true );
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

    // save/insert booking --------------------------------
    static function save()
    {
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
            if ( current_user_can('administrator') ) {
                DBS_Booking::save();
            } else {

            }
        }
        if ( $_POST['origin'] == 'admin' ) {
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
