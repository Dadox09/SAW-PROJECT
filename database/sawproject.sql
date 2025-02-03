-- Creazione del database (se non esiste già)
CREATE DATABASE IF NOT EXISTS sawproject;
USE sawproject;

-- Tabella utenti
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_admin BOOLEAN DEFAULT FALSE,
    newsletter_subscribed BOOLEAN DEFAULT FALSE -- Nuovo campo per l'iscrizione alla newsletter
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Indici per ottimizzare le ricerche
CREATE INDEX IF NOT EXISTS idx_email ON users(email); 
CREATE INDEX IF NOT EXISTS idx_username ON users(username); 

-- Tabella prenotazioni 
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    guests INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Indici per ottimizzare le ricerche
CREATE INDEX IF NOT EXISTS idx_user_id ON bookings(user_id); 
CREATE INDEX IF NOT EXISTS idx_check_in ON bookings(check_in_date); 
CREATE INDEX IF NOT EXISTS idx_check_out ON bookings(check_out_date); 