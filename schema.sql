create database IF NOT EXISTS `221665-yeticave`
    default CHARACTER SET utf8
    default COLLATE utf8_general_ci;
use `221665-yeticave`;
create table IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name varchar(64),
    email varchar(128) UNIQUE,
    password varchar(64),
    reg_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    avatar varchar(128),
    contact text
);
create table IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title varchar(128),
    promo_class varchar(128),
    UNIQUE(title(10))
);
create table IF NOT EXISTS lots (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reg_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    name varchar(128),
    description TEXT,
    img varchar(128),
    finish DATETIME,
    amount_step INT default 1000,
    start_amount INT,
    user_id INT,
    winner_id INT,
    category_id INT,
    FULLTEXT INDEX description_index(description),
    FULLTEXT INDEX name_index(name),
    FOREIGN KEY category(category_id) REFERENCES categories(id)
);
create table IF NOT EXISTS bets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reg_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    amount INT,
    user_id INT,
    lot_id INT,
    FOREIGN KEY user(user_id) REFERENCES users(id),
    FOREIGN KEY lot(lot_id) REFERENCES lots(id)
);
