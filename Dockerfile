# استخدم صورة رسمية لـ PHP مع خادم Apache
FROM php:8.2-apache

# نسخ ملفات المشروع إلى مجلد السيرفر داخل الحاوية
COPY . /var/www/html/

# تفعيل دعم إعادة كتابة الروابط (اختياري)
RUN a2enmod rewrite

# تعيين صلاحيات مناسبة للمجلد الصوتي
RUN chown -R www-data:www-data /var/www/html/audio

# تعيين مجلد العمل
WORKDIR /var/www/html

# فتح المنفذ 80
EXPOSE 80
