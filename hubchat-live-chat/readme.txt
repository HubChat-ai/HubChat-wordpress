=== HubChat Live Chat ===
Contributors: hubchat
Tags: live chat, chat, customer support, chat widget, woocommerce
Requires at least: 5.7
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add the HubChat live chat widget to your site and answer visitors from one shared inbox, next to Telegram, Bale, Rubika and Instagram.

== Description ==

HubChat is a shared inbox for customer conversations. This plugin installs the HubChat website chat widget on your WordPress site without editing theme code.

Messages from your visitors arrive in your HubChat inbox together with your other channels, and your team replies from one place.

The widget's look and features are managed in your HubChat panel and applied automatically:

* Brand color, launcher text, icon and position
* Knowledge base search inside the widget
* File and image sharing
* Working hours and offline message
* Satisfaction survey (CSAT) after a conversation
* Links to your other channels (Telegram, Instagram, ...)

Plugin settings:

* Show or hide the widget on mobile devices
* Show the widget only to logged-in users
* Exclude specific posts or pages
* `[hubchat_button]` shortcode and `#hubchat` links to open the chat
* `hubchat_live_chat_should_render` filter for developers

== External services ==

This plugin connects to the HubChat service (https://hubchat.ir) to show the chat widget. A HubChat account and a widget key are required.

When the widget is enabled, every front-end page loads the script https://widget.hubchat.ir/widget.js. The script loads the widget settings and, when a visitor opens the chat, the chat window from widget.hubchat.ir. The messages and files a visitor sends, and the page address they chat from, are sent to HubChat so your team can answer them. No data is sent while the widget key is empty or the widget is disabled.

* Terms of service: https://hubchat.ir/terms
* Privacy policy: https://hubchat.ir/privacy

== Installation ==

1. Install the plugin from Plugins > Add New, or upload the zip file, and activate it.
2. In your HubChat panel, open Channels > Website chat widget and copy the widget key (hc_...).
3. Go to Settings > HubChat, paste the key and save.

== Frequently Asked Questions ==

= Where do I change the widget's color, text or icon? =

In your HubChat panel, under the website chat widget settings. Changes appear on your site automatically.

= Does the widget slow down my site? =

No. The script loads asynchronously, and the chat window is only loaded on the first interaction or when the browser is idle.

= Can I open the chat from my own button? =

Yes. Use the `[hubchat_button text="Talk to us"]` shortcode, or link to `#hubchat`.

== Screenshots ==

1. The chat widget on a website.
2. Plugin settings page.

== Changelog ==

= 1.2.0 =
* Logged-in users and WooCommerce customers are identified to HubChat (name, email, phone, address, order count, total spent), signed with the identity secret. Developers can extend the data with the hubchat_live_chat_user filter.
* Signing out clears the chat of that user from the browser.

= 1.1.3 =
* Plugin URI points to the plugin guide.

= 1.1.2 =
* Tested with WordPress 7.1.
* Load the bundled translation only when WordPress has not loaded one.
* Versioned widget script URL.

= 1.1.1 =
* Documentation updates.

= 1.1.0 =
* Display settings: mobile, logged-in users only, excluded pages.
* `[hubchat_button]` shortcode and `#hubchat` links.
* Persian (fa_IR) translation.

= 1.0.0 =
* First release.
