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

Create "t2.micro" EC2 Instance and Open port "" for PHP Application server

### Install PHP and its dependencies
```
sudo yum install -y httpd php php-mysqlnd php-mysql
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
sudo git checkout 01-Local-setup-Dev
```
#### Edit "index.php" and Mention your DB Details

```
// use when starting application locally
 $link = mysqli_connect('<AWS-Private-IP>', 'ecomuser', 'ecompassword', 'ecomdb');
```

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
sudo mysql < db-load.sql
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
#### Access Your Application in Browser
```
http://<Your-AWS-Public-IP>:80
```
