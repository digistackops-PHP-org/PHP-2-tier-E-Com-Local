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
## Setup MYSQL DB

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
SELECT user, host FROM mysql.ecomdb WHERE user='ecomuser';
```

Check the Grants of the "appuser" in DB

```
SHOW GRANTS FOR 'ecomuser'@'%';
```

# Lod Dummy Data to our Application

HERE we have Dummy data in the file "db-load.sql"
```
sudo mysql < db-load-script.sql
```

# Application server Setup

Create "t2.micro" EC2 Instance and Open port "" for PHP Application server

### Install Node
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

#### Access Your Application in Browser
```
http://<Your-AWS-Public-IP>:80
```
