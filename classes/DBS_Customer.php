<?php

class DBS_Customer
{

    // get all the customers ------------------------------
    static function get_all()
    {
        // need to filter admins 
        $results = get_users();
        return $results;
    }

    // add some extra fields for user forms ---------------
    static function custom_user_fields($user)
    {
        if (isset($user->ID)) {
            $phone = get_the_author_meta('phone', $user->ID);
            $address = get_the_author_meta('address', $user->ID);
        } else {
            $phone = '';
            $address = '';
        }
        echo "<table class='form-table'>";
        echo "<tr>";
        echo "<th><label for='phone'>Phone</label></th>";
        echo "<td>";
        echo "<input type='text' name='phone' id='phone' value='$phone'>";
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<th><label for='address'>Address</label></th>";
        echo "<td>";
        echo "<textarea name='address' id='address' rows='5' cols='100'>$address</textarea>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";
    }

    // when saving, include our fields --------------------
    static function save_custom_user_fields($user_id)
    {
        # again do this only if you can
        if (!current_user_can('manage_options'))
            return false;

        # save my custom field
        update_usermeta($user_id, 'phone', $_POST['phone']);
        update_usermeta($user_id, 'address', $_POST['address']);
    }

    // simple search and return of customers ----------
    static function search_customers()
    {
        $s = $_POST['search'];
        $args = array(
            'number' => 5,
        );
        if ($s !== '') {
            $args['search'] = '*' . $_POST['search'] . '*';
        }
        $user_query = new WP_User_Query($args);
        foreach ($user_query->get_results() as $customer) {
            $html = '';
            $html .= '<br>';
            $html .= '<div>';
            $html .= $customer->data->user_nicename . '<br>';
            $html .= get_user_meta($customer->ID, 'first_name', true) . ' ' . get_user_meta($customer->ID, 'last_name', true) . '<br>';
            $html .= $customer->data->user_email . '<br>';
            $html .= get_user_meta($customer->ID, 'description', true);
            $html .= '</div>';
            $html .= '<br>';
            echo '<br>';
            echo "<input style='display: inline-block' type='radio' id='$customer->ID' name='dbs_booking-customer_id' value='$customer->ID'>";
            echo "<label style='display: inline-block' for='$customer->ID'>$html</label>";
        }
        wp_die();
    }

    // ====================================================
    // instance methods 
    // ====================================================

    function __construct($ID) {
        $userdata = get_userdata($ID);
        $this->ID = $ID;
        $this->userdata = $userdata;
        $this->first_name = get_user_meta($ID, 'first_name', true);
        $this->last_name = get_user_meta($ID, 'last_name', true);
        // just to catch if there's no user
        $this->email = 'no user';
        if ( $userdata ) {
            $this->email = $userdata->user_email;
        }
    }

}

add_action('show_user_profile', ['DBS_Customer', 'custom_user_fields']);
add_action('edit_user_profile', ['DBS_Customer', 'custom_user_fields']);
add_action('profile_update', ['DBS_Customer', 'save_custom_user_fields']);
add_action("user_new_form", ['DBS_Customer', 'custom_user_fields']);
add_action('user_register', ['DBS_Customer', 'save_custom_user_fields']);