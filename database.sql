CREATE DATABASE english;

USE english;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    word VARCHAR(255) NOT NULL,
    translation VARCHAR(255),
    definition TEXT(500),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE sentences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    word_id INT,
    user_id INT,
    sentence TEXT(500) NOT NULL,
    translation TEXT(500) NOT NULL,
    FOREIGN KEY (word_id) REFERENCES words(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE liked_words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    word_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (word_id) REFERENCES words(id) ON DELETE CASCADE
);