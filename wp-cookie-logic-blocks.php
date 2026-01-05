<?php
/**
 * Plugin Name: WP Cookie Logic Blocks
 * Plugin URI: https://github.com/yourusername/wp-cookie-logic-blocks
 * Description: Track page visit counts via cookies and control block visibility based on visit thresholds.
 * Version: 1.0.1
 * Author: Michael Lajlev
 * Author URI: https://lillefar.dk
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cookie-logic-blocks
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'COOKIE_LOGIC_BLOCKS_VERSION', '1.0.1' );
define( 'COOKIE_LOGIC_BLOCKS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'COOKIE_LOGIC_BLOCKS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Include required files.
 */
require_once COOKIE_LOGIC_BLOCKS_PLUGIN_DIR . 'includes/class-meta-box.php';
require_once COOKIE_LOGIC_BLOCKS_PLUGIN_DIR . 'includes/class-cookie-tracker.php';

/**
 * Initialize the plugin.
 */
function cookie_logic_blocks_init() {
	// Initialize meta box for page settings.
	Cookie_Logic_Blocks_Meta_Box::init();

	// Initialize cookie tracker.
	Cookie_Logic_Blocks_Tracker::init();

	// Register the block.
	register_block_type( COOKIE_LOGIC_BLOCKS_PLUGIN_DIR . 'build' );
}
add_action( 'init', 'cookie_logic_blocks_init' );

/**
 * Enqueue frontend script for visibility logic.
 */
function cookie_logic_blocks_enqueue_frontend() {
	if ( is_admin() ) {
		return;
	}

	wp_enqueue_script(
		'cookie-logic-blocks-frontend',
		COOKIE_LOGIC_BLOCKS_PLUGIN_URL . 'build/frontend.js',
		array(),
		COOKIE_LOGIC_BLOCKS_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'cookie_logic_blocks_enqueue_frontend' );
