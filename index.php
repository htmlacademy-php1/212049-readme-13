<?php
require_once 'helpers.php';

$is_auth = rand(0, 1);
$user_name = 'Yuriy'; // укажите здесь ваше имя
$cards = [
    [
        'quote' => 'Цитата', 
        'type' => 'post-quote', 
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих', 
        'name' => 'Лариса',  
        'avatar' => 'userpic-larisa-small.jpg',
    ],
    [
        'quote' => 'Игра престолов', 
        'type' => 'post-text', 
        'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!', 
        'name' => 'Владик',  
        'avatar' => 'userpic.jpg',
    ],
    [
        'quote' => 'Наконец, обработал фотки!', 
        'type' => 'post-photo', 'content' => 'rock-medium.jpg', 
        'name' => 'Виктор',  
        'avatar' => 'userpic-mark.jpg',
    ],
    [
        'quote' => 'Моя мечта', 
        'type' => 'post-photo', 
        'content' => 'coast-medium.jpg', 
        'name' => 'Лариса',  
        'avatar' => 'userpic-larisa-small.jpg',
    ],
    [
        'quote' => 'Лучшие курсы', 
        'type' => 'post-link', 
        'content' => 'www.htmlacademy.ru ', 
        'name' => 'Владик',  
        'avatar' => 'userpic.jpg',
    ],
];

function truncateText($text, $maxLength = 300) {
    $counter = 0;
    $tempArr = [];

    if (mb_strlen($text) <= $maxLength) {
        return [$text, false];
    }

    $words = explode(' ', $text);

    foreach ($words as $word) {
        $counter += mb_strlen($word);

        if ($counter >= $maxLength) {
             break;
        }
        $tempArr[] = $word;
    }

    $truncatedText = implode(' ', $tempArr);

    return  [$truncatedText, true];
}

$pageContent = include_template('main.php', ['cards' => $cards]);

$layoutContent = include_template('layout.php', ['content' => $pageContent, 'is_auth' => $is_auth, 'user_name' => 'Yuriy', 'title' => 'readme: популярное']);

print($layoutContent);