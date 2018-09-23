create database IF NOT EXISTS yeticave
    default CHARACTER SET utf8
    default COLLATE utf8_general_ci;
use yeticave;
create table IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name char(64) UNIQUE,
    email char(128) UNIQUE,
    password char(64),
    reg DATE,
    avatar BLOB,
    contact text
);
create table IF NOT EXISTS bets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reg DATETIME,
    amount FLOAT,
    user_id INT,
    lot_id INT
);
create table IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name TEXT
);
create table IF NOT EXISTS lots (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reg DATETIME,
    name CHAR(128) FULLTEXT,
    description TEXT FULLTEXT,
    img BLOB,
    finish DATETIME,
    amount_step FLOAT,
    user_id INT,
    winner_id INT,
    category_id INT
);