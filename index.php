<?php
require_once 'helpers.php';

$is_auth = rand(0, 1);
$user_name = 'Yuriy'; // укажите здесь ваше имя
$oneMin = 60;
$oneHour = 3600;
$oneDay = 86400;
$oneWeek = 604800;
$fiveWeeks = 2678400;
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

foreach ($cards as $key => &$card) {
    $date = generate_random_date($key);
    $card['date']['abs'] = $date;
    $card['date']['titleTime'] = date('d.m.Y H:i', strtotime($date));
    $diff = strtotime('now') - strtotime($card['date']['abs']);
   
    switch ($diff) {
        case ($diff < $oneHour):
            $num = ceil($diff / $oneMin);
            $res = get_noun_plural_form($num, 'минута', 'минуты', 'минут');
            $card['date']['rel'] = $num . ' ' . $res . ' назад';
            break;
        case ($oneHour <= $diff && $diff < $oneDay):
            $num = ceil($diff / $oneHour);
            $res = get_noun_plural_form($num, 'час', 'часа', 'часов');
            $card['date']['rel'] = $num . ' ' . $res . ' назад';
            break;
        case ($oneDay <= $diff && $diff < $oneWeek):
            $num = ceil($diff / $oneDay);
            $res = get_noun_plural_form($num, 'день', 'дня', 'дней');
            $card['date']['rel'] = $num . ' ' . $res . ' назад';
            break;
        case ($oneWeek <= $diff && $diff < $fiveWeeks):
            $num = ceil($diff / $oneWeek);
            $res = get_noun_plural_form($num, 'неделя', 'недели', 'недель');
            $card['date']['rel'] = $num . ' ' . $res . ' назад';
            break;
        case ($diff >= $fiveWeeks):
            $num = ceil($diff / $fiveWeeks);
            $res = get_noun_plural_form($num, 'месяц', 'месяца', 'месяцев');
            $card['date']['rel'] = $num . ' ' . $res . ' назад';
            break;
    }
}

$pageContent = include_template('main.php', ['cards' => $cards]);

$layoutContent = include_template('layout.php', ['content' => $pageContent, 'is_auth' => $is_auth, 'user_name' => 'Yuriy', 'title' => 'readme: популярное']);

print($layoutContent);