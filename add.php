<?php
require_once 'helpers.php';
require_once 'functions.php';

$isAuth = rand(0, 1);
$userName = 'Yuriy'; // укажите здесь ваше имя
$formType = isset($_POST['content-type']) ? key($_POST['content-type']) : 'photo';
$formType = preg_replace('/\'/', '', $formType);
$postType = $_GET['postType'] ?? $formType;
$tagIndex = $postType . '-' . 'tag';
$filePath = '';

$con = mysqliConnect();
$postTypes = getPostTypes($con);
$errors = validateForm();

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
    $con = mysqliConnect();
    insertTagsToDatabase($con, $tags);
    insertPostToDatabase($con, $postType, $tags, $filePath);
}

$modErrors = modifyErrors($errors);

$path = 'adding-post-' . $postType . '.php';

$blockContent = include_template($path, ['errors' => $errors, 'modErrors' => $modErrors]);
$pageContent = include_template('adding-post-main.php', ['blockContent' => $blockContent, 'postTypes' => $postTypes, 'postType' => $postType]);
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'isAuth' => $isAuth, 'userName' => 'Yuriy', 'title' => 'readme: добавить публикацию']);
print($layoutContent);
