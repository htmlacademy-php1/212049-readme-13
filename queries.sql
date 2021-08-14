USE readme;

-- Добавление информации в БД
-- Записываем данные (список типов контента для поста)
INSERT INTO content_types
VALUES
	(DEFAULT, 'Текст', 'text'),
	(DEFAULT, 'Цитата', 'quote'),
	(DEFAULT, 'Картинка', 'photo'),
	(DEFAULT, 'Видео', 'video'),
	(DEFAULT, 'Ссылка', 'link');

-- Добавляем пользователей
INSERT INTO users
VALUES
	(DEFAULT, NOW(), 'larisa@gmail.com', 'Лариса', "qwerty", ''),
	(DEFAULT, NOW(), 'vladik@gmail.com', 'Владик', "12345", ''),
	(DEFAULT, NOW(), 'viktor@gmail.com', 'Виктор', "qaz", '');

-- Добавляем существующий список постов
INSERT INTO posts
VALUES
	(
		DEFAULT, 
		NOW(), 
		'Цитата', 
		'Мы в жизни любим только раз, а после ищем лишь похожих', 
		'Лариса', 
		'', 
		'', 
		'', 
		11,
		1, 
		2
	),
	(
		DEFAULT, 
		NOW(), 
		'Игра престолов', 
		'Не могу дождаться начала финального сезона своего любимого сериала!', 
		'Владик', 
		'', 
		'', 
		'', 
		22,
		2, 
		2
	),
	(
		DEFAULT,
		NOW(),
		'Наконец, обработал фотки!',
		'',
		'Виктор',
		'rock-medium.jpg',
		'',
		'',
		35,
		3,
		3
	),
	(
		DEFAULT,
		NOW(),
		'Моя мечта',
		'',
		'Лариса',
		'coast-medium.jpg',
		'',
		'',
		22,
		1,
		3

	),
	(
		DEFAULT,
		NOW(),
		'Лучшие курсы',
		'',
		'Владик',
		'',
		'',
		'www.htmlacademy.ru ',
		1,
		2,
		5
	);

-- Добавляем три комментария
INSERT INTO comments
VALUES
	(DEFAULT, NOW(), 'Отличный сериал!', 1, 2),
	(DEFAULT, NOW(), 'Сериал бомба!', 3, 2),
	(DEFAULT, NOW(), 'Шикарная цитата!', 2, 1);

-- Запросы для этих действий
-- Получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента
SELECT content, num_of_views, login, type FROM posts
	INNER JOIN users ON users.id = posts.user_id
	INNER JOIN content_types ON content_types.id = posts.content_type_id
	ORDER BY num_of_views DESC;

-- Получить список постов для конкретного пользователя
SELECT content, login FROM posts
	INNER JOIN users ON users.id = posts.user_id
	WHERE login = 'Владик';

-- Получить список комментариев для одного поста, в комментариях должен быть логин пользователя
SELECT comment, login FROM comments
	INNER JOIN users ON users.id = comments.user_id
	WHERE post_id = 2;

-- Добавить лайк к посту
INSERT INTO likes VALUES(1, 2);

-- Подписаться на пользователя
INSERT INTO subscriptions VALUES (1, 2);