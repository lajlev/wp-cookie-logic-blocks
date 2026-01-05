<?php
/**
 * Meta Box for Page-Level Cookie Settings
 *
 * @package CookieLogicBlocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cookie_Logic_Blocks_Meta_Box
 */
class Cookie_Logic_Blocks_Meta_Box {

	/**
	 * Initialize the meta box.
	 */
	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ) );
		add_action( 'save_post', array( __CLASS__, 'save_meta_box' ) );
	}

	/**
	 * Add meta box to post/page edit screen.
	 */
	public static function add_meta_box() {
		add_meta_box(
			'cookie_logic_blocks_settings',
			__( 'Cookie Tracking Settings', 'cookie-logic-blocks' ),
			array( __CLASS__, 'render_meta_box' ),
			array( 'post', 'page' ),
			'side',
			'default'
		);
	}

	/**
	 * Render the meta box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public static function render_meta_box( $post ) {
		// Add nonce for security.
		wp_nonce_field( 'cookie_logic_blocks_meta_box', 'cookie_logic_blocks_nonce' );

		// Get current values.
		$enabled     = get_post_meta( $post->ID, '_cookie_tracking_enabled', true );
		$cookie_name = get_post_meta( $post->ID, '_cookie_name', true );

		// Default cookie name to page ID if not set.
		if ( empty( $cookie_name ) ) {
			$cookie_name = 'page_' . $post->ID;
		}
		?>
		<div class="cookie-logic-blocks-settings">
			<p>
				<label>
					<input
						type="checkbox"
						name="cookie_tracking_enabled"
						value="1"
						<?php checked( $enabled, '1' ); ?>
					/>
					<?php esc_html_e( 'Enable cookie tracking for this page', 'cookie-logic-blocks' ); ?>
				</label>
			</p>
			<p>
				<label for="cookie_name">
					<?php esc_html_e( 'Cookie Name:', 'cookie-logic-blocks' ); ?>
				</label><br>
				<input
					type="text"
					id="cookie_name"
					name="cookie_name"
					value="<?php echo esc_attr( $cookie_name ); ?>"
					class="widefat"
					placeholder="page_<?php echo esc_attr( $post->ID ); ?>"
				/>
				<small class="description">
					<?php esc_html_e( 'Alphanumeric and underscores only.', 'cookie-logic-blocks' ); ?>
				</small>
			</p>
		</div>
		<?php
	}

	/**
	 * Save meta box data.
	 *
	 * @param int $post_id The post ID.
	 */
	public static function save_meta_box( $post_id ) {
		// Check nonce.
		if ( ! isset( $_POST['cookie_logic_blocks_nonce'] ) ||
		     ! wp_verify_nonce( $_POST['cookie_logic_blocks_nonce'], 'cookie_logic_blocks_meta_box' ) ) {
			return;
		}

		// Check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save enabled checkbox.
		$enabled = isset( $_POST['cookie_tracking_enabled'] ) ? '1' : '0';
		update_post_meta( $post_id, '_cookie_tracking_enabled', $enabled );

		// Save and sanitize cookie name.
		if ( isset( $_POST['cookie_name'] ) ) {
			$cookie_name = self::sanitize_cookie_name( $_POST['cookie_name'] );

			// If empty after sanitization, use default.
			if ( empty( $cookie_name ) ) {
				$cookie_name = 'page_' . $post_id;
			}

			update_post_meta( $post_id, '_cookie_name', $cookie_name );
		}
	}

	/**
	 * Sanitize cookie name to alphanumeric and underscore only.
	 *
	 * @param string $name The cookie name to sanitize.
	 * @return string The sanitized cookie name.
	 */
	private static function sanitize_cookie_name( $name ) {
		// Remove any characters that aren't alphanumeric or underscore.
		return preg_replace( '/[^a-zA-Z0-9_]/', '', $name );
	}
}
