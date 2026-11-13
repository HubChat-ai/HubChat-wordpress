<?php
/**
 * Plugin Name:       HubChat Live Chat
 * Plugin URI:        https://hubchat.ir
 * Description:       Adds the HubChat live chat widget to your WordPress site without editing theme code. The widget's look and features are managed in your HubChat panel.
 * Version:           1.1.1
 * Requires at least: 5.7
 * Requires PHP:      7.4
 * Author:            HubChat
 * Author URI:        https://hubchat.ir
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hubchat-live-chat
 * Domain Path:       /languages
 */
if (! defined('ABSPATH')) {
    exit;
}

define('HUBCHAT_LIVE_CHAT_VERSION', '1.1.1');
define('HUBCHAT_LIVE_CHAT_SCRIPT_URL', 'https://widget.hubchat.ir/widget.js');
define('HUBCHAT_LIVE_CHAT_KEY_OPTION', 'hubchat_widget_key');
define('HUBCHAT_LIVE_CHAT_SETTINGS_OPTION', 'hubchat_settings');

/**
 * @return array{enabled: bool, hide_on_mobile: bool, logged_in_only: bool, excluded_ids: string}
 */
function hubchat_live_chat_settings()
{
    $defaults = ['enabled' => true, 'hide_on_mobile' => false, 'logged_in_only' => false, 'excluded_ids' => ''];

    return array_merge($defaults, (array) get_option(HUBCHAT_LIVE_CHAT_SETTINGS_OPTION, []));
}

function hubchat_live_chat_key()
{
    return (string) get_option(HUBCHAT_LIVE_CHAT_KEY_OPTION, '');
}

add_action('init', function () {
    load_plugin_textdomain('hubchat-live-chat', false, dirname(plugin_basename(__FILE__)).'/languages');
});

add_action('admin_menu', function () {
    add_options_page(
        __('HubChat', 'hubchat-live-chat'),
        __('HubChat', 'hubchat-live-chat'),
        'manage_options',
        'hubchat-live-chat',
        'hubchat_live_chat_render_settings'
    );
});

add_filter('plugin_action_links_'.plugin_basename(__FILE__), function ($links) {
    array_unshift($links, sprintf(
        '<a href="%s">%s</a>',
        esc_url(admin_url('options-general.php?page=hubchat-live-chat')),
        esc_html__('Settings', 'hubchat-live-chat')
    ));

    return $links;
});

add_action('admin_init', function () {
    register_setting('hubchat_live_chat', HUBCHAT_LIVE_CHAT_KEY_OPTION, [
        'type' => 'string',
        'sanitize_callback' => function ($value) {
            $value = trim((string) $value);

            // The whole install snippet may be pasted; the key is extracted from it.
            if (preg_match('/hc_[a-z0-9]{24}/', $value, $match)) {
                return $match[0];
            }

            if ($value !== '') {
                add_settings_error(
                    HUBCHAT_LIVE_CHAT_KEY_OPTION,
                    'hubchat_key',
                    __('The widget key is not valid. It starts with hc_.', 'hubchat-live-chat')
                );
            }

            return '';
        },
    ]);

    register_setting('hubchat_live_chat', HUBCHAT_LIVE_CHAT_SETTINGS_OPTION, [
        'type' => 'array',
        'sanitize_callback' => function ($value) {
            $value = (array) $value;

            return [
                'enabled' => ! empty($value['enabled']),
                'hide_on_mobile' => ! empty($value['hide_on_mobile']),
                'logged_in_only' => ! empty($value['logged_in_only']),
                'excluded_ids' => implode(',', array_filter(array_map('absint', explode(',', (string) ($value['excluded_ids'] ?? ''))))),
            ];
        },
    ]);
});

add_action('admin_notices', function () {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;

    if (hubchat_live_chat_key() !== '' || ! current_user_can('manage_options') || ($screen && $screen->id === 'settings_page_hubchat-live-chat')) {
        return;
    }

    printf(
        '<div class="notice notice-warning"><p>%s <a href="%s">%s</a></p></div>',
        esc_html__('The HubChat widget is not active yet.', 'hubchat-live-chat'),
        esc_url(admin_url('options-general.php?page=hubchat-live-chat')),
        esc_html__('Enter your widget key', 'hubchat-live-chat')
    );
});

