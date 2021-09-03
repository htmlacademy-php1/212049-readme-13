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
function mysqliConnect(): object {
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
 * Вставляет данные ($tags) в таблицу hashtags БД readme
 *
 * @param mysqli Object $con Обьект mysqli
 * @param array $tags простой массив значения которого теги переданные через форму публикации поста
 * 
 * @return void
 */
function insertTagsToDatabase($con, $tags): void {
    $query = 'INSERT INTO hashtags(hashtag) VALUE (?)';

    $stmt = mysqli_prepare($con, $query);
    foreach ($tags as $key => $tag) {
        mysqli_stmt_bind_param($stmt, 's', $tag);
        mysqli_stmt_execute($stmt);
    }
}

/**
 * Вставляет данные переданные из формы в таблицу posts БД readme
 *
 * @param mysqli Object $con Обьект mysqli
 * @param string $postType тип контента
 * @param string $filePath путь к файлу сохраненному в публичной папке проекта uploads
 * 
 * @return void
 */
function insertPostToDatabase($con, $postType, $filePath): void {
    list(,$title, $content, $quote_author) = array_values($_POST);
    $queries = [
        'photo' => 'INSERT INTO posts(title, content, quote_author, image, video, link, num_of_views, user_id, content_type_id) VALUE (?, "", "anonymous", ?, "", "", 10, 1, 1);',
        'video' => 'INSERT INTO posts(title, content, quote_author, image, video, link, num_of_views, user_id, content_type_id) VALUE (?, "", "anonymous", "", ?, "", 11, 1, 2);',
        'text' => 'INSERT INTO posts(title, content, quote_author, image, video, link, num_of_views, user_id, content_type_id) VALUE (?, ?, "anonymous", "", "", "", 12, 1, 3);',
        'quote' => 'INSERT INTO posts(title, content, quote_author, image, video, link, num_of_views, user_id, content_type_id) VALUE (?, ?, ?, "", "", "", 13, 1, 4);',
        'link' => 'INSERT INTO posts(title, content, quote_author, image, video, link, num_of_views, user_id, content_type_id) VALUE (?, "", "anonymous", "", "", ?, 14, 1, 5);',
    ];

    foreach ($queries as $type => $query) {
        if ($postType === $type) {
            $postQuery = $query;
        }
    }

    $stmt = mysqli_prepare($con, $postQuery);

    if ($postType === 'quote') {
        mysqli_stmt_bind_param($stmt, 'sss', $title, $content, $quote_author);
    } elseif ($postType === 'photo' || $postType === 'video') {
        mysqli_stmt_bind_param($stmt, 'ss', $title, $filePath);
    } else {
         mysqli_stmt_bind_param($stmt, 'ss', $title, $content);
    }
    
    if(@!mysqli_stmt_execute($stmt)) {
        echo 'Ошибка загрузки поста в базу данных ' . mysqli_error($con);
        die;
    } else {
        $postId = mysqli_insert_id($con);
        //header('Location: post.php?post_id=' . $postId, true, 303);
    }
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
function getPost(int $post_id, object $con): array {
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

/**
 * Проверяет корректность URL
 *
 * @param string string $name значение атрибута name поля формы
 * 
 * @return string
 */
function validateUrl(string $name): string {
    if (empty($_POST[$name])) {
        return '';
    }
    if (!filter_input(INPUT_POST, $name, FILTER_VALIDATE_URL)) {
        return 'Введите корректную ссылку из интернета';
    }
    return '';
}

/**
 * Проверяет корректность введенного URL и существование видео по указанной ссылке
 *
 * @param string $name значение атрибута name поля формы
 * 
 * @return string
 */
function validateYoutubeUrl(string $name): string {
    if (!filter_input(INPUT_POST, $name, FILTER_VALIDATE_URL)) {
        return 'Введите корректную ссылку из интернета';
    }
    if ($error = check_youtube_url($_POST[$name])) {
       if ($error === true) {
           return '';
       } else {
            return $error;
       }
    }
    return '';
}

/**
 * Проверяет введен один или более хештегов, проверяет начинается хештег с #, если нет возвращает текст ошибки
 *
 * @param string $name значение атрибута name поля формы
 * 
 * @return string
 */
function validateHashtag(string $name): string {
    if (empty($_POST[$name])) {
        return '';
    }

    if(!preg_match_all('/#[^#\s]+/', $_POST[$name])) {
        return 'Введите корректный хештег(и)';
    }
    return '';
}

/**
 * Проверяет тип и размер загруженного файла, если некорректный возвращает текст ошибки
 * 
 * @return string
 */
function validateImage(): string {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $fileName = $_FILES['userpic-file-photo']['tmp_name'];
    $fileSize = $_FILES['userpic-file-photo']['size'];

    $fileType = finfo_file($finfo, $fileName);

    if ($fileType !== 'image/jpeg' && $fileType !== 'image/jpg' && $fileType !== 'image/png' && $fileType !== 'image/gif') {
        return 'Загрузите картинку в одном из следующих форматов - jpg/png/gif ';
    }

    if ($fileSize > 200000) {
        return 'Максимальный размер файла 200K';
    }
    return '';
}

/**
 * Осуществляет валидацию данных из формы
 * 
 * @return array
 */
function validateForm($rules, $required) {
    $errors = [];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }

        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = 'Поле должно быть заполнено';
        }
    }

    $errors = array_filter($errors);
    return $errors;
}

/**
 * Меняет ключи из массива $errors на значения из массива $keys
 *
 * @param array $errors массив с ошибками после валидации данных из формы
 * @param array $keys массив с ключами
 * 
 * @return array
 */
function modifyErrors(array $errors, array $keys): array {
    $modErrors = [];

   foreach ($errors as $key => $value) {
       if (isset($keys[$key])) {
           $modErrors[$keys[$key]] = $value;
       }
   }

   return $modErrors;
}

/**
 * Возвращает значение по ключу $name из массива $_POST
 *
 * @param string $name значение атрибута name поля формы
 * 
 * @return string
 */
function getPostValue($name): string {
    return $_POST[$name] ?? '';
}

/**
 * Перемещает файл из временного хранилища в публичную папку проекта
 * 
 * @return void
 */
function moveLoadedFile(): void {
    $ext = explode('/', $_FILES['userpic-file-photo']['type']);
    $file = uniqid() . '.' . $ext[1];
    $path = __DIR__ . '/uploads/' . $file;
    global $filePath;
    $filePath = $file;

    move_uploaded_file($_FILES['userpic-file-photo']['tmp_name'], $path);
}

/**
 * Скачивает файл по ссылке и помещает в публичную папку проекта
 * 
 * @return string
 */
function downloadImageFile(): string {
    $link = $_POST['photo-url'];
    $error = '';
    
    if(@!$fileContent = file_get_contents($link)) {
        return 'Ошибка загрузки файла по сети';
    }
    preg_match('/jpeg|jpg|png|gif/', $link, $match);
    $ext = $match[0];
    $dir = __DIR__ . '/uploads/';
    $file = uniqid() . '.' . $ext;
    $path = $dir . $file;
    global $filePath;
    $filePath = $file;

    file_put_contents($path, $fileContent);

    return '';
}

/**
 * Разбивает строку тегов на отдельные слова 
 * 
 * @param string $tagIndex ключ 
 * 
 * @return array
 */
function getTags($tagIndex): array {
    preg_match_all('/#[^#\s]+/', $_POST[$tagIndex], $match);
    $tags = $match[0];

    return $tags;
}