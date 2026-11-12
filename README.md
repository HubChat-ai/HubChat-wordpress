# HubChat Live Chat – WordPress Plugin

افزونه‌ی وردپرس برای نصب ویجت چت آنلاین هاب‌چت بدون ویرایش کد قالب. کد افزونه در پوشه‌ی `hubchat-live-chat/` است و نام این پوشه همان slug افزونه در wordpress.org است.

## نصب برای کاربر
- از مخزن افزونه‌های وردپرس: «افزونه‌ها > افزودن» و جستجوی **HubChat Live Chat**.
- یا فایل `hubchat-live-chat.zip` از [Releases](https://github.com/HubChat-ai/HubChat-wordpress/releases) و «بارگذاری افزونه».

## انتشار نسخه‌ی جدید
1. نسخه را در `hubchat-live-chat/hubchat-live-chat.php` (هدر و `HUBCHAT_LIVE_CHAT_VERSION`) و `Stable tag` در `readme.txt` بالا ببرید و Changelog را بنویسید.
2. تگ بزنید (مثلاً `1.1.1`) و push کنید.
3. GitHub Actions فایل zip را در Releases می‌گذارد و، اگر انتشار در wordpress.org فعال باشد، نسخه را به SVN وردپرس هم می‌فرستد.

## ثبت اولیه در wordpress.org (یک بار)
1. با حساب wordpress.org (مثلاً `hubchat`) وارد شوید و در https://wordpress.org/plugins/developers/add/ فایل `hubchat-live-chat.zip` را بفرستید.
2. نام کاربری wordpress.org را در `Contributors` فایل `readme.txt` بگذارید.
3. بعد از تأیید، در GitHub مخزن: **Settings > Secrets and variables > Actions**
   - Secrets: `SVN_USERNAME` و `SVN_PASSWORD` (رمز SVN از پروفایل wordpress.org)
   - Variables: `WPORG_DEPLOY` = `true`
4. آیکن‌ها در `.wordpress-org/` هستند؛ بنر (`banner-772x250.png` و `banner-1544x500.png`) و اسکرین‌شات‌ها (`screenshot-1.png`، `screenshot-2.png`) را هم آنجا اضافه کنید.

## ترجمه
رشته‌های افزونه انگلیسی‌اند و ترجمه‌ی فارسی در `hubchat-live-chat/languages/` همراه افزونه است. بعد از انتشار در wordpress.org، ترجمه از translate.wordpress.org هم قابل مدیریت است.
