FROM liveizy/auth-service:latest
#copy source files and run composer
COPY . $APP_HOME
#
## install all PHP dependencies
RUN composer install --no-interaction
#
##change ownership of our applications
RUN chown -R www-data:www-data $APP_HOME
COPY ./bin/run-scheduler.sh /usr/local/bin/run-scheduler.sh
RUN chmod +x /usr/local/bin/run-scheduler.sh
