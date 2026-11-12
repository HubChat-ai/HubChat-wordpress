# HubChat WordPress Plugin

افزونه‌ی وردپرس برای نصب ویجت چت آنلاین هاب‌چت بدون ویرایش کد قالب. کد افزونه در پوشه‌ی `hubchat/` است (نام پوشه همان slug افزونه است).

## ساخت فایل نصب
با ساختن هر تگ (مثلاً `1.0.0`)، GitHub Actions فایل `hubchat.zip` را می‌سازد و در Releases مخزن می‌گذارد: https://github.com/HubChat-ai/hubchat-wordpress/releases

دستی:
```bash
zip -r hubchat.zip hubchat
```
فایل `hubchat.zip` را در Releases مخزن بگذارید؛ کاربر آن را از «افزونه‌ها > افزودن > بارگذاری افزونه» نصب می‌کند.

## امکانات
- وارد کردن کلید ویجت (یا چسباندن کل کد نصب)
- نمایش/عدم نمایش در موبایل، فقط برای کاربران واردشده، صفحه‌های مستثنا
- شورت‌کد `[hubchat_button text="..."]` و لینک‌های `#hubchat` برای باز کردن گفتگو
- فیلتر `hubchat_should_render` برای توسعه‌دهندگان
