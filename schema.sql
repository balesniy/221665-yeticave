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
    avatar TEXT,
    contact text
);
create table IF NOT EXISTS bets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reg DATETIME,
    amount FLOAT,
    user_id INT,
    FOREIGN KEY user(user_id) REFERENCES users(id),
    lot_id INT
    FOREIGN KEY lot(lot_id) REFERENCES lots(id)
);
create table IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name TEXT
);
create table IF NOT EXISTS lots (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reg DATETIME,
    name CHAR(128),
    description TEXT,
    FULLTEXT INDEX description(description),
    img TEXT,
    finish DATETIME,
    amount_step FLOAT,
    user_id INT,
    winner_id INT,
    category_id INT,
    FOREIGN KEY category(category_id) REFERENCES categories(id)
);