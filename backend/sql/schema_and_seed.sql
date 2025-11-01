CREATE DATABASE IF NOT EXISTS blog_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blog_db;
DROP TABLE posts;

CREATE TABLE IF NOT EXISTS posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- DROP TABLE users;
-- CREATE TABLE IF NOT EXISTS users (
--     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     username VARCHAR(100) NOT NULL UNIQUE,
--     password_hash VARCHAR(255) NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TEST SEED
INSERT INTO 
    posts (title, body) 
VALUES  
    ('Hello world', 'This is the first post'),
    ('Second post', 'Another post on the blog');

-- INSERT INTO 
--     users (username, password_hash) 
-- VALUES  
--     ('user', '$2y$10$8gDuLiz251fvGebz9ZDcF.q7Qj0B8i6REKZYkF7CJHPhIG17WaegW');
