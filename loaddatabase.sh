service mysql start
unrar e dump.rar
mysql -u root -e "CREATE USER 'ipcityservice'@'%' IDENTIFIED BY '1234'"
mysql -u root < dump3.sql
mysql -u root -e "GRANT ALL ON ipcitydatabase.* TO 'ipcityservice'@'%'"
mysql -u root -e "FLUSH PRIVILEGES"
