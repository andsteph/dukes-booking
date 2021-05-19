<?php

/**
 * Registers the `dbs_activity` post type.
 */
function dbs_activity_init() {
	register_post_type( 'dbs_activity', array(
		'labels'                => array(
			'name'                  => __( 'Activities', 'dukes-booking' ),
			'singular_name'         => __( 'Activity', 'dukes-booking' ),
			'all_items'             => __( 'All Activities', 'dukes-booking' ),
			'archives'              => __( 'Activity Archives', 'dukes-booking' ),
			'attributes'            => __( 'Activity Attributes', 'dukes-booking' ),
			'insert_into_item'      => __( 'Insert into Activity', 'dukes-booking' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Activity', 'dukes-booking' ),
			'featured_image'        => _x( 'Featured Image', 'dbs_activity', 'dukes-booking' ),
			'set_featured_image'    => _x( 'Set featured image', 'dbs_activity', 'dukes-booking' ),
			'remove_featured_image' => _x( 'Remove featured image', 'dbs_activity', 'dukes-booking' ),
			'use_featured_image'    => _x( 'Use as featured image', 'dbs_activity', 'dukes-booking' ),
			'filter_items_list'     => __( 'Filter Activities list', 'dukes-booking' ),
			'items_list_navigation' => __( 'Activities list navigation', 'dukes-booking' ),
			'items_list'            => __( 'Activities list', 'dukes-booking' ),
			'new_item'              => __( 'New Activity', 'dukes-booking' ),
			'add_new'               => __( 'Add New', 'dukes-booking' ),
			'add_new_item'          => __( 'Add New Activity', 'dukes-booking' ),
			'edit_item'             => __( 'Edit Activity', 'dukes-booking' ),
			'view_item'             => __( 'View Activity', 'dukes-booking' ),
			'view_items'            => __( 'View Activities', 'dukes-booking' ),
			'search_items'          => __( 'Search Activities', 'dukes-booking' ),
			'not_found'             => __( 'No Activities found', 'dukes-booking' ),
			'not_found_in_trash'    => __( 'No Activities found in trash', 'dukes-booking' ),
			'parent_item_colon'     => __( 'Parent Activity:', 'dukes-booking' ),
			'menu_name'             => __( 'Activities', 'dukes-booking' ),
		),
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
        'show_in_menu'          => false,
		'show_in_nav_menus'     => true,
		'supports'              => array( 'title', 'editor' ),
		'has_archive'           => true,
		'rewrite'               => true,
		'query_var'             => true,
		'menu_position'         => null,
		'menu_icon'             => 'dashicons-admin-post',
		'show_in_rest'          => true,
		'rest_base'             => 'dbs_activity',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'dbs_activity_init' );

/**
 * Sets the post updated messages for the `dbs_activity` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `dbs_activity` post type.
 */
function dbs_activity_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['dbs_activity'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Activity updated. <a target="_blank" href="%s">View Activity</a>', 'dukes-booking' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'dukes-booking' ),
		3  => __( 'Custom field deleted.', 'dukes-booking' ),
		4  => __( 'Activity updated.', 'dukes-booking' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Activity restored to revision from %s', 'dukes-booking' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Activity published. <a href="%s">View Activity</a>', 'dukes-booking' ), esc_url( $permalink ) ),
		7  => __( 'Activity saved.', 'dukes-booking' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Activity submitted. <a target="_blank" href="%s">Preview Activity</a>', 'dukes-booking' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Activity scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Activity</a>', 'dukes-booking' ),
		date_i18n( __( 'M j, Y @ G:i', 'dukes-booking' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Activity draft updated. <a target="_blank" href="%s">Preview Activity</a>', 'dukes-booking' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'dbs_activity_updated_messages' );
