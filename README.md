# caipren
Just another CAI website

## Setup
- Clone the repository
- Install XAMPP and start it
- Create a symlink of the repository inside /opt/lampp/htdocs
--> Now the system should at least start at localhost/cai/main.php
- Create a new database in phpMyAdmin called 6786_pernottamenti
- Import all tables from .sql
- Create a new user running this query:
    CREATE USER '6786_utentesql'@'localhost' IDENTIFIED VIA mysql_native_password USING '***';GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO '6786_utentesql'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
  or create a test user with username : 6786_utentesql, password databasecai, host: localhost, granting him data privileges.
--> Now the system should work with a messed up layout
- Download Bootstrap CSS and put it in: static/bootstrap/css/bootstrap.min.css
- Download Bootstrap JS sheet and put it in: static/bootstrap/js/bootstrap.min.js
- Download jQuery and put it in: static/javascript/jQuery/jquery-1.11.2.min.js
