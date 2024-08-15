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
    word VARCHAR(50) NOT NULL,
    translation VARCHAR(50),
    definition TEXT(120),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE sentences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    word_id INT,
    user_id INT,
    sentence TEXT(120) NOT NULL,
    translation TEXT(120) NOT NULL,
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

CREATE TABLE liked_sentences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    sentence_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (sentence_id) REFERENCES sentences(id) ON DELETE CASCADE
);

INSERT INTO
    users (fullname, email, username, password)
VALUES
    (
        `iqbolshoh ilhomjonov`,
        `iilhomjonov777@gmail.com`,
        `iqbolshoh`,
        `29cbffe112a766305c4a49a61e27d7e117c2efc0b2bd31451b3a200c24fd565b`
    ),
    (
        `admin`,
        `admin@iqbolshoh.uz`,
        `admin`,
        `0c138cbe7d1f479abb449366f3cb3dddd52bc104596ff91813c6674cd016896a`
    );