function hubchat_live_chat_render_settings()
{
    if (! current_user_can('manage_options')) {
        return;
    }

    $settings = hubchat_live_chat_settings();
    $name = HUBCHAT_LIVE_CHAT_SETTINGS_OPTION;
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('HubChat Live Chat', 'hubchat-live-chat'); ?></h1>
        <p><?php esc_html_e('Copy the widget key from Channels > Website chat widget in your HubChat panel (the data-key value of the install code). You can also paste the whole install code here.', 'hubchat-live-chat'); ?></p>
        <p><?php esc_html_e('Color, text, icon, position and the other widget features are set in the HubChat panel and applied here automatically.', 'hubchat-live-chat'); ?></p>
        <?php settings_errors(HUBCHAT_LIVE_CHAT_KEY_OPTION); ?>
        <form method="post" action="options.php">
            <?php settings_fields('hubchat_live_chat'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="hubchat_key"><?php esc_html_e('Widget key', 'hubchat-live-chat'); ?></label></th>
                    <td><input id="hubchat_key" name="<?php echo esc_attr(HUBCHAT_LIVE_CHAT_KEY_OPTION); ?>" class="regular-text" dir="ltr" placeholder="hc_..." value="<?php echo esc_attr(hubchat_live_chat_key()); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Display', 'hubchat-live-chat'); ?></th>
                    <td>
                        <label><input type="checkbox" name="<?php echo esc_attr($name); ?>[enabled]" value="1" <?php checked($settings['enabled']); ?>> <?php esc_html_e('Show the widget on the site', 'hubchat-live-chat'); ?></label><br>
                        <label><input type="checkbox" name="<?php echo esc_attr($name); ?>[hide_on_mobile]" value="1" <?php checked($settings['hide_on_mobile']); ?>> <?php esc_html_e('Hide on mobile devices', 'hubchat-live-chat'); ?></label><br>
                        <label><input type="checkbox" name="<?php echo esc_attr($name); ?>[logged_in_only]" value="1" <?php checked($settings['logged_in_only']); ?>> <?php esc_html_e('Only for logged-in users', 'hubchat-live-chat'); ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="hubchat_excluded"><?php esc_html_e('Pages without the widget', 'hubchat-live-chat'); ?></label></th>
                    <td>
                        <input id="hubchat_excluded" name="<?php echo esc_attr($name); ?>[excluded_ids]" class="regular-text" dir="ltr" placeholder="12, 45" value="<?php echo esc_attr($settings['excluded_ids']); ?>">
                        <p class="description"><?php esc_html_e('Post or page IDs, separated by commas.', 'hubchat-live-chat'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Save', 'hubchat-live-chat')); ?>
        </form>

        <h2><?php esc_html_e('Open the chat from a page', 'hubchat-live-chat'); ?></h2>
        <p>
            <?php
            printf(
                /* translators: 1: shortcode, 2: link href */
                esc_html__('The %1$s shortcode adds a button that opens the chat window. Any link to %2$s does the same.', 'hubchat-live-chat'),
                '<code>[hubchat_button]</code>',
                '<code>#hubchat</code>'
            );
            ?>
        </p>

        <?php if (class_exists('WooCommerce')) { ?>
            <h2><?php esc_html_e('WooCommerce', 'hubchat-live-chat'); ?></h2>
            <p><?php esc_html_e('To connect orders and products, go to Settings > Store in your HubChat panel and use one-click connect.', 'hubchat-live-chat'); ?></p>
        <?php } ?>
    </div>
    <?php
}

function hubchat_live_chat_should_render()
{
    $settings = hubchat_live_chat_settings();

    if (hubchat_live_chat_key() === '' || ! $settings['enabled'] || is_admin()) {
        return false;
    }

    if ($settings['logged_in_only'] && ! is_user_logged_in()) {
        return false;
    }

    if ($settings['hide_on_mobile'] && wp_is_mobile()) {
        return false;
    }

    $excluded = array_filter(array_map('absint', explode(',', $settings['excluded_ids'])));

    if ($excluded && is_singular() && in_array((int) get_queried_object_id(), $excluded, true)) {
        return false;
    }

    return (bool) apply_filters('hubchat_live_chat_should_render', true);
}

add_action('wp_enqueue_scripts', function () {
    if (! hubchat_live_chat_should_render()) {
        return;
    }

    wp_enqueue_script('hubchat-live-chat', HUBCHAT_LIVE_CHAT_SCRIPT_URL, [], null, ['in_footer' => true, 'strategy' => 'async']);
    wp_add_inline_script(
        'hubchat-live-chat',
        "document.addEventListener('click',function(e){var t=e.target.closest&&e.target.closest('a[href=\"#hubchat\"],.hubchat-open');if(t&&window.HubChat){e.preventDefault();window.HubChat('open');}});"
    );
});

/**
 * widget.js reads its key from the data-key attribute of its own script tag.
 */
add_filter('script_loader_tag', function ($tag, $handle) {
    if ($handle !== 'hubchat-live-chat') {
        return $tag;
    }

    return str_replace(' src=', ' data-key="'.esc_attr(hubchat_live_chat_key()).'" src=', $tag);
}, 10, 2);

add_shortcode('hubchat_button', function ($attributes) {
    $attributes = shortcode_atts([
        'text' => __('Chat with support', 'hubchat-live-chat'),
        'class' => '',
    ], $attributes, 'hubchat_button');

    return sprintf(
        '<button type="button" class="hubchat-open %s">%s</button>',
        esc_attr($attributes['class']),
        esc_html($attributes['text'])
    );
});
