-- Création de la table pour les articles
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre_h1 VARCHAR(255) NOT NULL,            -- Pour le SEO
    url_slug VARCHAR(255) UNIQUE NOT NULL,     -- Pour l'URL rewriting
    contenu_html TEXT,                         -- Ce qui viendra de TinyMCE
    image_url VARCHAR(255),
    image_alt VARCHAR(255),                    -- Pour le SEO
    meta_description VARCHAR(160),             -- Pour les balises méta
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Création de la table pour le BackOffice 
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(50) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);
-- Insertion d'un utilisateur par défaut pour le BackOffice
INSERT INTO utilisateurs (identifiant, mot_de_passe) 
VALUES ('admin', 'admin123');