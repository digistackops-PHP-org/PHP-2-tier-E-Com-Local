# Check data saved in DB or Not

Login to your MYSQL
```
mysql -u root -p
```
Show the List of DBs
```
SHOW DATABASES;
```
Switch to your "user" DB
```
use ecomdb;
```

See the Tables under "user" DB
```
SHOW TABLES;
```
<img width="186" height="107" alt="image" src="https://github.com/user-attachments/assets/801fc988-b701-4fb4-ae2a-45546170b194" />

To see Data stored under "user" DB or Not
```
SELECT * FROM products;
```
<img width="320" height="210" alt="image" src="https://github.com/user-attachments/assets/9bc44c3c-8650-4dd5-8741-4d9efea15f6c" />


## Access Your Application in Browser
```
http://<Your-AWS-Public-IP>:80
```
