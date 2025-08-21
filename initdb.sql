CREATE DATABASE IF NOT EXISTS employeedb;

CREATE USER IF NOT EXISTS 'appuser'@'%' IDENTIFIED BY 'P@55Word';
GRANT ALL PRIVILEGES ON employeedb.* TO 'appuser'@'%';
FLUSH PRIVILEGES;

USE employeedb;

CREATE TABLE IF NOT EXISTS user (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        email VARCHAR(255),
        Address TEXT,
        phonenumber VARCHAR(255),
        password VARCHAR(255)
);
