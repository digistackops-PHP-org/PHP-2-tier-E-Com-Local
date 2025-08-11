# Database Setup
Create "t2.micro" EC2 Instance and open port "3306" for DB 

## Install MYSQL DB
```
sudo yum update -y
sudo wget https://dev.mysql.com/get/mysql80-community-release-el9-1.noarch.rpm
sudo dnf install mysql80-community-release-el9-1.noarch.rpm -y
sudo rpm --import https://repo.mysql.com/RPM-GPG-KEY-mysql-2023
sudo dnf install mysql-community-client -y
sudo dnf install mysql-community-server -y
sudo systemctl start mysqld
sudo systemctl enable mysqld
sudo systemctl status mysqld
```

## Setup MYSQL DB

#### Allow any Host connect to DB
```
sudo vi /etc/my.cnf
```
ADD these Under [mysqld]
```
bind-address = 0.0.0.0
```
Restart MYSQL DB
```
sudo systemctl restart mysqld
```

Get your temporary root Password
```
sudo grep 'temporary password' /var/log/mysqld.log
```
Setup your root Password
```
sudo mysql_secure_installation
```
Login to your MYSQL
```
mysql -u root -p
```
Test it is working or Not
```
SELECT VERSION();
```

## Create our Application DB 'user'
```
CREATE DATABASE ecomdb;
```
Check the DB created or Not
```
SHOW DATABASES LIKE 'ecomdb';
```
## Create one system User for our Application in DB
These user can login to DB to do Tasks
```
CREATE USER '<user-name>'@'Host-IP' IDENTIFIED BY 'Password-HERE';

GRANT ALL PRIVILEGES ON <DB-Name>.* TO '<user-name>'@'Host-IP';

FLUSH PRIVILEGES;
```

```
CREATE USER 'ecomuser'@'%' IDENTIFIED BY 'P@55Word';
GRANT ALL PRIVILEGES ON ecomdb.* TO 'ecomuser'@'%';
FLUSH PRIVILEGES;
```
#### HERE "%" => means any Host will connect

Check the Permissions of the "appuser" in DB

```
SELECT user, host FROM mysql.user WHERE user='ecomuser';
```

Check the Grants of the "appuser" in DB

```
SHOW GRANTS FOR 'ecomuser'@'%';
```

# Application server Setup

Create "t2.micro" EC2 Instance and Open port "80" for PHP Application server

## Note ==> HERE in our PROD Branch Code we alredy Edit these Code in "index.php", so no need to Change any thing HERE

### Good-To-Know
```
As of Now We Hardcode the DB Credentials in "index.php"

In "index.php" we mention our DB credentials Manually and push it to GIT. 
We have 2 problems HERE
1. These code is not eligibile for CICD, we manually enter the Credentials
2. It expose our Credentials to everyone

Which is Not recommended in PROD as well

So we need to Pass our DB Credentials as Environment Variables
For that we need to Change our Code 

Open your "index.php" Edit MYSQL configuration

You see like these

----
// use when starting application locally
$link = mysqli_connect('172.20.1.101', 'ecomuser', 'ecompassword', 'ecomdb');
---


So we need to Edit these code as per Environment Variables

----
// Fetch database connection details directly from environment variables
$dbHost = getenv('MYSQL_HOST');
$dbUser = getenv('MYSQL_USER');
$dbPassword = getenv('MYSQL_PASSWORD');
$dbName = getenv('MYSQL_DATABASE');

// Attempt to connect to the database
$link = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);
----

```

### Install PHP and its dependencies
```
sudo yum install -y httpd php php-mysqlnd
```
#### By default htpd web server servers index.html page, so we need to show the page "index.php", for that we need to change the configuration in from "index.html" to "index.php" in the file /etc/httpd/conf/httpd.conf

```
sudo sed -i 's/index.html/index.php/g' /etc/httpd/conf/httpd.conf
```
Start httpd webserver
```
sudo systemctl start httpd
sudo systemctl enable httpd
sudo systemctl status httpd
```

### Install Git
```
sudo yum install git -y
```
#### To start this application first you can get the code using below url
##### Clone the Repo
```
sudo git clone https://github.com/techizone-Small-Project-org/PHP-2-tier-UMS-App.git
cd PHP-2-tier-UMS-App
```
##### Switch to Local-setup Branch
```
sudo git checkout 02-Local-setup-Prod
```
#### Cop ythe Content to our HTTPD webserver Directory

```
sudo cp -r * /var/www/html/
```
## Good to Know
### HERE these PHP code didnt accept passing DB credentials through "export" command
```
HERE Issue is
your PHP code is fine for reading environment variables via getenv(), 
but in Apache/HTTPD passing environment variables via "export" commands is in your shell do not automatically become available to PHP unless you pass them into the web server’s environment.
	
When PHP runs inside Apache, it only sees environment variables defined in:

		1. Apache config (SetEnv)
		2. ".env" file loaded by code {recommended easy to containerize}
		3. mention Environment variables in https service file

```
#### Pass our DB Creentials as .env file, Place this .env in the same directory as index.php:
```
sudo vim /var/www/html/.env
```

```
MYSQL_HOST=<AWS-DB-Private-IP>
MYSQL_USER=ecomuser
MYSQL_PASSWORD=P@55Word
MYSQL_DATABASE=ecomdb
```

#### Restart our Web server HTTPD

```
sudo systemctl restart httpd
```

## Access Your Application in Browser
```
http://<Your-AWS-Public-IP>:80
```
<img width="1193" height="506" alt="image" src="https://github.com/user-attachments/assets/ec9a7b94-bb45-420d-8c66-af7cabe2f83a" />

# Load Dummy Data to our Application

## 2 Ways we can Load the DATA to our DB
```
Way-1 ==> Login to DB and Execute these Script
Way-2 ==> We can Load the Data from Another Serevr {Recommended}
             These way in Real-Time product info Loaded by End-users or other Team from their Portal
```
### Way-1 ==> Login to your MYSQL DB server
Execute these Steps to create a file "db-load.sql"
```
cat > db-load.sql <<EOF
USE ecomdb;
CREATE TABLE products (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  Name varchar(255) DEFAULT NULL,
  Price decimal(10,2) DEFAULT NULL,
  ImageUrl varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
);
INSERT INTO products (Name,Price,ImageUrl) VALUES 
  ("Laptop", "100", "c-1.png"),
  ("Drone", "200", "c-2.png"),
  ("VR", "300", "c-3.png"),
  ("Tablet", "5", "c-5.png"),
  ("Watch", "90", "c-6.png"),
  ("Phone", "80", "c-8.png"),
  ("Laptop", "150", "c-4.png");
EOF
```
Load these DATA into DB

```
mysql -u ecomuser -p ecomdb < /path/to/db-load.sql
```

### Way-2 ==> Login to your other server say example catalouge server

Prerequisites
```
1. The MySQL client (mysql) must be installed on your application server.
2. You must know the MySQL username, password, host, and target database.
3. You must have network access (firewall/security groups) allowing the app server to connect to the MySQL server (172.31.26.26) on port 3306.
```
#### Install MYSQL Client
```
sudo yum update -y
sudo wget https://dev.mysql.com/get/mysql80-community-release-el9-1.noarch.rpm
sudo dnf install mysql80-community-release-el9-1.noarch.rpm -y
sudo rpm --import https://repo.mysql.com/RPM-GPG-KEY-mysql-2023
sudo dnf install mysql-community-client -y
```

#### Load DATA from Catalogue Server to Db

```
mysql -h <DB-Private-IP> -u ecomuser -p ecomdb < /path/to/db-load.sql
```


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
