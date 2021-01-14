#!/bin/bash
docker exec -i lemp_mariadb mysql -uroot -proot_db --database=db_banbung < ../backup/db_banbung.sql