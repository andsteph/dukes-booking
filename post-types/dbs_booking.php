<?php

/**
 * Registers the `dbs_booking` post type.
 */
function dbs_booking_init() {
	register_post_type( 'dbs_booking', array(
		'labels'                => array(
			'name'                  => __( 'Bookings', 'dukes-booking' ),
			'singular_name'         => __( 'Booking', 'dukes-booking' ),
			'all_items'             => __( 'All Bookings', 'dukes-booking' ),
			'archives'              => __( 'Booking Archives', 'dukes-booking' ),
			'attributes'            => __( 'Booking Attributes', 'dukes-booking' ),
			'insert_into_item'      => __( 'Insert into Booking', 'dukes-booking' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Booking', 'dukes-booking' ),
			'featured_image'        => _x( 'Featured Image', 'dbs_booking', 'dukes-booking' ),
			'set_featured_image'    => _x( 'Set featured image', 'dbs_booking', 'dukes-booking' ),
			'remove_featured_image' => _x( 'Remove featured image', 'dbs_booking', 'dukes-booking' ),
			'use_featured_image'    => _x( 'Use as featured image', 'dbs_booking', 'dukes-booking' ),
			'filter_items_list'     => __( 'Filter Bookings list', 'dukes-booking' ),
			'items_list_navigation' => __( 'Bookings list navigation', 'dukes-booking' ),
			'items_list'            => __( 'Bookings list', 'dukes-booking' ),
			'new_item'              => __( 'New Booking', 'dukes-booking' ),
			'add_new'               => __( 'Add New', 'dukes-booking' ),
			'add_new_item'          => __( 'Add New Booking', 'dukes-booking' ),
			'edit_item'             => __( 'Edit Booking', 'dukes-booking' ),
			'view_item'             => __( 'View Booking', 'dukes-booking' ),
			'view_items'            => __( 'View Bookings', 'dukes-booking' ),
			'search_items'          => __( 'Search Bookings', 'dukes-booking' ),
			'not_found'             => __( 'No Bookings found', 'dukes-booking' ),
			'not_found_in_trash'    => __( 'No Bookings found in trash', 'dukes-booking' ),
			'parent_item_colon'     => __( 'Parent Booking:', 'dukes-booking' ),
			'menu_name'             => __( 'Bookings', 'dukes-booking' ),
		),
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
        'show_in_menu'          => false,
		'show_in_nav_menus'     => true,
		'supports'              => false,
		'has_archive'           => true,
		'rewrite'               => true,
		'query_var'             => true,
		'menu_position'         => null,
		'menu_icon'             => 'dashicons-admin-post',
		'show_in_rest'          => true,
		'rest_base'             => 'dbs_booking',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'dbs_booking_init' );

/**
 * Sets the post updated messages for the `dbs_booking` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `dbs_booking` post type.
 */
function dbs_booking_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['dbs_booking'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Booking updated. <a target="_blank" href="%s">View Booking</a>', 'dukes-booking' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'dukes-booking' ),
		3  => __( 'Custom field deleted.', 'dukes-booking' ),
		4  => __( 'Booking updated.', 'dukes-booking' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Booking restored to revision from %s', 'dukes-booking' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Booking published. <a href="%s">View Booking</a>', 'dukes-booking' ), esc_url( $permalink ) ),
		7  => __( 'Booking saved.', 'dukes-booking' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Booking submitted. <a target="_blank" href="%s">Preview Booking</a>', 'dukes-booking' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Booking scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Booking</a>', 'dukes-booking' ),
		date_i18n( __( 'M j, Y @ G:i', 'dukes-booking' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Booking draft updated. <a target="_blank" href="%s">Preview Booking</a>', 'dukes-booking' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'dbs_booking_updated_messages' );
