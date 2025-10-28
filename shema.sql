-- Schema SQL corrigé pour l'Association d'Art
-- À importer dans phpMyAdmin

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS new_site_artv1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE new_site_artv1;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des articles
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    image VARCHAR(500) DEFAULT NULL,
    author_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_author (author_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des commentaires
 CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    user_id INT DEFAULT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des abonnés newsletter
CREATE TABLE IF NOT EXISTS subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion d'un utilisateur de test (password: cake)
INSERT INTO users (username, email, password) VALUES 
('admin', 'admin@aurart.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE username=username;

-- Insertion d'articles de démonstration
INSERT INTO articles (title, slug, content, author_id) VALUES 
('Bienvenue à la Collection Aur\'art', 'bienvenue-collection-aurart', 
'Nous sommes ravis de vous présenter notre nouvelle plateforme dédiée à l\'art et aux artistes locaux. Découvrez nos expositions, nos ateliers et nos événements à venir.\n\nNotre mission est de créer un espace d\'expression libre pour tous les passionnés d\'art.', 
1),
('Exposition de printemps 2025', 'exposition-printemps-2025',
'Cette année, notre exposition de printemps mettra en avant les œuvres de dix artistes locaux émergents. Sculptures, peintures et installations seront au rendez-vous.\n\nVernissage prévu le 15 avril 2025 à 18h.',
1)
ON DUPLICATE KEY UPDATE title=title;