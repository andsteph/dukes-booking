<?php

/**
 * Registers the `dbs_provider` post type.
 */
function dbs_provider_init() {
	register_post_type( 'dbs_provider', array(
		'labels'                => array(
			'name'                  => __( 'Providers', 'dukes-booking' ),
			'singular_name'         => __( 'Provider', 'dukes-booking' ),
			'all_items'             => __( 'All Providers', 'dukes-booking' ),
			'archives'              => __( 'Provider Archives', 'dukes-booking' ),
			'attributes'            => __( 'Provider Attributes', 'dukes-booking' ),
			'insert_into_item'      => __( 'Insert into Provider', 'dukes-booking' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Provider', 'dukes-booking' ),
			'featured_image'        => _x( 'Featured Image', 'dbs_provider', 'dukes-booking' ),
			'set_featured_image'    => _x( 'Set featured image', 'dbs_provider', 'dukes-booking' ),
			'remove_featured_image' => _x( 'Remove featured image', 'dbs_provider', 'dukes-booking' ),
			'use_featured_image'    => _x( 'Use as featured image', 'dbs_provider', 'dukes-booking' ),
			'filter_items_list'     => __( 'Filter Providers list', 'dukes-booking' ),
			'items_list_navigation' => __( 'Providers list navigation', 'dukes-booking' ),
			'items_list'            => __( 'Providers list', 'dukes-booking' ),
			'new_item'              => __( 'New Provider', 'dukes-booking' ),
			'add_new'               => __( 'Add New', 'dukes-booking' ),
			'add_new_item'          => __( 'Add New Provider', 'dukes-booking' ),
			'edit_item'             => __( 'Edit Provider', 'dukes-booking' ),
			'view_item'             => __( 'View Provider', 'dukes-booking' ),
			'view_items'            => __( 'View Providers', 'dukes-booking' ),
			'search_items'          => __( 'Search Providers', 'dukes-booking' ),
			'not_found'             => __( 'No Providers found', 'dukes-booking' ),
			'not_found_in_trash'    => __( 'No Providers found in trash', 'dukes-booking' ),
			'parent_item_colon'     => __( 'Parent Provider:', 'dukes-booking' ),
			'menu_name'             => __( 'Providers', 'dukes-booking' ),
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
		'rest_base'             => 'dbs_provider',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'dbs_provider_init' );

/**
 * Sets the post updated messages for the `dbs_provider` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `dbs_provider` post type.
 */
function dbs_provider_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['dbs_provider'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Provider updated. <a target="_blank" href="%s">View Provider</a>', 'dukes-booking' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'dukes-booking' ),
		3  => __( 'Custom field deleted.', 'dukes-booking' ),
		4  => __( 'Provider updated.', 'dukes-booking' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Provider restored to revision from %s', 'dukes-booking' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Provider published. <a href="%s">View Provider</a>', 'dukes-booking' ), esc_url( $permalink ) ),
		7  => __( 'Provider saved.', 'dukes-booking' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Provider submitted. <a target="_blank" href="%s">Preview Provider</a>', 'dukes-booking' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Provider scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Provider</a>', 'dukes-booking' ),
		date_i18n( __( 'M j, Y @ G:i', 'dukes-booking' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Provider draft updated. <a target="_blank" href="%s">Preview Provider</a>', 'dukes-booking' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'dbs_provider_updated_messages' );
