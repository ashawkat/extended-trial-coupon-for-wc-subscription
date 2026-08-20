# Extended Trial Coupon for WooCommerce Subscriptions

Extend and customize the trial period of WooCommerce Subscription products using a dedicated coupon type. Ideal for promotions, marketing campaigns, and providing extended trial access without altering subscription product settings.

---

## 🚀 Features

- Adds a new coupon type: **Subscription Trial**
- Extends trial duration for WooCommerce Subscription products
- Supports:
  - Sign Up Fee Discount
  - Sign Up Fee % Discount
  - Recurring Product Discount
  - Recurring Product % Discount
- Fully compatible with **WooCommerce HPOS (High-Performance Order Storage)**
- Compatible with Cart & Checkout blocks
- Translation files included:
  - Swedish  
  - Spanish (Spain)
  - German  
  - Dutch  
  - French (France)
- Modern, maintained fork of a previously outdated plugin

---

## 🛠 Requirements

- **WordPress** 5.7+ (tested up to 7.1)  
- **PHP** 8.0+ (tested up to 8.5)  
- **WooCommerce** 8.0+ (tested up to 11.0.1)  
- **WooCommerce Subscriptions** plugin  

---

## 📦 Installation

### **Install via WordPress Admin**
1. Go to **Plugins → Add New**
2. Search for: **Extended Trial Coupon for WC Subscription**
3. Click **Install Now** and then **Activate**
4. Go to **WooCommerce → Coupons** to create a trial coupon

### **Install via FTP**
1. Download the plugin ZIP  
2. Extract files  
3. Upload the `extended-trial-coupon-for-wc-subscription` folder to `/wp-content/plugins/`  
4. Activate the plugin  

---

### Privacy Policy
Extended Trial Coupon for WC Subscription uses [Appsero](https://appsero.com) SDK to collect some telemetry data upon user's confirmation. This helps us to troubleshoot problems faster & make product improvements.
Appsero SDK **does not gather any data by default.** The SDK only starts gathering basic telemetry data **when a user allows it via the admin notice**. We collect the data to ensure a great user experience for all our users.
Integrating Appsero SDK **DOES NOT IMMEDIATELY** start gathering data, **without confirmation from users in any case.**
Learn more about how [Appsero collects and uses this data](https://appsero.com/privacy-policy/).

---

## ❓ FAQ

### Does it work without WooCommerce?
No. WooCommerce is required.

### Does it work without WooCommerce Subscriptions?
No. Subscription extension requires WooCommerce Subscriptions.

### Does it support other subscription coupon types?
Yes. It integrates with all default WooCommerce Subscriptions coupon features.

### Is it HPOS compatible?
Yes. Compatibility is declared with WooCommerce, and trial overrides do not write order or product meta through legacy post APIs.

---

## 🖼 Screenshots

1. WooCommerce → Coupons screen  
2. Before applying trial coupon  
3. After applying trial coupon  

---

## 📜 Changelog

### **1.7**
- Updated: Tested with WordPress **7.1**, WooCommerce **11.0.1**, and PHP **8.5**
- Security: Review notice actions now require a nonce and capability check
- Security: Coupon trial fields are sanitized and capability-checked on save
- Fixed: "Already Rated" review action did not dismiss the notice
- Fixed: Trial coupons could be rejected as an unknown WooCommerce coupon type
- Fixed: Only the first applied coupon was checked for trial data
- Changed: Trial length is applied via subscription filters instead of mutating product meta (HPOS-safe)
- Added: Cart & Checkout blocks compatibility declaration

### **1.6**
- Updated: Tested compatibility with WordPress **7.0**
- Updated: Short description to meet WordPress.org directory requirements
- Fixed: Removed duplicate autoloader require statement

### **1.5**
- Updated: Compatibility with WooCommerce **10.3.5**
- Added: **WooCommerce HPOS (High-Performance Order Storage) support**

### **1.4**
- Updated compatibility with WooCommerce **8.8**

### **1.3**
- Fixed review prompt display issue

### **1.2**
- Fixed minor admin notice warnings

### **1.1**
- Added review request functionality

### **1.0**
- Initial release

---

## 👨‍💻 Contributors

- **betatech**  
- **adnanshawkat**

---

## ⚖ License

GPLv2 — see https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
