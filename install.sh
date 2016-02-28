sudo composer install
sudo mkdir ./var/media/
sudo chmod -R 777 ./app/config/parameters.yml
sudo chmod -R 777 ./var/logs
sudo chmod -R 777 ./var/cache
sudo php bin/console doctrine:schema:update --force
php bin/console fixture:generateData
sudo chmod -R 777 ./var

#sudo php bin/console cache:clear --no-debug
#sudo php bin/console assets:install --symlink --relative
#sudo php bin/console assetic:dump --no-debug
#sudo php bin/console assetic:watch