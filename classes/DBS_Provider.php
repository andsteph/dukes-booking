<?php

class DBS_Provider
{
    // ====================================================
    // static methods 
    // ====================================================
    
    static function get_all()
    {
        $args = [
            'orderby' => 'title',
            'order' => 'asc',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post_type' => 'dbs_provider',
        ];
        $query = new WP_Query($args);
        $providers = [];
        foreach ( $query->posts as $post ) {
            $provider = new DBS_Provider($post->ID);
            array_push($providers, $provider);
        }
        return $providers;
    }

    // ====================================================
    // instance methods 
    // ====================================================

    // constructor ----------------------------------------
    function __construct($ID)
    {
        $post = get_post($ID);
        $this->ID = $post->ID;
        $this->post = $post;
        $this->name = $post->post_title;
        $this->description = $post->post_content;
    }

    // generate entries for the day -----------------------
    function generate_entries($date)
    {
        $bookings = DBS_Booking::get_by_date_and_provider($date, $this->ID);
        $current_datetime = date('Y-m-d H:i:s');
        foreach (DukesBookingSystem::$valid_times as $index=>$time) {
            $booking = DBS_Booking::bookings_have_time($bookings, $time);
            $status = 'open';
            $locked = false;
            $text = 'Open<br>';
            $slot_datetime = date('Y-m-d H:i:s', strtotime("$date $time"));
            if ( $slot_datetime < $current_datetime ) {
                $status = 'over';
                $text = 'Done';
                write_log($slot_datetime);
                write_log($current_datetime);
                write_log('');
            }
            if ( $booking ) {
                $status = 'booked';
                $locked = true;
                $text = 'Booked<br>';
                if ( is_admin() && current_user_can('administrator')) {
                    $text = $booking->email . '<br>';
                    $text .= $booking->payment;
                }
            }
            echo "<div class='dbs-timeslot $status' data-locked='$locked' data-status='$status'>";
            echo "<input type='hidden' name='times[$this->ID][$index]' value='$time' data-status='$status' disabled>";
            echo "<strong>$time</strong><br>";
            echo $text;
            echo '</div>'; // .dbs-timeslot
        }
    }

}
