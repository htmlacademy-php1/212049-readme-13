USE readme;

-- Добавление информации в БД
-- Записываем данные (список типов контента для поста)
INSERT INTO content_types
	(type, class_name, width, height)
VALUES
	('Картинка', 'photo', '22', '18'),
	('Видео', 'video', '24', '16'),
	('Текст', 'text', '20', '21'),
	('Цитата', 'quote', '21', '20'),
	('Ссылка', 'link', '21', '18');

-- Добавляем пользователей
INSERT INTO users
	(email, login, password, avatar)
VALUES
	('larisa@gmail.com', 'Лариса', 'qwerty', 'userpic-larisa-small.jpg'),
	('vladik@gmail.com', 'Владик', '12345', 'userpic.jpg'),
	('viktor@gmail.com', 'Виктор', 'qaz', 'userpic-mark.jpg');

-- Добавляем существующий список постов
INSERT INTO posts
	(title, content, quote_author, image, video, link, num_of_views, user_id, content_type_id)
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
		3
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
		3
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
		1
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
		1
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

-- Добавить лайк к посту
INSERT INTO likes VALUES
					(1, 2),
					(1, 2),
					(1, 2),
					(2, 3),
					(2, 3),
					(3, 1);

-- Подписаться на пользователя
INSERT INTO subscriptions VALUES (1, 2);
INSERT INTO subscriptions VALUES (1, 3);

-- Добавляем три комментария
INSERT INTO comments
	(comment, user_id, post_id)
VALUES
	('Отличный сериал!', 1, 2),
	('Сериал бомба!', 3, 2),
	('Шикарная цитата!', 2, 1);

-- Запросы для этих действий
-- Получить список постов с сортировкой по популярности вместе с именами авторов и типом контента
SELECT
	posts.id,
	content,
	users.login,
	content_types.type,
	class_name,
	avatar,
	(SELECT COUNT(likes.post_id) FROM likes WHERE likes.post_id = posts.id) AS likes_count
FROM posts
JOIN users ON posts.user_id = users.id
JOIN content_types ON posts.content_type_id = content_types.id
ORDER BY likes_count DESC;

-- Получить список постов для конкретного пользователя
SELECT content, login FROM posts
	INNER JOIN users ON users.id = posts.user_id
	WHERE users.id = 2;

-- Получить список комментариев для одного поста, в комментариях должен быть логин пользователя
SELECT comment, login FROM comments
	INNER JOIN users ON users.id = comments.user_id
	WHERE post_id = 2;
