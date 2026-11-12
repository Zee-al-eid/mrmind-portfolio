<?php
/**
 * Custom post types and taxonomies.
 *
 * Phase 2 (Content model):
 *   - Product      — one post per digital product (single or bundle).
 *   - Testimonial  — reusable curated social proof, entered once, shown anywhere.
 *   - Product Collection — taxonomy for grouping products on the hub page.
 *
 * Field definitions for these live in /acf-json (ACF Pro), so structure is code
 * and configuration is version-controlled alongside it.
 *
 * @package MrMind
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Product post type.
 *
 * Permalinks resolve to /products/<slug>/ per the migration plan's URL scheme.
 * `page-attributes` is enabled so the client can drag-order products on the hub.
 */
function mrmind_register_product_cpt() {
	$labels = array(
		'name'                  => _x( 'Products', 'post type general name', 'mrmind' ),
		'singular_name'         => _x( 'Product', 'post type singular name', 'mrmind' ),
		'menu_name'             => _x( 'Products', 'admin menu', 'mrmind' ),
		'add_new'               => __( 'Add New', 'mrmind' ),
		'add_new_item'          => __( 'Add New Product', 'mrmind' ),
		'edit_item'             => __( 'Edit Product', 'mrmind' ),
		'new_item'              => __( 'New Product', 'mrmind' ),
		'view_item'             => __( 'View Product', 'mrmind' ),
		'view_items'            => __( 'View Products', 'mrmind' ),
		'search_items'          => __( 'Search Products', 'mrmind' ),
		'not_found'             => __( 'No products found.', 'mrmind' ),
		'not_found_in_trash'    => __( 'No products found in Trash.', 'mrmind' ),
		'all_items'             => __( 'All Products', 'mrmind' ),
		'featured_image'        => __( 'Hero image', 'mrmind' ),
		'set_featured_image'    => __( 'Set hero image', 'mrmind' ),
		'remove_featured_image' => __( 'Remove hero image', 'mrmind' ),
	);

	register_post_type( 'product', array(
		'labels'             => $labels,
		'description'        => __( 'Digital products — single systems and bundles.', 'mrmind' ),
		'public'             => true,
		'has_archive'        => true,
		'show_in_rest'       => true,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-products',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'revisions' ),
		'taxonomies'         => array( 'product_collection' ),
		'rewrite'            => array( 'slug' => 'products', 'with_front' => false ),
		'capability_type'    => 'post',
		'hierarchical'       => false,
	) );
}
add_action( 'init', 'mrmind_register_product_cpt' );

/**
 * Register the Testimonial post type.
 *
 * Not publicly queryable — testimonials have no pages of their own; they are
 * pulled into product pages and the homepage. Admin UI is fully enabled.
 */
function mrmind_register_testimonial_cpt() {
	$labels = array(
		'name'               => _x( 'Testimonials', 'post type general name', 'mrmind' ),
		'singular_name'      => _x( 'Testimonial', 'post type singular name', 'mrmind' ),
		'menu_name'          => _x( 'Testimonials', 'admin menu', 'mrmind' ),
		'add_new'            => __( 'Add New', 'mrmind' ),
		'add_new_item'       => __( 'Add New Testimonial', 'mrmind' ),
		'edit_item'          => __( 'Edit Testimonial', 'mrmind' ),
		'new_item'           => __( 'New Testimonial', 'mrmind' ),
		'search_items'       => __( 'Search Testimonials', 'mrmind' ),
		'not_found'          => __( 'No testimonials found.', 'mrmind' ),
		'not_found_in_trash' => __( 'No testimonials found in Trash.', 'mrmind' ),
		'all_items'          => __( 'All Testimonials', 'mrmind' ),
	);

	register_post_type( 'testimonial', array(
		'labels'             => $labels,
		'description'        => __( 'Curated customer testimonials, reusable across the site.', 'mrmind' ),
		'public'             => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'publicly_queryable' => false,
		'exclude_from_search'=> true,
		'has_archive'        => false,
		'rewrite'            => false,
		'menu_position'      => 21,
		'menu_icon'          => 'dashicons-format-quote',
		// Title acts as an internal label; the quote itself is an ACF field.
		'supports'           => array( 'title', 'page-attributes', 'revisions' ),
		'capability_type'    => 'post',
		'hierarchical'       => false,
	) );
}
add_action( 'init', 'mrmind_register_testimonial_cpt' );

/**
 * Register the Product Collection taxonomy (e.g. Foundations, Systems, Bundles).
 */
function mrmind_register_product_collection_tax() {
	$labels = array(
		'name'              => _x( 'Product Collections', 'taxonomy general name', 'mrmind' ),
		'singular_name'     => _x( 'Product Collection', 'taxonomy singular name', 'mrmind' ),
		'menu_name'         => __( 'Collections', 'mrmind' ),
		'search_items'      => __( 'Search Collections', 'mrmind' ),
		'all_items'         => __( 'All Collections', 'mrmind' ),
		'edit_item'         => __( 'Edit Collection', 'mrmind' ),
		'update_item'       => __( 'Update Collection', 'mrmind' ),
		'add_new_item'      => __( 'Add New Collection', 'mrmind' ),
		'new_item_name'     => __( 'New Collection Name', 'mrmind' ),
		'not_found'         => __( 'No collections found.', 'mrmind' ),
	);

	register_taxonomy( 'product_collection', array( 'product' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'collections', 'with_front' => false ),
	) );
}
add_action( 'init', 'mrmind_register_product_collection_tax' );

/**
 * Flush rewrite rules once after the CPTs are registered, so /products/<slug>/
 * resolves without a manual Settings → Permalinks visit.
 */
function mrmind_maybe_flush_rewrites() {
	if ( get_option( 'mrmind_rewrites_flushed' ) === MRMIND_VERSION ) {
		return;
	}
	flush_rewrite_rules( false );
	update_option( 'mrmind_rewrites_flushed', MRMIND_VERSION );
}
add_action( 'init', 'mrmind_maybe_flush_rewrites', 99 );

/**
 * Show the product's price in the admin list for quick scanning.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function mrmind_product_admin_columns( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['mrmind_type']  = __( 'Type', 'mrmind' );
			$new['mrmind_price'] = __( 'Price', 'mrmind' );
		}
	}
	return $new;
}
add_filter( 'manage_product_posts_columns', 'mrmind_product_admin_columns' );

/**
 * Render the custom product admin columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function mrmind_product_admin_column_content( $column, $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}

	if ( 'mrmind_price' === $column ) {
		$price = get_field( 'price', $post_id );
		echo $price ? esc_html( $price ) : '&mdash;';
	}

	if ( 'mrmind_type' === $column ) {
		$type = get_field( 'product_type', $post_id );
		echo $type ? esc_html( ucfirst( $type ) ) : '&mdash;';
	}
}
add_action( 'manage_product_posts_custom_column', 'mrmind_product_admin_column_content', 10, 2 );

/**
 * Order the product archive by the client's drag-order (page attributes),
 * falling back to title. Keeps the hub's curated ladder order editable
 * without code.
 *
 * @param WP_Query $query The query.
 */
function mrmind_order_product_archive( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'product' ) || $query->is_tax( 'product_collection' ) ) {
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
		$query->set( 'posts_per_page', 12 );
	}
}
add_action( 'pre_get_posts', 'mrmind_order_product_archive' );
