FROM php:8.3-apache

# PDO MySQL draiver (fileinfo ja mbstring on ametlikus PHP imidžis juba olemas)
RUN docker-php-ext-install pdo_mysql

# .htaccess failid (uploads/cv, inc, logod) peavad kehtima
RUN a2enmod rewrite \
 && printf 'DocumentRoot /var/www/html/jobportal\n<Directory /var/www/html/jobportal>\n    AllowOverride All\n    Require all granted\n</Directory>\n' > /etc/apache2/conf-available/jobportal.conf \
 && a2enconf jobportal

# Arenduskeskkonna PHP seaded: vead nähtavale, suurem üleslaadimise piir
RUN { echo 'display_errors=On'; echo 'error_reporting=E_ALL'; echo 'upload_max_filesize=8M'; echo 'post_max_size=10M'; } > /usr/local/etc/php/conf.d/jobportal.ini
