<?php
require_once 'helpers.php';

$is_auth = rand(0, 1);
$user_name = 'Yuriy'; // укажите здесь ваше имя
define('MINUTE', 60);
define('HOUR', 60 * MINUTE);
define('DAY', 24 * HOUR);
define('WEEK', 7 * DAY);
define('FIVEWEEKS', 5 * WEEK);

$con = mysqli_connect('localhost', 'root', '', 'readme');

 if (!$con) {
       die('Ошибка соединения с сервером MySQL: ' . mysqli_connect_error());
  } else {
        $query_types = 'SELECT * FROM content_types';
        $query_posts = 'SELECT posts.*, content, users.login, class_name, avatar,' .
                            '(SELECT COUNT(likes.post_id) FROM likes WHERE likes.post_id = posts.id) AS likes_count' . 
                        ' FROM posts' .
                        ' JOIN users ON posts.user_id = users.id' .
                        ' JOIN content_types ON posts.content_type_id = content_types.id' .
                        ' ORDER BY likes_count DESC;';

        $types = mysqli_query($con, $query_types);
        $posts = mysqli_query($con, $query_posts);

        if (!$types) {
            die('Ошибка получения данных: ' . mysqli_error($con));
        } else {
            $types = mysqli_fetch_all($types);
        }

        if (!$posts) {
            die('Ошибка получения данных: ' . mysqli_error($con));
        } else {
            $posts = mysqli_fetch_all($posts, MYSQLI_ASSOC);
        }
  }

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

foreach ($posts as $key => &$card) {
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

$pageContent = include_template('main.php', ['posts' => $posts, 'types' => $types]);

$layoutContent = include_template('layout.php', ['content' => $pageContent, 'is_auth' => $is_auth, 'user_name' => 'Yuriy', 'title' => 'readme: популярное']);

print($layoutContent);