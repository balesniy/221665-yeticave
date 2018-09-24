INSERT INTO categories (title, promo_class)
VALUES  ("Доски и лыжи", 'boards'),
        ("Ботинки", 'boots'),
        ("Одежда", 'clothing'),
        ("Инструменты", 'tools'),
        ("Разное", 'other'),
        ("Крепления", 'attachment');
INSERT INTO users (name, email, password)
VALUES  ("Вася", 'boards@ya.ru', '123'),
        ("Петя", 'boots@ya.ru', '123');
INSERT INTO lots (name, category_id, start_amount, img, user_id, finish)
VALUES  ('2014 Rossignol District Snowboard', 1, 10999, 'img/lot-1.jpg', 1, DATE_ADD(NOW(), INTERVAL 2 DAY)),
        ('DC Ply Mens 2016/2017 Snowboard', 1, 159999, 'img/lot-2.jpg', 1, DATE_ADD(NOW(), INTERVAL 3 DAY)),
        ('Крепления Union Contact Pro 2015 года размер L/XL', 6, 8000, 'img/lot-3.jpg', 1, DATE_ADD(NOW(), INTERVAL 1 DA))),
        ('Ботинки для сноуборда DC Mutiny Charocal', 2, 10999, 'img/lot-4.jpg', 2, DATE_ADD(NOW(), INTERVAL 2 DAY)),
        ('Куртка для сноуборда DC Mutiny Charocal', 3, 7500, 'img/lot-5.jpg', 2, DATE_ADD(NOW(), INTERVAL 1 DAY)),
        ('Маска Oakley Canopy', 5, 5400, 'img/lot-6.jpg', 2, DATE_ADD(NOW(), INTERVAL 3 DAY));
INSERT INTO bets (amount, user_id, lot_id)
VALUES  (12000, 2, 1),
        (7000, 1, 6);
SELECT * FROM categories;
SELECT name, start_amount, img,
    (select max(amount) from bets where lot_id=lots.id) as price,
    (select count(*) from bets where lot_id=lots.id) as bets_count,
    categories.title
    FROM lots
    JOIN categories on category_id=categories.id
    WHERE finish > NOW()
    ORDER BY reg_date DESC;
SELECT name, categories.title FROM lots JOIN categories ON category_id=categories.id WHERE lots.id=1;
UPDATE lots SET name="Новое название" WHERE id=1;
SELECT * FROM bets WHERE lot_id=1 ORDER BY reg_date DESC;
