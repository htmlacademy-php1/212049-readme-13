<?php
$con = mysqli_connect('localhost', 'root', '', 'readme');

if (!$con) {
   die('Ошибка соединения с сервером MySQL: ' . mysqli_connect_error());
}

mysqli_set_charset($con, 'utf8');
