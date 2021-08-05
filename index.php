<?php
require_once 'helpers.php';

$is_auth = rand(0, 1);
$user_name = 'Yuriy'; // укажите здесь ваше имя
define('MINUTE', 60);
define('HOUR', 60 * MINUTE);
define('DAY', 24 * HOUR);
define('WEEK', 7 * DAY);
define('FIVEWEEKS', 5 * WEEK);

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
   
    switch (true) {
        case ($diff < HOUR):
            $num = ceil($diff / MINUTE);
            $res = get_noun_plural_form($num, 'минута', 'минуты', 'минут');
            $card['date']['rel'] = $num . ' ' . $res . ' назад';
            break;
        case ($diff >= HOUR  && $diff < DAY):
            $num = ceil($diff / HOUR);
            $res = get_noun_plural_form($num, 'час', 'часа', 'часов');
            $card['date']['rel'] = $num . ' ' . $res . ' назад';
            break;
        case ($diff >= DAY && $diff < WEEK):
            $num = ceil($diff / DAY);
            $res = get_noun_plural_form($num, 'день', 'дня', 'дней');
            $card['date']['rel'] = $num . ' ' . $res . ' назад';
            break;
        case ($diff >= WEEK&& $diff < FIVEWEEKS):
            $num = ceil($diff / WEEK);
            $res = get_noun_plural_form($num, 'неделя', 'недели', 'недель');
            $card['date']['rel'] = $num . ' ' . $res . ' назад';
            break;
        case ($diff >= FIVEWEEKS):
            $num = ceil($diff / FIVEWEEKS);
            $res = get_noun_plural_form($num, 'месяц', 'месяца', 'месяцев');
            $card['date']['rel'] = $num . ' ' . $res . ' назад';
            break;
    }
}

$pageContent = include_template('main.php', ['cards' => $cards]);

$layoutContent = include_template('layout.php', ['content' => $pageContent, 'is_auth' => $is_auth, 'user_name' => 'Yuriy', 'title' => 'readme: популярное']);

print($layoutContent);