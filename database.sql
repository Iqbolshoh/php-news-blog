/* 
 * ======================================================
 *                    DATABASE CREATION                  
 * ======================================================
 */

-- 1. Drop the existing version (if it exists)
DROP DATABASE IF EXISTS news_db;

-- 2. Create a new database
CREATE DATABASE news_db;

-- 3. Use the newly created database
USE news_db;

-- ==================== Users Table ====================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==================== News Table ====================
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,        -- News title
    content TEXT NOT NULL,              -- News content
    user_id INT,                        -- Author (foreign key)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ==================== Insert Admin User ====================
INSERT INTO users (name, username, password, role)
VALUES (
    'Iqbolshoh Ilhomjonov', 
    'iqbolshoh', 
    '$2y$10$gIKUrsLRB.U7ee9Fv9nib.di2NgMYvAeqqWGoB5aFXpHoxIv/igkW', -- Hashed password
    'admin'
);

-- ==================== Insert Initial News ====================
INSERT INTO news (title, content, user_id)
VALUES (
    'Laravel 11 Released!',
    'Laravel 11 is now lighter and more powerful! The new features will blow your mind. 🔥',
    1
);