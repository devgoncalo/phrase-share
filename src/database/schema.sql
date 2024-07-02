CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    language VARCHAR(10) DEFAULT 'en',
    confirmation_token VARCHAR(255),
    confirmed TINYINT(1) DEFAULT 0,
    reset_token VARCHAR(255),
    reset_token_expire TIMESTAMP   
);

CREATE TABLE phrases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    visibility_type ENUM('automatic', 'manual') DEFAULT 'automatic',
    visibility BOOLEAN DEFAULT FALSE,
    show_time TIMESTAMP,
    creation_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expiration_time TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
