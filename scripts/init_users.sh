#!/bin/bash
set -e

mysql -uroot -p$MYSQL_ROOT_PASSWORD <<EOSQL
CREATE USER 'user'@'%' IDENTIFIED BY '$USER_PASSWORD';
GRANT SELECT, INSERT ON securitydb.* TO 'user'@'%';

CREATE USER 'admin'@'%' IDENTIFIED BY '$ADMIN_PASSWORD';
GRANT INSERT, UPDATE, SELECT, DELETE ON securitydb.* TO 'admin'@'%';
EOSQL
# I don't think anything needs to be escaped in this heredoc.