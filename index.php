<?php
$is_auth = (bool) rand(0, 1);
date_default_timezone_set('Europe/Moscow');
$user_name = 'Алексей'; // укажите здесь ваше имя
$title = 'Главная';
$user_avatar = 'img/user.jpg';
$categories = [
    [
        'title' => "Доски и лыжи",
        'promo_class' => 'boards'
    ], [
        'title' => "Крепления",
        'promo_class' => 'attachment'
    ], [
        'title' => "Ботинки",
        'promo_class' => 'boots'
    ], [
        'title' => "Одежда",
        'promo_class' => 'clothing'
    ], [
        'title' => "Инструменты",
        'promo_class' => 'tools'
    ], [
        'title' => "Разное",
        'promo_class' => 'other'
        ]
];
$lots = [
    [
        'title' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 10999,
        'picture' => 'img/lot-1.jpg'
    ],
    [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'picture' => 'img/lot-2.jpg'
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'picture' => 'img/lot-3.jpg'
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'price' => 10999,
        'picture' => 'img/lot-4.jpg'
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'price' => 7500,
        'picture' => 'img/lot-5.jpg'
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'picture' => 'img/lot-6.jpg'
    ]
];

require_once 'functions.php';
$main_page = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);
$layout = include_template('layout.php', [
    'content' => $main_page,
    'categories' => $categories,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'user_avatar' => $user_avatar
]);
print($layout);
?>
