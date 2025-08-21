CREATE DATABASE IF NOT EXISTS ecomdb;

CREATE USER IF NOT EXISTS 'appuser'@'%' IDENTIFIED BY 'P@55Word';
GRANT ALL PRIVILEGES ON ecomdb.* TO 'appuser'@'%';
FLUSH PRIVILEGES;

USE ecomdb;

DROP TABLE IF EXISTS products;

CREATE TABLE IF NOT EXISTS products (
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
