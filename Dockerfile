FROM php:8.2-apache

#COPY / /var/www/html
# Ici on copie tout un dossier, ce qui pose problème car on doit se limiter au strict minimum
# Si on a des infos sensibles dans le dossier, elles seront copiées dans le container
# Il faudrait plutôt copier uniquement les fichiers nécessaires
COPY / /var/www/html/
COPY src/ /var/www/html/src/

# RUN chmod -R 777 /var/www/html
# Très grosse faille de sécurité ici. On doit limiter les droits au strict minimum, or ici il est
# admin, ce qui veut dire que si quelqu'un arrive a rentrer dans le container, il peut modifier
# tout le contenu web
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

#EXPOSE 80
# On expose un port pour rien, inutile
