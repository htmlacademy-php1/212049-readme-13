<?php
require_once 'helpers.php';
require_once 'functions.php';

$isAuth = rand(0, 1);
$userName = 'Yuriy'; // укажите здесь ваше имя
$filterButtonActive = isset($_POST['content-type']) ? array_keys($_POST['content-type'])[0] : 'photo';
$filterButtonActive = str_replace('\'', '', $filterButtonActive);

$con = masqliConnect();
$postTypes = getPostTypes($con);
$errors = validateForm();

if (!empty($_FILES['userpic-file-photo']['name']) && !$errors) {
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

$modErrors = modifyErrors($errors);

$pageContent = include_template('adding-post-main.php', ['postTypes' => $postTypes, 'errors' => $errors, 'modErrors' => $modErrors, 'filterButtonActive' => $filterButtonActive]);
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'isAuth' => $isAuth, 'userName' => 'Yuriy', 'title' => 'readme: добавить публикацию']);
print($layoutContent);


// echo "<pre>";
// print_r($_POST) . '<br>';
// print_r($_FILES);
// echo "<pre>";