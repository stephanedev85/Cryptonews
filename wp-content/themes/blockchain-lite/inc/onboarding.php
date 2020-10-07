<?php
/**
 * Blockchain_Lite onboarding related code.
 */
if ( ! defined( 'BLOCKCHAIN_LITE_WHITELABEL' ) || false === (bool) BLOCKCHAIN_LITE_WHITELABEL ) {
	add_action( 'pt-ocdi/after_import', 'blockchain_lite_ocdi_after_import_setup' );
}

add_filter( 'pt-ocdi/timeout_for_downloading_import_file', 'blockchain_lite_ocdi_download_timeout' );
function blockchain_lite_ocdi_download_timeout( $timeout ) {
	return 60;
}

add_filter( 'pt-ocdi/plugin_intro_text', 'blockchain_lite_ocdi_intro_text' );
function blockchain_lite_ocdi_intro_text( $html ) {
	$sample_content_url = blockchain_lite_documentation_url() . '#sample-content';

	ob_start();
	?>
	<p><?php
		/* translators: %s is a url. */
		echo wp_kses_post( sprintf( __( 'Looking for the Blockchain Lite sample content files? Download them <a href="%s" target="_blank">here</a>.', 'blockchain-lite' ), esc_url( $sample_content_url ) ) );
	?></p>
	<hr>
	<?php

	$append_html = ob_get_clean();

	return $html . $append_html;
}

function blockchain_lite_ocdi_after_import_setup() {
	// Set up nav menus.
	$main_menu = get_term_by( 'name', 'Main', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
		'menu-1' => $main_menu->term_id,
	) );

	// Set up home and blog pages.
	$front_page_id = get_page_by_title( 'Home' );
	$blog_page_id  = get_page_by_title( 'Blog' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );
	update_option( 'page_for_posts', $blog_page_id->ID );

	// Try to force a term recount.
	// wp_defer_term_counting( false ) doesn't work properly as there are post imported from different AJAX requests.
	$taxonomies = get_taxonomies( array(), 'names' );
	foreach ( $taxonomies as $taxonomy ) {
		$terms             = get_terms( $taxonomy, array( 'hide_empty' => false ) );
		$term_taxonomy_ids = wp_list_pluck( $terms, 'term_taxonomy_id' );

		wp_update_term_count( $term_taxonomy_ids, $taxonomy );
	}
}

function blockchain_lite_get_theme_required_plugins() {
	return apply_filters( 'blockchain_lite_theme_required_plugins', array() );
}

function blockchain_lite_get_theme_recommended_plugins() {
	return apply_filters( 'blockchain_lite_theme_recommended_plugins', array(
		'gutenbee'              => array(
			'title'       => __( 'GutenBee', 'blockchain-lite' ),
			'description' => __( 'Premium blocks for WordPress.', 'blockchain-lite' ),
		),
		'woocommerce'           => array(
			'title'       => __( 'WooCommerce', 'blockchain-lite' ),
			'description' => __( 'Sell anything, beautifully.', 'blockchain-lite' ),
		),
		'maxslider'             => array(
			'title'       => __( 'MaxSlider', 'blockchain-lite' ),
			'description' => __( 'Add a custom responsive slider to any page of your website.', 'blockchain-lite' ),
		),
		'elementor'             => array(
			'title'              => __( 'Elementor', 'blockchain-lite' ),
			'description'        => __( 'Elementor is a front-end drag & drop page builder for WordPress.', 'blockchain-lite' ),
			'required_by_sample' => true,
		),
		'audioigniter'          => array(
			'title'       => __( 'AudioIgniter', 'blockchain-lite' ),
			'description' => __( 'Probably the most flexible music player plugin for WordPress.', 'blockchain-lite' ),
		),
		'wp-smushit'            => array(
			'title'       => __( 'Smush by WPMU DEV', 'blockchain-lite' ),
			'description' => __( 'Compress, Optimize and Lazy Load Images.', 'blockchain-lite' ),
			'plugin_file' => 'wp-smush.php',
		),
		'wpforms-lite'          => array(
			'title'       => __( 'Contact Form by WPForms', 'blockchain-lite' ),
			'description' => __( 'Drag & Drop Form Builder for WordPress.', 'blockchain-lite' ),
			'plugin_file' => 'wpforms.php',
			'is_callable' => 'wpforms',
		),
		'one-click-demo-import' => array(
			'title'              => __( 'One Click Demo Import', 'blockchain-lite' ),
			'description'        => __( 'Import your demo content, widgets and theme settings with one click.', 'blockchain-lite' ),
			'required_by_sample' => true,
		),
	) );
}

add_action( 'init', 'blockchain_lite_onboarding_page_init' );
function blockchain_lite_onboarding_page_init() {

	$data = array(
		'show_page'                => true,
		'redirect_on_activation'   => false,
		'description'              => __( 'Blockchain Lite is a powerful business theme for WordPress. <strong>WooCommerce</strong> is also supported by Blockchain Lite.', 'blockchain-lite' ),
		'default_tab'              => 'recommended_plugins',
		'tabs'                     => array(
			'required_plugins'    => '',
			'recommended_plugins' => __( 'Recommended Plugins', 'blockchain-lite' ),
			'sample_content'      => __( 'Sample Content', 'blockchain-lite' ),
			'support'             => __( 'Support', 'blockchain-lite' ),
			'upgrade_pro'         => __( 'Upgrade to Pro', 'blockchain-lite' ),
		),
		'required_plugins_page'    => array(
			'plugins' => blockchain_lite_get_theme_required_plugins(),
		),
		'recommended_plugins_page' => array(
			'plugins' => blockchain_lite_get_theme_recommended_plugins(),
		),
		'support_page'             => array(
			'sections' => array(
				'documentation' => array(
					'title'       => __( 'Theme Documentation', 'blockchain-lite' ),
					'description' => __( "If you don't want to import our demo sample content, just visit this page and learn how to set things up individually.", 'blockchain-lite' ),
					'link_url'    => blockchain_lite_documentation_url(),
				),
				'kb'            => array(
					'title'       => __( 'Knowledge Base', 'blockchain-lite' ),
					'description' => __( 'Browse our library of step by step how-to articles, tutorials, and guides to get quick answers.', 'blockchain-lite' ),
					'link_url'    => 'https://www.cssigniter.com/docs/knowledgebase/',
				),
				'support'       => array(
					'title'       => __( 'Request Support', 'blockchain-lite' ),
					'description' => __( 'Got stuck? No worries, just visit our support page, submit your ticket and we will be there for you within 24 hours.', 'blockchain-lite' ),
					'link_url'    => 'https://wordpress.org/support/theme/blockchain-lite',
				),
			),
		),
	);

	require_once get_theme_file_path( '/inc/class-onboarding-page-lite.php' );

	$onboarding = new Blockchain_Lite_Onboarding_Page_Lite();
	$onboarding->init( apply_filters( 'blockchain_lite_onboarding_page_array', $data ) );
}

/**
 * User onboarding.
 */
require_once get_theme_file_path( '/inc/onboarding/onboarding-page.php' );
