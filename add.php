<?php
require_once 'helpers.php';
require_once 'functions.php';

$isAuth = rand(0, 1);
$userName = 'Yuriy'; // укажите здесь ваше имя
$formType = isset($_POST['content-type']) ? trim(key($_POST['content-type']), '\'') : 'photo';
$postType = $_GET['postType'] ?? $formType;
$tagIndex = $postType . '-' . 'tag';
$filePath = '';
$blockContentPath = 'adding-post-' . $postType . '.php';
$errors = [];
$modErrors = [];
$required = ['photo-title', 'video-title', 'video-url', 'text-title', 'text-content', 'quote-title', 'quote-content', 'quote-author', 'link-title', 'link-url'];
$rules = [
    'photo-url' => function() {
        return validateUrl('photo-url');
    },
    'photo-tag' => function() {
        return validateHashtag('photo-tag');
    },
    'video-url' => function() {
        return validateYoutubeUrl('video-url');
    },
    'video-tag' => function() {
        return validateHashtag('video-tag');
    },
    'text-tag' => function() {
        return validateHashtag('text-tag');
    },
    'quote-tag' => function() {
        return validateHashtag('quote-tag');
    },
    'link-url' => function() {
        return validateUrl('link-url');
    },
    'link-tag' => function() {
        return validateHashtag('link-tag');
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateForm($rules, $required);

    if (isset($_FILES['userpic-file-photo']) && isset($_POST)) {
        if ($_FILES['userpic-file-photo']['error'] && empty($_POST['photo-url'])) {
            $errors['photo-url'] = 'Выбрите файл изображения со своего компьютера, либо укажите прямую ссылку на изображение, размещенное в интернете.';
        } elseif (!empty($_FILES['userpic-file-photo']['name']) && !$errors) {
            if (!$error = validateImage() && !$errors) {
                moveLoadedFile();
            } else {
                $errors['file'] = $error;
            }
        } elseif (!$errors && !empty($_POST['photo-url'])) {
            $error = downloadImageFile();
            if ($error) {
                $errors['photo-url'] = $error;
            }
        }
    }

    if (!$errors && isset($_POST[$tagIndex])) {
        $tags = getTags($tagIndex);
        insertTagsToDatabase($con, $tags);
        insertPostToDatabase($con, $postType, $tags, $filePath);
    }
    
    $modErrors = modifyErrors($errors, $keys); 
}

$con = mysqliConnect();
$postTypes = getPostTypes($con);

$blockContent = include_template($blockContentPath, ['errors' => $errors, 'modErrors' => $modErrors]);
$pageContent = include_template('adding-post-main.php', ['blockContent' => $blockContent, 'postTypes' => $postTypes, 'postType' => $postType]);
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'isAuth' => $isAuth, 'userName' => 'Yuriy', 'title' => 'readme: добавить публикацию']);
print($layoutContent);
