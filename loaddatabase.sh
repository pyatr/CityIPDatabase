service mysql start
unrar e dump.rar
mysql -u root -e "CREATE DATABASE IF NOT EXISTS ipcitydatabase"
mysql -u root -e "CREATE USER 'ipcityservice'@'%' IDENTIFIED BY '1234';"
mysql -u root ipcitydatabase < dump3.sql
mysql -u root -e "GRANT ALL ON ipcitydatabase.iprangelocation TO 'ipcityservice'@'%'";
mysql -u root -e "FLUSH PRIVILEGES";
