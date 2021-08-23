<?php
require_once 'helpers.php';
require_once 'mysqli/mysqli-connect.php';
date_default_timezone_set('Europe/Moscow');

$is_auth = rand(0, 1);
$user_name = 'Yuriy'; // укажите здесь ваше имя
$cardsOnPageAll = (isset($_GET['cardsOnPageAll'])) ? true : false;
$cardsOnPageMax = 9;
$cardsOnPageDef = 6;
$post_limit = "LIMIT $cardsOnPageDef";
define('MINUTE', 60);
define('HOUR', 60 * MINUTE);
define('DAY', 24 * HOUR);
define('WEEK', 7 * DAY);
define('FIVEWEEKS', 5 * WEEK);

$type_id = isset($_GET['type_id']) ? (int) $_GET['type_id'] : null;

if ($cardsOnPageAll) {
    $post_limit = "LIMIT $cardsOnPageMax";
}

$query_types = 'SELECT * FROM content_types';
$query_posts_def = 'SELECT users.login AS author, content_types.*, posts.*, users.avatar,' .
                    '(SELECT COUNT(likes.post_id) FROM likes WHERE likes.post_id = posts.id) AS likes_count' . 
                ' FROM posts' .
                ' JOIN users ON posts.user_id = users.id' .
                ' JOIN content_types ON posts.content_type_id = content_types.id' .
                ' ORDER BY likes_count DESC' .
                ' ' . $post_limit;
$query_posts = 'SELECT users.login AS author, content_types.*, posts.*, users.avatar,' .
                    '(SELECT COUNT(likes.post_id) FROM likes WHERE likes.post_id = posts.id) AS likes_count' . 
                ' FROM posts' .
                ' JOIN users ON posts.user_id = users.id' .
                ' JOIN content_types ON posts.content_type_id = content_types.id' .
                ' WHERE posts.content_type_id = ?' .
                ' ORDER BY likes_count DESC';

$stmt = mysqli_prepare($con, $query_types);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res) {
    die('Ошибка получения данных: ' . mysqli_error($con));
}

$types = mysqli_fetch_all($res, MYSQLI_ASSOC);

if ($type_id === null || $cardsOnPageAll) {
    $res = mysqli_query($con, $query_posts_def);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $posts = mysqli_fetch_all($res, MYSQLI_ASSOC);
} else {
    $stmt = mysqli_prepare($con, $query_posts);
    mysqli_stmt_bind_param($stmt, 'i', $type_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $posts = mysqli_fetch_all($res, MYSQLI_ASSOC);
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

function getModDate($date) {
    $modDate = [];

    $modDate['titleDate'] = date('d.m.Y H:i', strtotime($date));
    $diff = strtotime('now') - strtotime($date);

    switch (true) {
        case ($diff < HOUR):
            $num = ceil($diff / MINUTE);
            $res = get_noun_plural_form($num, 'минута', 'минуты', 'минут');
            $modDate['rel'] = $num . ' ' . $res . ' назад';
            break;
        case ($diff >= HOUR  && $diff < DAY):
            $num = ceil($diff / HOUR);
            $res = get_noun_plural_form($num, 'час', 'часа', 'часов');
            $modDate['rel'] = $num . ' ' . $res . ' назад';
            break;
        case ($diff >= DAY && $diff < WEEK):
            $num = ceil($diff / DAY);
            $res = get_noun_plural_form($num, 'день', 'дня', 'дней');
            $modDate['rel'] = $num . ' ' . $res . ' назад';
            break;
        case ($diff >= WEEK&& $diff < FIVEWEEKS):
            $num = ceil($diff / WEEK);
            $res = get_noun_plural_form($num, 'неделя', 'недели', 'недель');
            $modDate['rel'] = $num . ' ' . $res . ' назад';
            break;
        case ($diff >= FIVEWEEKS):
            $num = ceil($diff / FIVEWEEKS);
            $res = get_noun_plural_form($num, 'месяц', 'месяца', 'месяцев');
            $modDate['rel'] = $num . ' ' . $res . ' назад';
            break;
    }

    return $modDate;
}

$pageContent = include_template('main.php', ['posts' => $posts, 'types' => $types, 'type_id' => $type_id, 'cardsOnPageAll' => $cardsOnPageAll]);

$layoutContent = include_template('layout.php', ['content' => $pageContent, 'is_auth' => $is_auth, 'user_name' => 'Yuriy', 'title' => 'readme: популярное']);

print($layoutContent);