<?php
/**
 * Cookie Tracker for Visit Counting
 *
 * @package CookieLogicBlocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cookie_Logic_Blocks_Tracker
 */
class Cookie_Logic_Blocks_Tracker {

	/**
	 * Cookie name that stores all visit counts.
	 */
	const COOKIE_NAME = 'wp_visit_counts';

	/**
	 * Cookie expiration in seconds (6 months).
	 */
	const COOKIE_EXPIRY = 15552000; // 6 months = 180 days * 24 hours * 60 minutes * 60 seconds

	/**
	 * Initialize the cookie tracker.
	 */
	public static function init() {
		add_action( 'wp_footer', array( __CLASS__, 'output_tracking_script' ) );
	}

	/**
	 * Output JavaScript tracking script in footer.
	 */
	public static function output_tracking_script() {
		// Only track on singular pages/posts.
		if ( ! is_singular() ) {
			return;
		}

		global $post;

		// Check if tracking is enabled for this page.
		$enabled = get_post_meta( $post->ID, '_cookie_tracking_enabled', true );
		if ( $enabled !== '1' ) {
			return;
		}

		// Get the cookie name for this page.
		$cookie_name = get_post_meta( $post->ID, '_cookie_name', true );
		if ( empty( $cookie_name ) ) {
			$cookie_name = 'page_' . $post->ID;
		}

		// Output JavaScript to handle cookie tracking.
		?>
		<script type="text/javascript">
		(function() {
			'use strict';

			const COOKIE_NAME = '<?php echo esc_js( self::COOKIE_NAME ); ?>';
			const PAGE_KEY = '<?php echo esc_js( $cookie_name ); ?>';
			const EXPIRY_DAYS = 180; // 6 months

			/**
			 * Get a cookie value by name.
			 */
			function getCookie(name) {
				const value = `; ${document.cookie}`;
				const parts = value.split(`; ${name}=`);
				if (parts.length === 2) {
					return parts.pop().split(';').shift();
				}
				return null;
			}

			/**
			 * Set a cookie with expiration.
			 */
			function setCookie(name, value, days) {
				const date = new Date();
				date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
				const expires = `expires=${date.toUTCString()}`;
				const secure = window.location.protocol === 'https:' ? '; Secure' : '';
				document.cookie = `${name}=${value}; ${expires}; path=/; SameSite=Lax${secure}`;
			}

			/**
			 * Track the page visit.
			 */
			function trackVisit() {
				// Get existing cookie data.
				const cookieValue = getCookie(COOKIE_NAME);
				let visitCounts = {};

				// Parse existing data.
				if (cookieValue) {
					try {
						visitCounts = JSON.parse(decodeURIComponent(cookieValue));
					} catch (e) {
						// Invalid JSON, start fresh.
						visitCounts = {};
					}
				}

				// Increment counter for this page.
				if (!visitCounts[PAGE_KEY]) {
					visitCounts[PAGE_KEY] = 0;
				}
				visitCounts[PAGE_KEY]++;

				// Save updated cookie.
				const jsonString = JSON.stringify(visitCounts);
				setCookie(COOKIE_NAME, encodeURIComponent(jsonString), EXPIRY_DAYS);
			}

			// Track the visit when DOM is ready.
			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', trackVisit);
			} else {
				trackVisit();
			}
		})();
		</script>
		<?php
	}
}
