CREATE DATABASE todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;

USE todo_app;

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    task_text TEXT NOT NULL,
    priority VARCHAR(20),
    notes TEXT,
    tags TEXT,
    completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci; 