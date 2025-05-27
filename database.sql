/* 
 * ======================================================
 *                    DATABASE YARATISH              
 * ======================================================
 */

-- 1. Avvalgi versiyani tozalash (agar mavjud bo'lsa)
DROP DATABASE IF EXISTS news_db;

-- 2. Yangi database yaratish
CREATE DATABASE news_db;

-- 3. Database aktivlashtirish
USE news_db;

-- ==================== Users ====================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==================== News ====================
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,         -- Yangilik sarlavhasi
    content TEXT NOT NULL,               -- Yangilik matni
    user_id INT,                         -- Muallif (foreign key)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (name, username, password, role)
VALUES ('Iqbolshoh Ilhomjonov', 'iqbolshoh', '$2y$10$gIKUrsLRB.U7ee9Fv9nib.di2NgMYvAeqqWGoB5aFXpHoxIv/igkW', 'admin');

INSERT INTO news (title, content, user_id)
VALUES ('Laravel 11 chiqdi!','Laravel 11 versiyasi endi yanada engil va kuchli! Yangi xususiyatlar sizni hayratda qoldiradi.',1);