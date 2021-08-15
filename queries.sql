USE readme;

-- Добавление информации в БД
-- Записываем данные (список типов контента для поста)
INSERT INTO content_types
VALUES
	('Текст', 'text'),
	('Цитата', 'quote'),
	('Картинка', 'photo'),
	('Видео', 'video'),
	('Ссылка', 'link');

-- Добавляем пользователей
INSERT INTO users
VALUES
	('larisa@gmail.com', 'Лариса', 'qwerty'),
	('vladik@gmail.com', 'Владик', '12345'),
	('viktor@gmail.com', 'Виктор', 'qaz');

-- Добавляем существующий список постов
INSERT INTO posts
VALUES
	( 
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
	('Отличный сериал!', 1, 2),
	('Сериал бомба!', 3, 2),
	('Шикарная цитата!', 2, 1);

-- Запросы для этих действий
-- Получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента
SELECT results.post_id, content, login, content_types.type, results.likes_num FROM (
	SELECT COUNT(post_id) as likes_num, post_id FROM likes
	GROUP BY post_id
) AS results
	RIGHT JOIN posts ON results.post_id = posts.user_id
	LEFT JOIN users ON users.id = posts.user_id
	LEFT JOIN content_types ON content_types.id = posts.content_type_id
	GROUP BY post_id
	ORDER BY likes_num DESC;

-- Получить список постов для конкретного пользователя
SELECT content, login FROM posts
	INNER JOIN users ON users.id = posts.user_id
	WHERE users.id = 2;

-- Получить список комментариев для одного поста, в комментариях должен быть логин пользователя
SELECT comment, login FROM comments
	INNER JOIN users ON users.id = comments.user_id
	WHERE post_id = 2;

-- Добавить лайк к посту
INSERT INTO likes VALUES
					(1, 2),
					(1, 2),
					(1, 2),
					(2, 3),
					(3, 2);

-- Подписаться на пользователя
INSERT INTO subscriptions VALUES (1, 2);