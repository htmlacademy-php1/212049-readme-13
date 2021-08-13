CREATE DATABASE readme
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE readme;

CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	registed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	email VARCHAR(128) NOT NULL UNIQUE,
	login VARCHAR(64) NOT NULL UNIQUE,
	password VARCHAR(64) NOT NULL,
	avatar VARCHAR(2048)
);

CREATE TABLE content_types (
	id INT AUTO_INCREMENT PRIMARY KEY,
	type VARCHAR(64) NOT NULL,
	class_name VARCHAR(64) NOT NULL
);

CREATE TABLE hashtags (
	id INT AUTO_INCREMENT PRIMARY KEY,
	hashtag VARCHAR(128)
);

CREATE TABLE posts (
	id INT AUTO_INCREMENT PRIMARY KEY,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	title VARCHAR(255) NOT NULL,
	content TEXT,
	quote_author VARCHAR(64) NOT NULL,
	image VARCHAR(2048),
	video VARCHAR(2048),
	link VARCHAR(2048),
	num_of_views INT NOT NULL,
	user_id INT NOT NULL,
	content_type_id INT NOT NULL,
	FOREIGN KEY (user_id)  REFERENCES users (id),
	FOREIGN KEY (content_type_id)  REFERENCES content_types (id)
);

CREATE INDEX title_index ON posts(title);

CREATE INDEX content_index ON posts(content);

CREATE TABLE comments (
	id INT AUTO_INCREMENT PRIMARY KEY,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	comment TEXT NOT NULL,
	user_id INT NOT NULL,
	post_id INT NOT NULL,
	FOREIGN KEY (user_id)  REFERENCES users (id),
	FOREIGN KEY (post_id)  REFERENCES posts (id)
);

CREATE TABLE messages (
	id INT AUTO_INCREMENT PRIMARY KEY,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	message TEXT NOT NULL,
	sender_id INT NOT NULL,
	receiver_id INT NOT NULL,
	FOREIGN KEY (sender_id)  REFERENCES users (id),
	FOREIGN KEY (receiver_id)  REFERENCES users (id)
);

CREATE TABLE likes (
	user_id INT NOT NULL,
	post_id INT NOT NULL,
	FOREIGN KEY (user_id)  REFERENCES users (id),
	FOREIGN KEY (post_id)  REFERENCES posts (id)
);

CREATE TABLE subscriptions (
	user_id INT NOT NULL,
	subscription_id INT NOT NULL,
	FOREIGN KEY (user_id)  REFERENCES users (id),
	FOREIGN KEY (subscription_id)  REFERENCES users (id)
);

CREATE TABLE hashtags_posts (
	hashtag_id INT NOT NULL,
	post_id INT NOT NULL,
	FOREIGN KEY (hashtag_id)  REFERENCES hashtags (id),
	FOREIGN KEY (post_id)  REFERENCES posts (id)
);
