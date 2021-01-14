echo "Installing..."
cp .env.dist .env
docker-compose up -d
docker-compose run --rm php php init --env=Production --overwrite=All
docker-compose run --rm php composer install
docker exec -i lemp_mariadb mysql -uroot -proot_db --database=db_banbung < ./docker/mysql/backup/db_banbung.sql