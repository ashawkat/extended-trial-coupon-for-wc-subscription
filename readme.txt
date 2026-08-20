=== Extended Trial Coupon for WC Subscription ===
Contributors: betatech, adnanshawkat
Tags: woocommerce subscription, trial coupon, subscription trial, free trial extension, subscription discount
Requires at least: 5.7
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 1.7
WC requires at least: 8.0
WC tested up to: 11.0.1
License: GPLv2
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html

Easily extend the trial period of WooCommerce Subscription products using a new 'Subscription Trial' coupon type.

== Description ==
Extended Trial Coupon for WC Subscription is a maintained and improved version of the original “Free Trial Coupon for WooCommerce Subscriptions” plugin, which had not received updates in over two years.

The plugin introduces a new "Subscription Trial" coupon type inside WooCommerce's coupon settings. When applied, the coupon extends the default trial period of WooCommerce Subscription products.

This plugin requires both **WooCommerce** and **WooCommerce Subscriptions** to be active.

### Key Features
- Adds a new coupon type: **Subscription Trial**
- Extends trial length for WooCommerce Subscription products
- Supports:
  - Sign Up Fee Discount
  - Sign Up Fee Percentage Discount
  - Recurring Product Discount
  - Recurring Product Percentage Discount
- Compatible with WooCommerce Subscriptions workflow
- Compatible with Cart & Checkout blocks
- Fully compatible with **WooCommerce HPOS (High-Performance Order Storage)**
- Translation-ready with included language files

### Included Translations
- Swedish
- Spanish (Spain)
- German
- Dutch
- French (France)

== Installation ==

### Install via WordPress Dashboard
1. Navigate to **Plugins → Add New**
2. Search for **Extended Trial Coupon for WC Subscription**
3. Click **Install Now**, then **Activate**
4. Go to **WooCommerce → Coupons** to configure your trial coupon

### Install via FTP
1. Download the plugin ZIP
2. Unzip the package
3. Upload the `extended-trial-coupon-for-wc-subscription` folder into `/wp-content/plugins/`
4. Activate the plugin from the **Plugins** menu

### Privacy Policy
Extended Trial Coupon for WC Subscription uses [Appsero](https://appsero.com) SDK to collect some telemetry data upon user's confirmation. This helps us to troubleshoot problems faster & make product improvements.

Appsero SDK **does not gather any data by default.** The SDK only starts gathering basic telemetry data **when a user allows it via the admin notice**. We collect the data to ensure a great user experience for all our users.

Integrating Appsero SDK **DOES NOT IMMEDIATELY** start gathering data, **without confirmation from users in any case.**

Learn more about how [Appsero collects and uses this data](https://appsero.com/privacy-policy/).

== Frequently Asked Questions ==

= Does the plugin work without WooCommerce? =
No. WooCommerce must be installed and active.

= Does the plugin work without WooCommerce Subscriptions? =
No. WooCommerce Subscriptions is required for trial extension functionality.

= Is it compatible with existing WooCommerce Subscription coupon types? =
Yes. It works seamlessly with all default subscription-related coupon types.

= Is it compatible with WooCommerce HPOS? =
Yes. The plugin declares High-Performance Order Storage compatibility and does not read or write order data through legacy post meta.

= Is it compatible with Cart and Checkout blocks? =
Yes. Trial overrides use WooCommerce Subscriptions product filters, so they apply in both classic and block checkout.

== Screenshots ==
1. WooCommerce → Coupons screen
2. Before applying the trial coupon
3. After applying the trial coupon

== Changelog ==

= 1.7 =
* Updated: Tested with WordPress 7.1, WooCommerce 11.0.1, and PHP 8.5
* Security: Review notice actions now require a nonce and capability check
* Security: Coupon trial fields are sanitized and capability-checked on save
* Fixed: "Already Rated" review action did not dismiss the notice
* Fixed: Trial coupons could be rejected as an unknown WooCommerce coupon type
* Fixed: Only the first applied coupon was checked for trial data
* Changed: Trial length is applied via subscription filters instead of mutating product meta (HPOS-safe)
* Added: Cart & Checkout blocks compatibility declaration

= 1.6 =
* Updated: Tested compatibility with WordPress 7.0
* Updated: Short description to meet WordPress.org directory requirements
* Fixed: Removed duplicate autoloader require statement

= 1.5 =
* Updated: Tested compatibility with WooCommerce 10.3.5
* Added: WooCommerce HPOS (High-Performance Order Storage) compatibility

= 1.4 =
* Updated: Tested compatibility with WooCommerce 8.8

= 1.3 =
* Fixed: Review prompt display issue

= 1.2 =
* Fixed: Minor admin notice issues

= 1.1 =
* Added: Review request prompt

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.7 =
Recommended update — WordPress 7.1 / WooCommerce 11.0.1 compatibility, HPOS-safe trial application, and security fixes.

= 1.6 =
Recommended update — includes WordPress 7.0 compatibility and directory fixes.

= 1.5 =
Recommended update — includes latest WC compatibility + HPOS support.

= 1.4 =
Recommended update — tested against WooCommerce 8.8.

= 1.3 =
Includes improvements to review prompt handling.

= 1.2 =
Minor improvements and notice fixes.

= 1.1 =
Adds review request feature.

= 1.0 =
First release.
