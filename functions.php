<?php
/**
 * Проверяет переданную строку на допустимую длинну, если превышает лимит обрезает и возвращает строку, если меньше лимита воозвращет без изменений
 *
 * @param string $text Текст
 * @param int $maxLength Максимально допустимая длинна текста
 * 
 * @return array
 */
function truncateText(string $text, int $maxLength = 300): array {
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

/**
 * Возвращает модифицированную дату
 *
 * Примеры использования:
 * getModDate('2021-08-19 22:21:05'); // Array ([titleDate] => 19.08.2021 22:21, [rel] => 6 дней назад)
 *
 * @param string $date Дата в абсолютном формате
 * 
 * @return array
 */
function getModDate(string $date): array {
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

/**
 * Устанавливает новое соединение с сервером MySQL
 * 
 * @return mysqli Object $con Обьект mysqli
 */
function masqliConnect(): object {
    $con = mysqli_connect('localhost', 'root', '', 'readme');

    if (!$con) {
       die('Ошибка соединения с сервером MySQL: ' . mysqli_connect_error());
    }

    mysqli_set_charset($con, 'utf8');

    return $con;
}

/**
 * Возвращает таблицу типов постов из базы данных
 *
 * @param mysqli Object $con Обьект mysqli
 * 
 * @return array
 */
function getPostTypes(object $con): array {
    $query_types = 'SELECT * FROM content_types';

    $stmt = mysqli_prepare($con, $query_types);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $types = mysqli_fetch_all($res, MYSQLI_ASSOC);

    return $types;
}

/**
 * Возвращает обьединенную таблицу постов, пользователей и типа постов из базы данных
 *
 * @param $type_id тип поста
 * @param string $cardsOnPageAll максимальное количество постов выводимых на странице
 * @param mysqli Object $con Обьект mysqli
 * 
 * @return array
 */
function getPosts(int $type_id, bool $cardsOnPageAll, object $con): array {
    $cardsOnPageMax = 9;
    $cardsOnPageDef = 6;
    $post_limit = 'LIMIT ' . $cardsOnPageDef;

    if ($cardsOnPageAll) {
        $post_limit = "LIMIT $cardsOnPageMax";
    }

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

    if ($type_id === 0 || $cardsOnPageAll) {
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
        return $posts;
}

/**
 * Возвращает обьединенную таблицу: пост, пользователь и тип поста из базы данных
 *
 * @param $post_id пост
 * @param mysqli Object $con Обьект mysqli
 * 
 * @return array
 */
function getPost(int $post_id, object $con) {
    $query_post = 'SELECT posts.*, users.login AS author, users.avatar, content_types.class_name AS post_type,' .
                '(SELECT COUNT(likes.post_id) FROM likes WHERE likes.post_id = posts.id) AS likes_count' . 
                ' FROM posts' .
                ' JOIN users ON posts.user_id = users.id' .
                ' JOIN content_types ON posts.content_type_id = content_types.id' .
                ' WHERE posts.id = ?;';

    $stmt = mysqli_prepare($con, $query_post);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $post = mysqli_fetch_assoc($res);

    if (empty($post)) {
        http_response_code(404);
        exit();
    }
    return $post;
}