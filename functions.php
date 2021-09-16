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
 * @param string $postId id последнего загруженного в БД поста
 * 
 * @return void
 */
function insertTagsToDatabase(object $con, array $tags, int $postId): void {
    $query = 'INSERT INTO hashtags(hashtag) VALUE (?)';

    $stmt = mysqli_prepare($con, $query);
    foreach ($tags as $tag) {
        mysqli_stmt_bind_param($stmt, 's', $tag);

        if(!mysqli_stmt_execute($stmt)) {
            echo 'Ошибка загрузки данных в базу данных ' . mysqli_error($con);
            die;
        }

        $tagId = mysqli_insert_id($con);
        $tagId = intval($tagId);
        insertToHashtagsPosts($con, $tagId, $postId);
        
        header('Location: post.php?post_id=' . $postId, true, 302);
        die;
    }
}

/**
 * Вставляет данные в таблицу hashtags_posts БД readme
 *
 * @param mysqli Object $con Обьект mysqli
 * @param int $tagId идентификато последнего тега загруженного в БД
 * @param int $postId идентификато последнего поста загруженного в БД
 * 
 * @return void
 */
function insertToHashtagsPosts(object $con, int $tagId, int $postId): void {
     $queryRel = 'INSERT INTO hashtags_posts(hashtag_id, post_id) VALUE (?, ?)';

    $stmt = mysqli_prepare($con, $queryRel);
    mysqli_stmt_bind_param($stmt, 'ii', $tagId, $postId);

    if(!mysqli_stmt_execute($stmt)) {
        echo 'Ошибка загрузки данных в базу данных ' . mysqli_error($con);
        die;
    }
}

/**
 * Получает данные (теги) из БД которые относятся к конкретному посту ($postId)
 *
 * @param mysqli Object $con Обьект mysqli
 * @param int $postId идентификатор поста
 * 
 * @return array
 */
function getTagsFromDB(object $con, int $postId): array {
    $query = 'SELECT hashtag FROM hashtags WHERE id IN(SELECT hashtag_id FROM hashtags_posts WHERE post_id=?)';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $postId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $tags = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $tags = array_column($tags, 'hashtag');

    return $tags;
}

/**
 * Вставляет данные переданные из формы в таблицу posts БД readme
 *
 * @param mysqli Object $con Обьект mysqli
 * @param string $postType тип контента
 * @param string $filePath путь к файлу сохраненному в публичной папке проекта uploads
 * @param array $formData данные из формы переданные пользователем
 * 
 * @return int
 */
