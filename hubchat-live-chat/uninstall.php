<?php
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('hubchat_widget_key');
delete_option('hubchat_settings');
delete_option('hubchat_identity_secret');
