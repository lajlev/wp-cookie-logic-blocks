# WP Cookie Logic Blocks

A minimal WordPress plugin that tracks page visit counts via cookies and provides a custom Gutenberg block with visibility logic based on visit thresholds.

## Features

- **Page Visit Tracking**: Track how many times a visitor has viewed specific pages
- **Custom Cookie Names**: Set custom identifiers for each tracked page
- **Conditional Block**: A Gutenberg block that shows/hides content based on visit counts
- **Privacy-Friendly**: Uses client-side cookies, no server-side tracking
- **Cache-Compatible**: Works with caching plugins since tracking is handled in JavaScript

## Installation

1. Upload the `wp-cookie-logic-blocks` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Start configuring pages to track visits

## Usage

### Step 1: Enable Cookie Tracking on a Page

1. Edit any page or post
2. Look for the "Cookie Tracking Settings" meta box in the sidebar
3. Check "Enable cookie tracking for this page"
4. Set a custom cookie name (e.g., `page_home`, `product_launch`, etc.)
   - Default: `page_{ID}` (e.g., `page_123`)
   - Only alphanumeric characters, underscores, and dashes allowed
5. Publish or update the page

### Step 2: Add the Conditional Block

1. In the Gutenberg editor, add a new block
2. Search for "Conditional Block"
3. Configure the block settings in the right sidebar:
   - **Target Cookie Name**: Enter the cookie name from Step 1 (e.g., `page_home`)
   - **Minimum Visits**: Set the threshold (e.g., show after 3 visits)
4. Add any content inside the Conditional Block (text, images, other blocks, etc.)
5. Publish your page

### Example Use Case

**Scenario**: Show a special offer to repeat visitors of your pricing page

1. Edit your pricing page (e.g., `/pricing`)
2. Enable cookie tracking with name: `pricing_page`
3. On your homepage or any other page, add a "Conditional Block"
4. Set Target Cookie Name: `pricing_page`
5. Set Minimum Visits: `2`
6. Add your special offer content inside the block
7. The block will only show to visitors who have viewed the pricing page 2+ times

## How It Works

### Cookie Structure

The plugin stores all visit counts in a single cookie named `wp_visit_counts` in JSON format:

```json
{
  "page_home": 5,
  "pricing_page": 3,
  "product_launch": 1
}
```

### Cookie Details

- **Name**: `wp_visit_counts`
- **Expiry**: 6 months
- **Path**: `/` (site-wide)
- **SameSite**: Lax
- **Secure**: Yes (on HTTPS sites)
- **HttpOnly**: No (needs JavaScript access)

### Visibility Logic

The Conditional Block:
1. Reads the `wp_visit_counts` cookie on page load
2. Checks if the target page counter meets the minimum threshold
3. Shows or hides the block via CSS (`display: none`)
4. Defaults to hidden if JavaScript is disabled

## Development

### Requirements

- Node.js 18+
- npm 8+
- WordPress 6.0+
- PHP 7.4+

### Build from Source

```bash
# Install dependencies
npm install

# Development mode (with hot reload)
npm run start

# Production build
npm run build
```

### File Structure

```
wp-cookie-logic-blocks/
├── wp-cookie-logic-blocks.php          # Main plugin file
├── package.json                         # Dependencies
├── includes/
│   ├── class-cookie-tracker.php        # Cookie management
│   └── class-meta-box.php              # Page settings UI
├── src/
│   ├── index.js                        # Block registration
│   ├── edit.js                         # Editor component
│   ├── save.js                         # Frontend output
│   ├── block.json                      # Block metadata
│   └── frontend.js                     # Visibility logic
└── build/                               # Compiled assets
```

## Technical Details

### Why Single Cookie?

- Avoids browser cookie limits (~50 per domain)
- More efficient than multiple cookies
- Easier to manage and parse

### Why Client-Side Tracking?

- No server round-trips needed
- Works with page caching
- Better performance
- Respects visitor privacy (no server-side analytics)

### Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Requires JavaScript for tracking and visibility logic
- Graceful degradation: blocks hidden if JavaScript disabled

## Privacy & GDPR

This plugin uses cookies to track page visits. Depending on your jurisdiction, you may need to:

1. Add a cookie notice/consent banner
2. Update your privacy policy
3. Allow users to opt-out of tracking

The plugin does not:
- Send data to external servers
- Track personally identifiable information
- Use third-party tracking services

## Troubleshooting

### Block not showing/hiding correctly

1. Check browser console for JavaScript errors
2. Verify the cookie name matches exactly (case-sensitive)
3. Clear browser cookies and test again
4. Disable caching plugins temporarily to test

### Visit counter not incrementing

1. Check that cookie tracking is enabled on the page
2. Verify cookies are enabled in browser
3. Check browser console for errors
4. Try in incognito mode to rule out cookie issues

### Build errors

```bash
# Clear node_modules and reinstall
rm -rf node_modules package-lock.json
npm install
npm run build
```

## Support

For issues and feature requests, please open an issue on GitHub.

## License

GPL v2 or later
