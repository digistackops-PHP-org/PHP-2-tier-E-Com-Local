# Application server Setup

### Create "t2.micro" EC2 Instance and Open port "80" for PHP Application server

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
# Step:1 ==> Install the Required packages
### Install PHP and its dependencies
```
sudo yum install -y httpd php php-mysqlnd
```
### Install Git
```
sudo yum install git -y
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

# Step:2 ==> Get the Code
#### To start this application first you can get the code using below url
##### Clone the Repo
```
sudo git clone https://github.com/digistackops-PHP-org/PHP-2-tier-E-Com-Local.git
cd PHP-2-tier-E-Com-Local
```
##### Switch to Local-setup Branch
```
sudo git checkout 02-Local-setup-Prod
```
## Setup your Application Database by executing "initdb.sql" script from Application-server

Step:1 ==> install "MYSQL-Client" for communicate with MYSQL Database
```
sudo yum update -y
sudo wget https://dev.mysql.com/get/mysql80-community-release-el9-1.noarch.rpm
sudo dnf install mysql80-community-release-el9-1.noarch.rpm -y
sudo rpm --import https://repo.mysql.com/RPM-GPG-KEY-mysql-2023
sudo dnf install mysql-community-client -y
```
Step:2 ==> Execute your "init.sql" script for your Application DB setup

```
mysql -h <DB-Prvate-IP> -udbadmin -pAdmin@123 < initdb.sql
```
why We use root user HERE => because we just launch MYSQL Db so no other user in DB
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
MYSQL_USER=appuser
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