function insertPostToDatabase(object $con, string $postType, string $filePath, array $formData): int {
    list($title, $content, $quote_author) = array_values($formData);
    $postQuery = '';
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
    
    if(!mysqli_stmt_execute($stmt)) {
        echo 'Ошибка загрузки поста в базу данных ' . mysqli_error($con);
        die;
    }
    $postId = mysqli_insert_id($con);

    return $postId;
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
function getPost(object $con, int $post_id): array {
    $query_post = 'SELECT posts.*, users.id, users.login AS author, users.avatar, content_types.class_name AS post_type,' .
                '(SELECT COUNT(likes.post_id) FROM likes WHERE likes.post_id = posts.id) AS likes_count,' .
                ' (SELECT COUNT(posts.id) FROM posts WHERE posts.user_id=users.id) AS post_count,' .
                ' (SELECT COUNT(subscriptions.user_id) FROM subscriptions WHERE subscription_id=posts.user_id) AS subscription_count,' .
                ' (SELECT COUNT(comments.id) FROM comments WHERE comments.user_id=users.id) AS comments_count' .
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
 * @param string $name значение атрибута name поля формы
 * @param array $formData данные из формы переданные пользователем
 * 
 * @return string
 */
function validateUrl(string $name, array $formData): string {
    if (empty($formData[$name])) {
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
 * @param array $formData данные из формы переданные пользователем
 * 
 * @return string
 */
function validateYoutubeUrl(string $name, array $formData): string {
    if (!filter_input(INPUT_POST, $name, FILTER_VALIDATE_URL)) {
        return 'Введите корректную ссылку из интернета';
    }
    if ($error = check_youtube_url($formData[$name])) {
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
 * @param array $formData данные из формы переданные пользователем
 * 
 * @return string
 */
function validateHashtag(string $name, array $formData): string {
    if (empty($_POST[$name])) {
        return '';
    }

    if(!preg_match_all('/#[^#\s]+/', $formData[$name])) {
        return 'Введите корректный хештег(и)';
    }
    return '';
}

/**
 * Проверяет тип и размер загруженного файла, если некорректный возвращает текст ошибки
 * 
 * @param array $file данные файла переданного пользователем
 * 
 * @return string
 */
function validateImage(array $file): string {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $fileName = $file['tmp_name'];
    $fileSize = $file['size'];

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
 * Проверяет корректность email
 *
 * @param string $name значение атрибута name поля формы
 * @param array $formData данные из формы переданные пользователем
 * 
 * @return string
 */
function validateEmail(string $name, array $formData): string {
    if (empty($formData[$name])) {
        return '';
    }
    if (!filter_input(INPUT_POST, $name, FILTER_VALIDATE_EMAIL)) {
        return 'Введите корректный email';
    }
    return '';
}

/**
 * Осуществляет валидацию данных из формы загрузки поста
 * 
 * @param array $rules массив в котором ключи - значение атрибута name поля формы, значения - callback
 * @param array $required массив в котором значения - значение атрибута name поля формы
 * @param string $postType тип поста
 * 
 * @return array
 */
function validatePostForm(array $rules, array $required, string $postType): array {
    $errors = [];
    $filterRules = [];
    $req = [];

    foreach ($rules as $key => $value) {
        $type = explode('-', $key)[0];

        if ($postType === $type) {
            $filterRules[$key] = FILTER_DEFAULT;
        }
    }

    foreach ($required as $key) {
        $type = explode('-', $key)[0];

        if ($postType === $type) {
            $req[] = $key;
        }
    }

    $post = filter_input_array(INPUT_POST, $filterRules, true);

    foreach ($post as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    foreach ($req as $key) {
        if (empty($post[$key])) {
            $errors[$key] = 'Поле должно быть заполнено';
        }
    }

    $errors = array_filter($errors);
    return $errors;
}

/**
 * Осуществляет валидацию данных из формы регистрации/входа
 * 
 * @param array $rules массив в котором ключи - значение атрибута name поля формы, значения - callback
 * @param array $required массив в котором значения - значение атрибута name поля формы
 * 
 * @return array
 */
function validateForm(array $rules, array $required): array {
    foreach ($rules as $key => $value) {
        $filterRules[$key] = FILTER_DEFAULT;
    }
    $post = filter_input_array(INPUT_POST, $filterRules, true);

    foreach ($post as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    foreach ($required as $key) {
        if (empty($post[$key])) {
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
function getPostValue(string $name): string {
    return $_POST[$name] ?? '';
}

/**
 * Возвращает значение по ключу $name из массива $_GET
 *
 * @param string $name значение атрибута name поля формы
 * 
 * @return string
 */
function getPostValueGET(string $name): string {
    return $_GET[$name] ?? '';
}

/**
 * Перемещает файл из временного хранилища в публичную папку проекта
 * 
 * @param string $tmpPath путь к временному хранилищу загруженного файла
 * @param string $path путь к публичной папке uploads проекта
 * 
 * @return string текст ошибки или пустую строку
 */
function moveLoadedFile(string $tmpPath, string $path): string {

    if (move_uploaded_file($tmpPath, $path)) {
        return 'Ошибка перемещения файла в публичную папку проекта';
    }
    return '';
}

/**
 * Скачивает файл по ссылке и помещает в публичную папку проекта
 * 
 * @param string $link ссылка из интернета на скачиваемый файл
 * @param string $path путь к публичной папке uploads проекта
 * 
 * @return string текст ошибки или пустую строку
 */
function downloadImageFile(string $link, string $path): string {

    if(!$fileContent = file_get_contents($link)) {
        return 'Ошибка загрузки файла по сети';
    }
    if (!file_put_contents($path, $fileContent)) {
        return 'Ошибка перемещения файла в публичную папку проекта';
    }
    return '';
}

/**
 * Разбивает строку тегов на отдельные слова 
 * 
 * @param string $tags строка с тегами
 * 
 * @return array
 */
function getTags(string $tags): array {
    preg_match_all('/#[^#\s]+/', $tags, $match);
    $tags = $match[0];

    return $tags;
}

/**
 * Проверяет что указанный email уже не используется другим пользователем
 * 
 * @param mysqli Object $con Обьект mysqli
 * @param string $email указанный email
 * 
 * @return string
 */
function checkEmail(object $con, string $newEmail): string {
    $query = 'SELECT email FROM users WHERE email = ?';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 's', $newEmail);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    if (mysqli_fetch_assoc($res)) {
        return 'Такой email уже существует в базе данных';
    }

    return '';
}

/**
 * Вставляет данные переданные из формы в таблицу users БД readme
 *
 * @param mysqli Object $con Обьект mysqli
 * @param string $avatar путь к файлу сохраненному в публичной папке проекта uploads
 * @param array $formData данные из формы переданные пользователем
 * 
 * @return void
 */
function insertUsersDataToDatabase(object $con, string $avatar, array $formData): void {
    list($email, $login,,) = array_values($formData);
    $passwordHash = password_hash($formData['password'], PASSWORD_DEFAULT);
    $query = 'INSERT INTO users(email, login, password, avatar) VALUE(?, ?, ?, ?);';
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ssss', $email, $login, $passwordHash, $avatar);

    if(!mysqli_stmt_execute($stmt)) {
        echo 'Ошибка загрузки данных поста в базу данных ' . mysqli_error($con);
        die;
    }

    header('Location: index.php', true, 302);
    die;
}

/**
 * Проверяет что переданный пароль является корректным
 * 
 * @param mysqli Object $con Обьект mysqli
 * @param array $formData данные из формы переданные пользователем
 * 
 * @return string идентификатор пользователя
 */
function checkPassword(object $con, array $formData): bool {
    $query = 'SELECT password FROM users WHERE email = ?';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 's', $formData['login']);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $passwordHash = mysqli_fetch_assoc($res)['password'];

    if (password_verify($formData['password'], $passwordHash)) {
        $query = 'SELECT id FROM users WHERE email = ?';

        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 's', $formData['login']);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if (!$res) {
            die('Ошибка получения данных: ' . mysqli_error($con));
        }

        $userId = mysqli_fetch_assoc($res)['id'];

        return $userId;
    }

    return '';
}

/**
 * Возвращает данные пользователя по user id
 * 
 * @param mysqli Object $con Обьект mysqli
 * @param string $userId идентификатор запрашиваемого пользователя 
 * 
 * @return array
 */
function getUser(object $con, string $userId): array {
    $query = 'SELECT * FROM users WHERE id = ?';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $user = mysqli_fetch_assoc($res);
    return $user;
}

/**
 * Возвращает подписки пользователя
 * 
 * @param mysqli Object $con Обьект mysqli
 * @param string $userId идентификатор пользователя 
 * 
 * @return array
 */
function getSubscriptions(object $con, string $userId): array {
    $query = 'SELECT subscription_id FROM subscriptions WHERE user_id = ?';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 's', $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $subscriptions = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return array_column($subscriptions, 'subscription_id');
}

/**
 * Возвращает посты пользователей на кого оформлена подписка
 * 
 * @param mysqli Object $con Обьект mysqli
 * @param array $subscriptions подписки 
 * 
 * @return array
 */
function getSubPosts(object $con, array $subscriptions): array {
    if (!$subscriptions) {
       return array();
    }
    $str = '';

    foreach ($subscriptions as $sub) {
        $str .= $sub . ', ';
    }

    $str = rtrim($str, ', ');
    $query = 'SELECT posts.*, content_types.class_name AS type, users.avatar, users.login FROM posts' .
                ' RIGHT JOIN content_types ON posts.content_type_id = content_types.id' .
                ' RIGHT JOIN users ON users.id = posts.user_id' .
                ' WHERE user_id IN (' . $str . ')' .
                ' ORDER BY created_at DESC' ;

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $posts = mysqli_fetch_all($res, MYSQLI_ASSOC);
   
    return $posts;
}

/**
 * Получает посты из БД по запросу 
 * 
 * @param mysqli Object $con Обьект mysqli
 * @param string $searchQuery строка запроса
 * 
 * @return array
 */
function searchPosts(object $con, string $searchQuery): array {
    if (substr($searchQuery, 0, 1) === '#') {
        $searchQuery = substr($searchQuery, 1);
        echo $searchQuery;
    }
    $query = 'SELECT posts.*, content_types.class_name AS type, users.login, users.avatar FROM posts' .
                ' RIGHT JOIN content_types ON posts.content_type_id = content_types.id' .
                ' RIGHT JOIN users ON users.id = posts.user_id' .
                ' WHERE MATCH(title, content) AGAINST(?)';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 's', $searchQuery);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
            die('Ошибка получения данных: ' . mysqli_error($con));
        }

    $posts = mysqli_fetch_all($res, MYSQLI_ASSOC);

    return $posts;
}

/**
 * Возвращает все посты конкретного пользователя из базы данных
 *
 * @param int $userId идентификатор пользователя
 * @param mysqli Object $con Обьект mysqli
 * 
 * @return array
 */
function getProfilePosts(object $con, int $userId) {
    $query = 'SELECT * FROM posts' .
                ' LEFT JOIN hashtags_posts hashtags_posts ON posts.id = hashtags_posts.post_id' .
                ' LEFT JOIN hashtags ON hashtags_posts.hashtag_id = hashtags.id' .
                ' LEFT JOIN content_types ON posts.content_type_id = content_types.id' .
                ' WHERE user_id=?';


    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

     if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $posts = mysqli_fetch_all($res, MYSQLI_ASSOC);

    for ($i=0; $i < count($posts); $i++) {
        $counter = 0;

        for ($j=0; $j < count($posts); $j++) { 
            if (@$posts[$i]['post_id'] === @$posts[$j]['post_id']) {
                $counter ++;

                if ($counter > 1) {
                    if (is_array($posts[$i]['hashtag'])) {
                        array_push($posts[$i]['hashtag'], $posts[$j]['hashtag']);
                    } else {
                        $posts[$i]['hashtag'] = [$posts[$i]['hashtag'], $posts[$j]['hashtag']];
                    }
                    unset($posts[$j]);
                }
            }
        }
    }

    return $posts;
}

/**
 * Возвращает данные пользователя для профайла по user id
 * 
 * @param mysqli Object $con Обьект mysqli
 * @param string $userId идентификатор запрашиваемого пользователя 
 * 
 * @return array
 */
function getProfileUser(object $con, string $userId): array {
    $query = 'SELECT users.*,' .
                ' (SELECT COUNT(id) FROM posts WHERE user_id=?) AS posts,' .
                ' (SELECT COUNT(user_id) FROM subscriptions WHERE subscription_id=?) AS subscriptions' .
                ' FROM users' .
                ' WHERE id=?';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'iii', $userId, $userId, $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $user = mysqli_fetch_assoc($res);
    return $user;
}

function getSubscriptionInfo(object $con, int $userId, int $subUserId): bool {
    $query = 'SELECT user_id FROM subscriptions WHERE user_id=? AND subscription_id=?';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $userId, $subUserId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $res = mysqli_fetch_row($res);
   
    return (bool)$res;
}

function getUserExistsInfo(object $con, int $profileUserId): bool {
    $query = 'SELECT id FROM users WHERE id=?';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $profileUserId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $res = mysqli_fetch_row($res);
   
    return (bool)$res;
}

function subscribe(object $con, int $userId, int $profileUserId): void {
    $query = 'INSERT INTO subscriptions(user_id, subscription_id) VALUE(?,?)';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $userId, $profileUserId);

    if (!mysqli_stmt_execute($stmt)) {
        die('Ошибка записи данных: ' . mysqli_error($con));
    }
}

function unsubscribe(object $con, int $userId, int $profileUserId): void {
    echo $userId . '<br>';
    echo $profileUserId . '<br>';
    $query = 'DELETE FROM subscriptions WHERE user_id=? AND subscription_id=?';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $userId, $profileUserId);

     if (!mysqli_stmt_execute($stmt)) {
        die('Ошибка записи данных: ' . mysqli_error($con));
    }
}

function getIsLikedInfo(object $con, int $userId, int $postId): bool{
     $query = 'SELECT user_id FROM likes WHERE post_id=? AND user_id=?';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $userId, $postId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $res = mysqli_fetch_row($res);
   
    return (bool)$res;
}

function getPostExistsInfo(object $con, int $postId): bool {
    $query = 'SELECT id FROM posts WHERE id=?';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $postId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $res = mysqli_fetch_row($res);
   
    return (bool)$res;
}

function like(object $con, int $userId, int $postId) {
    $query = 'INSERT INTO likes(user_id, post_id) VALUE(?,?)';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $userId, $postId);

    if (!mysqli_stmt_execute($stmt)) {
        die('Ошибка записи данных: ' . mysqli_error($con));
    }
}

function unlike(object $con, int $userId, int $postId) {
    $query = 'DELETE FROM likes WHERE user_id=? AND post_id=?';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $userId, $postId);

    if (!mysqli_stmt_execute($stmt)) {
        die('Ошибка записи данных: ' . mysqli_error($con));
    }
}

function getPostLikedInfo(object $con, int $userId, int $postId): bool {
    $query = 'SELECT user_id FROM likes WHERE user_id=? AND post_id=?';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $userId, $postId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $res = mysqli_fetch_row($res);
   
    return (bool)$res;
}

function checkTextLength(string $name): string {
    if(strlen($_POST[$name]) < 4) {
        return 'Длина текста должна быть не менее 4-х символов';
    }
    return '';
}

function addComment(object $con, string $comment, int $userId, int $postId): void {
    if ($userId === 0 || $postId === 0) {
        die('Такого пользователя/поста не существует');
    }
    $query = 'INSERT INTO comments(comment, user_id, post_id) VALUE(?,?,?)';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'sii', $comment, $userId, $postId);

    if (!mysqli_stmt_execute($stmt)) {
        die('Ошибка записи данных: ' . mysqli_error($con));
    }
}

function getComments(object $con, int $postId): array {
    $query = 'SELECT comments.*, users.login, users.avatar FROM comments' . 
                ' LEFT JOIN users ON comments.user_id=users.id' .
                '  WHERE post_id=?';

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $postId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        die('Ошибка получения данных: ' . mysqli_error($con));
    }

    $res = mysqli_fetch_all($res, MYSQLI_ASSOC);
   
    return $res;
}
