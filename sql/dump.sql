-- Table de statuts pour les articles
CREATE TABLE article_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    libelle VARCHAR(100) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

INSERT INTO article_status (code, libelle)
VALUES
('published', 'Publie'),
('deleted', 'Supprime');

-- Création de la table pour les articles
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre_h1 VARCHAR(255) NOT NULL,            -- Pour le SEO
    url_slug VARCHAR(255) UNIQUE NOT NULL,     -- Pour l'URL rewriting
    contenu_html TEXT,                         -- Ce qui viendra de TinyMCE
    image_url VARCHAR(255),
    image_alt VARCHAR(255),                    -- Pour le SEO
    meta_description VARCHAR(160),             -- Pour les balises méta
    article_status_id INT NOT NULL DEFAULT 1,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_articles_status FOREIGN KEY (article_status_id) REFERENCES article_status(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Donnees de test pour l'index
INSERT INTO articles (titre_h1, url_slug, contenu_html, image_url, image_alt, meta_description, article_status_id)
VALUES
('Briefing du matin: deplacements et securite', 'briefing-du-matin-deplacements-et-securite', '<h2>Resume</h2><p>Point rapide sur les deplacements internes et les zones sensibles.</p><p>Sources croisees et mises a jour toutes les 4 heures.</p>', 'assets/img/uploads/briefing-matin.jpg', 'Carte des deplacements et checkpoints', 'Point rapide sur la securite et les deplacements internes.', 1),
('Chronologie: la semaine en 5 dates', 'chronologie-la-semaine-en-5-dates', '<h2>Faits marquants</h2><p>Cinq dates cles pour comprendre l''evolution recente.</p><p>Contexte, acteurs, impacts.</p>', 'assets/img/uploads/chronologie-semaine.jpg', 'Frise chronologique de la semaine', 'Cinq dates cles pour comprendre la semaine en un coup d''oeil.', 1),
('Sources et methodologie de verification', 'sources-et-methodologie-de-verification', '<h2>Methodologie</h2><p>Verification par recoupement OSINT et medias internationaux.</p><p>Transparence des sources et horodatage.</p>', 'assets/img/uploads/sources-methodologie.jpg', 'Table de verification et sources', 'Comment les informations sont verifiees et recoupees.', 1);
-- Création de la table pour le BackOffice 
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(50) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Insertion d'un utilisateur par défaut pour le BackOffice
INSERT INTO utilisateurs (identifiant, mot_de_passe) 
VALUES ('admin', '$2y$10$uCy4G4mXuwXsYs7h3467f.COUKYAXfWrP1Tm35kC3AffD/CpIFKB6');