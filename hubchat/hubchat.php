<?php
/**
 * Plugin Name:       HubChat – چت آنلاین
 * Plugin URI:        https://hubchat.ir
 * Description:       نصب ویجت چت آنلاین هاب‌چت روی سایت وردپرس بدون ویرایش کد قالب. ظاهر و امکانات ویجت از پنل هاب‌چت تنظیم می‌شود.
 * Version:           1.1.0
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Author:            HubChat
 * Author URI:        https://hubchat.ir
 * Text Domain:       hubchat
 * License:           GPL-2.0-or-later
 */
if (! defined('ABSPATH')) {
    exit;
}

define('HUBCHAT_VERSION', '1.1.0');
define('HUBCHAT_SCRIPT_URL', 'https://widget.hubchat.ir/widget.js');
define('HUBCHAT_OPTION', 'hubchat_widget_key');
define('HUBCHAT_SETTINGS', 'hubchat_settings');

/**
 * @return array{enabled: bool, hide_on_mobile: bool, logged_in_only: bool, excluded_ids: string}
 */
function hubchat_settings()
{
    $defaults = ['enabled' => true, 'hide_on_mobile' => false, 'logged_in_only' => false, 'excluded_ids' => ''];

    return array_merge($defaults, (array) get_option(HUBCHAT_SETTINGS, []));
}

function hubchat_key()
{
    return (string) get_option(HUBCHAT_OPTION, '');
}

add_action('admin_menu', function () {
    add_options_page('هاب‌چت', 'هاب‌چت', 'manage_options', 'hubchat', 'hubchat_render_settings');
});

add_filter('plugin_action_links_'.plugin_basename(__FILE__), function ($links) {
    array_unshift($links, '<a href="'.esc_url(admin_url('options-general.php?page=hubchat')).'">تنظیمات</a>');

    return $links;
});

add_action('admin_init', function () {
    register_setting('hubchat', HUBCHAT_OPTION, [
        'type' => 'string',
        'sanitize_callback' => function ($value) {
            $value = trim((string) $value);

            // کاربر ممکن است کل کد نصب را بچسباند؛ کلید از داخل آن بیرون کشیده می‌شود.
            if (preg_match('/hc_[a-z0-9]{24}/', $value, $match)) {
                return $match[0];
            }

            if ($value !== '') {
                add_settings_error(HUBCHAT_OPTION, 'hubchat_key', 'کلید ویجت معتبر نیست. کلید با hc_ شروع می‌شود.');
            }

            return '';
        },
    ]);

    register_setting('hubchat', HUBCHAT_SETTINGS, [
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

    if (hubchat_key() !== '' || ! current_user_can('manage_options') || ($screen && $screen->id === 'settings_page_hubchat')) {
        return;
    }

    printf(
        '<div class="notice notice-warning" dir="rtl"><p>ویجت هاب‌چت هنوز فعال نشده است. <a href="%s">کلید ویجت را وارد کنید</a>.</p></div>',
        esc_url(admin_url('options-general.php?page=hubchat'))
    );
});

function hubchat_render_settings()
{
    $settings = hubchat_settings();
    $name = HUBCHAT_SETTINGS;
    ?>
    <div class="wrap" dir="rtl">
        <h1>هاب‌چت – چت آنلاین</h1>
        <p>کلید ویجت را از «کانال‌ها &gt; ویجت چت سایت» در پنل هاب‌چت کپی کنید (مقدار <code>data-key</code> در کد نصب). می‌توانید کل کد نصب را هم اینجا بچسبانید.</p>
        <p>رنگ، متن، آیکن، جایگاه و بقیه‌ی امکانات ویجت از همان پنل تنظیم می‌شود و اینجا خودکار اعمال می‌شود.</p>
        <?php settings_errors(HUBCHAT_OPTION); ?>
        <form method="post" action="options.php">
            <?php settings_fields('hubchat'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="hubchat_key">کلید ویجت</label></th>
                    <td><input id="hubchat_key" name="<?php echo esc_attr(HUBCHAT_OPTION); ?>" class="regular-text" dir="ltr" placeholder="hc_..." value="<?php echo esc_attr(hubchat_key()); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">نمایش ویجت</th>
                    <td>
                        <label><input type="checkbox" name="<?php echo esc_attr($name); ?>[enabled]" value="1" <?php checked($settings['enabled']); ?>> روی سایت نمایش داده شود</label><br>
                        <label><input type="checkbox" name="<?php echo esc_attr($name); ?>[hide_on_mobile]" value="1" <?php checked($settings['hide_on_mobile']); ?>> در موبایل نمایش داده نشود</label><br>
                        <label><input type="checkbox" name="<?php echo esc_attr($name); ?>[logged_in_only]" value="1" <?php checked($settings['logged_in_only']); ?>> فقط برای کاربران واردشده</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="hubchat_excluded">صفحه‌های بدون ویجت</label></th>
                    <td>
                        <input id="hubchat_excluded" name="<?php echo esc_attr($name); ?>[excluded_ids]" class="regular-text" dir="ltr" placeholder="12, 45" value="<?php echo esc_attr($settings['excluded_ids']); ?>">
                        <p class="description">شناسه‌ی نوشته‌ها یا برگه‌ها، جدا با کاما.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('ذخیره'); ?>
        </form>

        <h2>باز کردن گفتگو از داخل صفحه</h2>
        <p>شورت‌کد <code>[hubchat_button text="گفتگو با پشتیبانی"]</code> دکمه‌ای می‌سازد که پنجره‌ی گفتگو را باز می‌کند. هر لینکی با آدرس <code>#hubchat</code> هم همین کار را می‌کند.</p>

        <?php if (class_exists('WooCommerce')) { ?>
            <h2>ووکامرس</h2>
            <p>برای وصل کردن سفارش‌ها و محصولات، در پنل هاب‌چت به «تنظیمات &gt; فروشگاه» بروید و «اتصال با یک کلیک» را بزنید.</p>
        <?php } ?>
    </div>
    <?php
}

function hubchat_should_render()
{
    $settings = hubchat_settings();

    if (hubchat_key() === '' || ! $settings['enabled'] || is_admin() || wp_doing_ajax()) {
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

    return (bool) apply_filters('hubchat_should_render', true);
}

add_action('wp_footer', function () {
    if (! hubchat_should_render()) {
        return;
    }

    printf('<script src="%s" data-key="%s" async></script>', esc_url(HUBCHAT_SCRIPT_URL), esc_attr(hubchat_key()));
    // لینک‌های #hubchat و دکمه‌ی شورت‌کد گفتگو را باز می‌کنند.
    echo "<script>document.addEventListener('click',function(e){var t=e.target.closest&&e.target.closest('a[href=\"#hubchat\"],.hubchat-open');if(t&&window.HubChat){e.preventDefault();window.HubChat('open');}});</script>";
}, 99);

add_shortcode('hubchat_button', function ($attributes) {
    $attributes = shortcode_atts(['text' => 'گفتگو با پشتیبانی', 'class' => ''], $attributes, 'hubchat_button');

    return sprintf(
        '<button type="button" class="hubchat-open %s">%s</button>',
        esc_attr($attributes['class']),
        esc_html($attributes['text'])
    );
});
