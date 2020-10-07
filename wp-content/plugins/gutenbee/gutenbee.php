<?php
/**
 * Plugin Name: GutenBee
 * Plugin URI: https://www.cssigniter.com/plugins/gutenbee/
 * Description: Premium Blocks for WordPress
 * Author: The CSSIgniter Team
 * Author URI: https://www.cssigniter.com
 * Version: 2.6.1
 * Text Domain: gutenbee
 * Domain Path: languages
 *
 * GutenBee is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * GutenBee is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GutenBee. If not, see <http://www.gnu.org/licenses/>.
 *
 */

if ( ! defined( 'GUTENBEE_PLUGIN_VERSION' ) ) {
	define( 'GUTENBEE_PLUGIN_VERSION', '2.6.1' );
}

if ( ! defined( 'GUTENBEE_PLUGIN_DIR' ) ) {
	define( 'GUTENBEE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'GUTENBEE_PLUGIN_DIR_URL' ) ) {
	define( 'GUTENBEE_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
}

add_action( 'init', 'gutenbee_init' );
function gutenbee_init() {
	load_plugin_textdomain( 'gutenbee', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'enqueue_block_editor_assets', 'gutenbee_enqueue_editor_assets' );
function gutenbee_enqueue_editor_assets() {
	wp_enqueue_script( 'gutenbee', untrailingslashit( GUTENBEE_PLUGIN_DIR_URL ) . '/build/gutenbee.build.js', array(
		'wp-components',
		'wp-blocks',
		'wp-element',
		'wp-editor',
		'wp-block-editor',
		'wp-data',
		'wp-date',
		'wp-i18n',
		'wp-compose',
		'wp-keycodes',
		'wp-html-entities',
		'wp-server-side-render',
	), GUTENBEE_PLUGIN_VERSION, true );

	$gutenbee_settings = get_option( 'gutenbee_settings' );
	$gutenbee_settings = gutenbee_validate_settings( $gutenbee_settings );
	wp_localize_script( 'gutenbee', '__GUTENBEE_SETTINGS__', $gutenbee_settings );

	wp_enqueue_style( 'gutenbee-editor', untrailingslashit( GUTENBEE_PLUGIN_DIR_URL ) . '/build/gutenbee.build.css', array(
		'wp-edit-blocks',
	), GUTENBEE_PLUGIN_VERSION );
}

add_action( 'wp_enqueue_scripts', 'gutenbee_enqueue_frontend_block_assets' );
function gutenbee_enqueue_frontend_block_assets() {
	$gutenbee_settings = get_option( 'gutenbee_settings' );
	$gutenbee_settings = gutenbee_validate_settings( $gutenbee_settings );
	$maps_api_key      = $gutenbee_settings['google_maps_api_key'];

	if ( $maps_api_key && has_block( 'gutenbee/google-maps' ) ) {
		wp_enqueue_script( 'gutenbee-google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $maps_api_key, array(), GUTENBEE_PLUGIN_VERSION, true );
	}

	$enqueue_css = false;
	$enqueue_js  = false;
	foreach ( gutenbee_get_blocks_info() as $block_name => $block_info ) {
		if ( has_block( $block_name ) ) {
			if ( ! $enqueue_css && $block_info['enqueue_css'] ) {
				$enqueue_css = true;
			}
			if ( ! $enqueue_js && $block_info['enqueue_js'] ) {
				$enqueue_js = true;
			}
		}
	}

	if ( $enqueue_css ) {
		wp_enqueue_style( 'gutenbee', untrailingslashit( GUTENBEE_PLUGIN_DIR_URL ) . '/build/gutenbee.scripts.css', array(), GUTENBEE_PLUGIN_VERSION );
	}

	if ( $enqueue_js ) {
		wp_enqueue_script( 'gutenbee-scripts', untrailingslashit( GUTENBEE_PLUGIN_DIR_URL ) . '/build/gutenbee.scripts.js', array(
			'jquery',
		), GUTENBEE_PLUGIN_VERSION, true );
	}
}

add_action( 'admin_enqueue_scripts', 'gutenbee_admin_styles' );

function gutenbee_admin_styles() {
	wp_enqueue_style( 'gutenbee-admin-styles', untrailingslashit( GUTENBEE_PLUGIN_DIR_URL ) . '/assets/css/admin.css', array(), GUTENBEE_PLUGIN_VERSION );
}

// GutenBee's block category
add_filter( 'block_categories', 'gutenbee_block_categories', 10, 2 );
function gutenbee_block_categories( $categories, $post ) {
	return array_merge( $categories, array(
		array(
			'slug'  => 'gutenbee',
			'title' => __( 'GutenBee', 'gutenbee' ),
		),
	) );
}

/**
 * Returns a list of settings names and their corresponding labels.
 * Note that the list may not contain all blocks. E.g. google-maps
 *
 * @return array
 */
function gutenbee_get_setting_block_names() {
	// Setting keys here for each block MUST be the same slugs
	// used in the block's registration definition (check each block's
	// index.js file, after `gutenbee/XYZ`, XYZ is the block's key).
	return array(
		'accordion'         => __( 'Accordion Block', 'gutenbee' ),
		'buttons'           => __( 'Button Block', 'gutenbee' ),
		'container'         => __( 'Container Block', 'gutenbee' ),
		'countdown'         => __( 'Countdown Block', 'gutenbee' ),
		'countup'           => __( 'Countup Block', 'gutenbee' ),
		'divider'           => __( 'Divider Block', 'gutenbee' ),
		'food-menu'         => __( 'Food Menu Block', 'gutenbee' ),
		'heading'           => __( 'Heading Block', 'gutenbee' ),
		'icon'              => __( 'Icon Block', 'gutenbee' ),
		'iconbox'           => __( 'Icon Box Block', 'gutenbee' ),
		'image'             => __( 'Image Block', 'gutenbee' ),
		'imagebox'          => __( 'Image Box Block', 'gutenbee' ),
		'image-comparison'  => __( 'Image Comparison Block', 'gutenbee' ),
		'justified-gallery' => __( 'Justified Gallery Block', 'gutenbee' ),
		'paragraph'         => __( 'Paragraph Block', 'gutenbee' ),
		'post-types'        => __( 'Post Types Block', 'gutenbee' ),
		'progress-bar'      => __( 'Progress Bar Block', 'gutenbee' ),
		'spacer'            => __( 'Spacer Block', 'gutenbee' ),
		'slideshow'         => __( 'Slideshow Block', 'gutenbee' ),
		'tabs'              => __( 'Tabs Block', 'gutenbee' ),
		'video'             => __( 'Video Block', 'gutenbee' ),
//			'lottie'            => __( 'Lottie Player Block', 'gutenbee' ),
	);
}

/**
 * Returns a list of blocks and information about them.
 *
 * @return array
 */
function gutenbee_get_blocks_info() {
	return array(
		'gutenbee/accordion'         => array(
			'label'       => __( 'Accordion Block', 'gutenbee' ),
			'enqueue_js'  => true,
			'enqueue_css' => true,
		),
		'gutenbee/button'            => array(
			'label'       => __( 'Button Block', 'gutenbee' ),
			'enqueue_js'  => false,
			'enqueue_css' => true,
		),
		'gutenbee/buttons'           => array(
			'label'       => __( 'Buttons Block', 'gutenbee' ),
			'enqueue_js'  => false,
			'enqueue_css' => true,
		),
		'gutenbee/container'         => array(
			'label'       => __( 'Container Block', 'gutenbee' ),
			'enqueue_js'  => true,
			'enqueue_css' => true,
		),
		'gutenbee/column'            => array(
			'label'       => __( 'Column Block', 'gutenbee' ),
			'enqueue_js'  => false,
			'enqueue_css' => false,
		),
		'gutenbee/countdown'         => array(
			'label'       => __( 'Countdown Block', 'gutenbee' ),
			'enqueue_js'  => true,
			'enqueue_css' => true,
		),
		'gutenbee/countup'           => array(
			'label'       => __( 'Countup Block', 'gutenbee' ),
			'enqueue_js'  => true,
			'enqueue_css' => true,
		),
		'gutenbee/divider'           => array(
			'label'       => __( 'Divider Block', 'gutenbee' ),
			'enqueue_js'  => true,
			'enqueue_css' => true,
		),
		'gutenbee/google-maps'       => array(
			'label'       => __( 'Google Map Block', 'gutenbee' ),
			'enqueue_js'  => true,
			'enqueue_css' => true,
		),
		'gutenbee/heading'           => array(
			'label'       => __( 'Heading Block', 'gutenbee' ),
			'enqueue_js'  => false,
			'enqueue_css' => false,
		),
		'gutenbee/icon'              => array(
			'label'       => __( 'Icon Block', 'gutenbee' ),
			'enqueue_js'  => false,
			'enqueue_css' => true,
		),
		'gutenbee/iconbox'           => array(
			'label'       => __( 'Icon Box Block', 'gutenbee' ),
			'enqueue_js'  => false,
			'enqueue_css' => true,
		),
		'gutenbee/image'             => array(
			'label'       => __( 'Image Block', 'gutenbee' ),
			'enqueue_js'  => false,
			'enqueue_css' => false,
		),
		'gutenbee/imagebox'          => array(
			'label'       => __( 'Image Box Block', 'gutenbee' ),
			'enqueue_js'  => false,
			'enqueue_css' => true,
		),
		'gutenbee/image-comparison'  => array(
			'label'       => __( 'Image Comparison Block', 'gutenbee' ),
			'enqueue_js'  => true,
			'enqueue_css' => true,
		),
		'gutenbee/justified-gallery' => array(
			'label'       => __( 'Justified Gallery Block', 'gutenbee' ),
			'enqueue_js'  => true,
			'enqueue_css' => true,
		),
//		'gutenbee/lottie'            => array(
//			'label'       => __( 'Lottie Player Block', 'gutenbee' ),
//			'enqueue_js'  => false,
//			'enqueue_css' => false,
//		),
		'gutenbee/paragraph'         => array(
			'label'       => __( 'Paragraph Block', 'gutenbee' ),
			'enqueue_js'  => false,
			'enqueue_css' => false,
		),
		'gutenbee/post-types'        => array(
			'label'       => __( 'Post Types Block', 'gutenbee' ),
			'enqueue_js'  => false,
			'enqueue_css' => true,
		),
		'gutenbee/progress-bar'      => array(
			'label'       => __( 'Progress Block', 'gutenbee' ),
			'enqueue_js'  => false,
			'enqueue_css' => true,
		),
		'gutenbee/slideshow'         => array(
			'label'       => __( 'Slideshow Block', 'gutenbee' ),
			'enqueue_js'  => true,
			'enqueue_css' => true,
		),
		'gutenbee/spacer'            => array(
			'label'       => __( 'Spacer Block', 'gutenbee' ),
			'enqueue_js'  => true,
			'enqueue_css' => true,
		),
		'gutenbee/tabs'              => array(
			'label'       => __( 'Tabs Block', 'gutenbee' ),
			'enqueue_js'  => true,
			'enqueue_css' => true,
		),
		'gutenbee/video'             => array(
			'label'       => __( 'Video Block', 'gutenbee' ),
			'enqueue_js'  => false,
			'enqueue_css' => true,
		),
		'gutenbee/food-menu'         => array(
			'label'       => __( 'Food Menu', 'gutenbee' ),
			'enqueue_js'  => false,
			'enqueue_css' => true,
		),
	);
}

/**
 * Makes sure there are no undefined indexes in the settings array.
 * Use before using a setting value. Eleminates the need for isset() before using.
 *
 * @param $settings
 *
 * @return array
 */
function gutenbee_validate_settings( $settings ) {
	$defaults = array(
		'active_google-maps'  => 1,
		'google_maps_api_key' => '',
	);
	foreach ( gutenbee_get_setting_block_names() as $id => $name ) {
		$defaults[ 'active_' . $id ] = 1;
	}

	$settings = wp_parse_args( $settings, $defaults );

	$settings['theme_supports']['post-types'] = gutenbee_block_post_types_get_theme_support();
	$settings['theme_supports']['container']  = gutenbee_block_container_get_theme_support();

	return $settings;
}

/**
 * Returns the appropriate page(d) query variable to use in custom loops (needed for pagination).
 *
 * @uses get_query_var()
 *
 * @param int $default_return The default page number to return, if no query vars are set.
 *
 * @return int The appropriate paged value if found, else 0.
 */
function gutenbee_get_page_var( $default_return = 0 ) {
	$paged = get_query_var( 'paged', false );
	$page  = get_query_var( 'page', false );

	if ( false === $paged && false === $page ) {
		return $default_return;
	}

	return max( $paged, $page );
}


function gutenbee_get_columns_classes( $columns ) {
	switch ( intval( $columns ) ) {
		case 1:
			$classes = 'columns-1';
			break;
		case 2:
			$classes = 'columns-2';
			break;
		case 3:
			$classes = 'columns-3';
			break;
		case 4:
		default:
			$classes = 'columns-4';
			break;
	}

	return apply_filters( 'gutenbee_get_columns_classes', $classes, $columns );
}

function gutenbee_get_template_part( $block, $slug, $name = '', $template_vars = array() ) {
	$templates = array();
	$name      = (string) $name;
	if ( '' !== $name ) {
		$templates[] = "{$slug}-{$name}.php";
	}

	$templates[] = "{$slug}.php";

	$located = gutenbee_locate_template( $block, $templates );

	if ( ! empty( $located ) ) {
		include $located;
	}
}

function gutenbee_locate_template( $block, $templates ) {
	$plugin_path = plugin_dir_path( __FILE__ );

	// The templates path in the plugin, i.e. defaults/fallback. E.g. src/blocks/post-types/templates/
	$default_path = trailingslashit( trailingslashit( $plugin_path ) . "src/blocks/{$block}/templates" );

	// The templates path in the theme. E.g. gutenbee/
	$theme_path = apply_filters( 'gutenbee_locate_template_theme_path', "gutenbee/{$block}", $block );
	$theme_path = trailingslashit( $theme_path );

	$theme_templates = array();
	foreach ( $templates as $template ) {
		$theme_templates[] = $theme_path . $template;
	}

	// Try to find a theme-overridden template.
	$located = locate_template( $theme_templates, false );

	$located = apply_filters( 'gutenbee_locate_template', $located, $block, $theme_templates, $templates, $theme_path, $default_path );

	if ( empty( $located ) ) {
		// Nope. Try the plugin templates instead.
		foreach ( $templates as $template ) {
			if ( file_exists( $default_path . $template ) ) {
				$located = $default_path . $template;
				break;
			}
		}
	}

	return $located;
}

require_once untrailingslashit( dirname( __FILE__ ) ) . '/inc/options.php';
require_once untrailingslashit( dirname( __FILE__ ) ) . '/src/blocks/container/block.php';
require_once untrailingslashit( dirname( __FILE__ ) ) . '/src/blocks/post-types/block.php';

// TODO think what to do here enabling JSON uploads
add_filter( 'wp_check_filetype_and_ext', 'gutenbee_file_and_ext_json', 10, 4 );
function gutenbee_file_and_ext_json( $types, $file, $filename, $mimes ) {
	if ( false !== strpos( $filename, '.json' ) ) {
		$types['ext']  = 'json';
		$types['type'] = 'application/json';
	}
	return $types;
}

function gutenbee_mime_types( $mimes ) {
	$mimes['json']  = 'application/json';
	return $mimes;
}

add_filter( 'upload_mimes', 'gutenbee_mime_types' );