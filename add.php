<?php
require_once 'helpers.php';
require_once 'functions.php';

$isAuth = rand(0, 1);
$userName = 'Yuriy'; // укажите здесь ваше имя
$postType = $_GET['postType'] ?? 'photo';
$tagIndex = $postType . '-' . 'tag';
$blockContentPath = 'adding-post-' . $postType . '.php';
$photoUrlError = 'Выбрите файл изображения со своего компьютера, либо укажите прямую ссылку на изображение, размещенное в интернете.';
$filePath = '';
$errors = [];
$modErrors = [];
$required = ['photo-title', 'video-title', 'video-url', 'text-title', 'text-content', 'quote-title', 'quote-content', 'quote-author', 'link-title', 'link-url'];
$rules = [
    'photo-title' => function() {},
    'photo-url' => function() {
        return validateUrl('photo-url', $_POST);
    },
    'photo-tag' => function() {
        return validateHashtag('photo-tag', $_POST);
    },
    'video-title' => function() {},
    'video-url' => function() {
        return validateYoutubeUrl('video-url', $_POST);
    },
    'video-tag' => function() {
        return validateHashtag('video-tag', $_POST);
    },
    'text-title' => function() {},
    'text-content' => function() {},
    'text-tag' => function() {
        return validateHashtag('text-tag', $_POST);
    },
    'quote-title' => function() {},
    'quote-content' => function() {},
    'quote-author' => function() {},
    'quote-tag' => function() {
        return validateHashtag('quote-tag', $_POST);
    },
    'link-title' => function() {},
    'link-url' => function() {
        return validateUrl('link-url', $_POST);
    },
    'link-tag' => function() {
        return validateHashtag('link-tag', $_POST);
    },
];
$keys = [
    'photo-title' => 'ЗАГОЛОВОК',
    'video-title' => 'ЗАГОЛОВОК',
    'text-title' => 'ЗАГОЛОВОК',
    'quote-title' => 'ЗАГОЛОВОК',
    'link-title' => 'ЗАГОЛОВОК',
    'photo-url' => 'ССЫЛКА ИЗ ИНТЕРНЕТА', 
    'photo-tag' => 'ТЕГИ',
    'video-tag' => 'ТЕГИ',
    'text-tag' => 'ТЕГИ',
    'quote-tag' => 'ТЕГИ',
    'link-tag' => 'ТЕГИ',
    'video-url' => 'ССЫЛКА YOUTUBE', 
    'text-content' => 'ТЕКСТ ПОСТА', 
    'quote-content' => 'ТЕКСТ ЦИТАТЫ', 
    'quote-author' => 'АВТОР', 
    'link-url' => 'ССЫЛКА',
];
$con = mysqliConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validatePostForm($rules, $required, $postType);

    if (isset($_FILES['userpic-file-photo']) && isset($_POST)) {
        if ($_FILES['userpic-file-photo']['error'] && empty($_POST['photo-url'])) {
            $errors['photo-url'] = $photoUrlError;
        } elseif (!empty($_FILES['userpic-file-photo']['name']) && !$errors) {
            if (!$error = validateImage($_FILES['userpic-file-photo']) && !$errors) {
                $ext = explode('/', $_FILES['userpic-file-photo']['type'])[1];
                $fileName = uniqid() . '.' . $ext;
                $path = __DIR__ . '/uploads/' . $fileName;
                $filePath = $fileName;

                moveLoadedFile($_FILES['userpic-file-photo']['tmp_name'], $path);
            } else {
                $errors['file'] = $error;
            }
        } elseif (!$errors && !empty($_POST['photo-url'])) {
            preg_match('/jpeg|jpg|png|gif/', $_POST['photo-url'], $match);
            $ext = $match[0] ?? '';
            $fileName = uniqid() . '.' . $ext;
            $path = __DIR__ . '/uploads/' . $fileName;
            $filePath = $fileName;

            $error = downloadImageFile($_POST['photo-url'], $path);

            if ($error) {
                $errors['photo-url'] = $error;
            }
        }
    }

    if (!$errors) {
        $tags = getTags($_POST[$tagIndex]);
        insertTagsToDatabase($con, $tags);
        if ($postType === 'video') {
            $filePath = $_POST['video-url'];
        }
        insertPostToDatabase($con, $postType, $filePath, $_POST);
    }
    
    $modErrors = modifyErrors($errors, $keys); 
}
$postTypes = getPostTypes($con);

$blockContent = include_template($blockContentPath, ['errors' => $errors, 'modErrors' => $modErrors]);
$pageContent = include_template('adding-post-main.php', ['blockContent' => $blockContent, 'postTypes' => $postTypes, 'postType' => $postType]);
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'isAuth' => $isAuth, 'userName' => 'Yuriy', 'title' => 'readme: добавить публикацию']);
print($layoutContent);
