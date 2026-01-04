-- ============================================
-- DONNÉES D'INITIALISATION
-- ============================================

USE bibliotheque;

-- ============================================
-- USERS (Admin, Bibliothécaire, Lecteur)
-- ============================================
INSERT INTO users (email, password, first_name, last_name, phone, address, role, status, created_at) VALUES
-- Admin (Password: Admin@123)
('admin@bibliotheque.com', '$2y$10$PZJLSOG35VvsNTQEtRsZCuwj6RvI8oOx2GY6oZLVuGgSeCPa6J/.C', 'Admin', 'System', '0555000001', '123 Rue Admin, Alger', 'admin', 'active', NOW()),

-- Bibliothécaire (Password: Biblio@123)
('biblio@bibliotheque.com', '$2y$10$kN.opiguD17MsG8kMCRAsecQHFl8AV1cJeJnTU.ZF3g3LmBKVzRJK', 'Marie', 'Dubois', '0555123456', '456 Rue Biblio, Oran', 'bibliothecaire', 'active', NOW()),

-- Lecteurs (Password: Reader@123)
('lecteur@bibliotheque.com', '$2y$10$5ZjM6oacjkIaQ9hRxoqEsexLBJ4AxOTLWifkDjXIML.RG3C.u/fVS', 'Ahmed', 'Benali', '0555789012', '789 Rue Lecteur, Constantine', 'lecteur', 'active', NOW()),
('sarah.martin@email.com', '$2y$10$5ZjM6oacjkIaQ9hRxoqEsexLBJ4AxOTLWifkDjXIML.RG3C.u/fVS', 'Sarah', 'Martin', '0555234567', '321 Avenue de la Paix, Annaba', 'lecteur', 'active', NOW()),
('karim.meziane@email.com', '$2y$10$5ZjM6oacjkIaQ9hRxoqEsexLBJ4AxOTLWifkDjXIML.RG3C.u/fVS', 'Karim', 'Meziane', '0555345678', '654 Rue Didouche, Blida', 'lecteur', 'active', NOW());

-- ============================================
-- CATÉGORIES PRINCIPALES
-- ============================================
INSERT INTO categories (name, slug, parent_id, description, icon, color, display_order, is_featured, status) VALUES
-- Catégories principales
('Romans', 'romans', NULL, 'Romans et fiction littéraire', 'bi-book', '#e74c3c', 1, 1, 'active'),
('Sciences', 'sciences', NULL, 'Sciences et technologies', 'bi-lightbulb', '#3498db', 2, 1, 'active'),
('Histoire', 'histoire', NULL, 'Histoire et géographie', 'bi-clock-history', '#f39c12', 3, 1, 'active'),
('Art et Culture', 'art-culture', NULL, 'Art, musique, culture', 'bi-palette', '#9b59b6', 4, 1, 'active'),
('Développement Personnel', 'dev-personnel', NULL, 'Développement personnel et bien-être', 'bi-heart', '#1abc9c', 5, 1, 'active'),
('Jeunesse', 'jeunesse', NULL, 'Livres pour enfants et adolescents', 'bi-balloon', '#e67e22', 6, 1, 'active'),
('Scolaire', 'scolaire', NULL, 'Manuels et livres scolaires', 'bi-mortarboard', '#27ae60', 7, 1, 'active'),
('Bandes Dessinées', 'bd', NULL, 'BD, Comics et Mangas', 'bi-journal-richtext', '#34495e', 8, 1, 'active');

-- Sous-catégories pour Romans
INSERT INTO categories (name, slug, parent_id, description, display_order, status) VALUES
('Roman Policier', 'roman-policier', 1, 'Romans policiers et thrillers', 1, 'active'),
('Science-Fiction', 'science-fiction', 1, 'Science-fiction et anticipation', 2, 'active'),
('Fantasy', 'fantasy', 1, 'Fantasy et fantastique', 3, 'active'),
('Romance', 'romance', 1, 'Romans d\'amour', 4, 'active'),
('Roman Historique', 'roman-historique', 1, 'Romans historiques', 5, 'active');

-- Sous-catégories pour Sciences
INSERT INTO categories (name, slug, parent_id, description, display_order, status) VALUES
('Mathématiques', 'mathematiques', 2, 'Livres de mathématiques', 1, 'active'),
('Physique', 'physique', 2, 'Physique et astronomie', 2, 'active'),
('Biologie', 'biologie', 2, 'Biologie et sciences naturelles', 3, 'active'),
('Informatique', 'informatique', 2, 'Informatique et programmation', 4, 'active'),
('Médecine', 'medecine', 2, 'Médecine et santé', 5, 'active');

-- ============================================
-- NIVEAUX SCOLAIRES
-- ============================================
-- Primaire
INSERT INTO school_levels (name, slug, level_order, description, age_range, status) VALUES
('1ère Année Primaire', '1ere-primaire', 1, 'Première année de l\'enseignement primaire', '6-7 ans', 'active'),
('2ème Année Primaire', '2eme-primaire', 2, 'Deuxième année de l\'enseignement primaire', '7-8 ans', 'active'),
('3ème Année Primaire', '3eme-primaire', 3, 'Troisième année de l\'enseignement primaire', '8-9 ans', 'active'),
('4ème Année Primaire', '4eme-primaire', 4, 'Quatrième année de l\'enseignement primaire', '9-10 ans', 'active'),
('5ème Année Primaire', '5eme-primaire', 5, 'Cinquième année de l\'enseignement primaire', '10-11 ans', 'active'),

-- Moyen (CEM)
('1ère Année Moyenne', '1ere-moyenne', 6, 'Première année du collège', '11-12 ans', 'active'),
('2ème Année Moyenne', '2eme-moyenne', 7, 'Deuxième année du collège', '12-13 ans', 'active'),
('3ème Année Moyenne', '3eme-moyenne', 8, 'Troisième année du collège', '13-14 ans', 'active'),
('4ème Année Moyenne (BEM)', '4eme-moyenne', 9, 'Quatrième année du collège (BEM)', '14-15 ans', 'active'),

-- Secondaire (Lycée)
('1ère Année Secondaire', '1ere-secondaire', 10, 'Première année du lycée', '15-16 ans', 'active'),
('2ème Année Secondaire', '2eme-secondaire', 11, 'Deuxième année du lycée', '16-17 ans', 'active'),
('3ème Année Secondaire (BAC)', '3eme-secondaire', 12, 'Troisième année du lycée (BAC)', '17-18 ans', 'active');

-- ============================================
-- MATIÈRES SCOLAIRES
-- ============================================
INSERT INTO school_subjects (name, slug, description, icon, color, display_order, status) VALUES
('Mathématiques', 'mathematiques', 'Mathématiques et calcul', 'bi-calculator', '#3498db', 1, 'active'),
('Français', 'francais', 'Langue française', 'bi-book', '#e74c3c', 2, 'active'),
('Arabe', 'arabe', 'Langue arabe', 'bi-pen', '#27ae60', 3, 'active'),
('Anglais', 'anglais', 'Langue anglaise', 'bi-translate', '#f39c12', 4, 'active'),
('Sciences Naturelles', 'sciences-naturelles', 'Biologie et sciences de la vie', 'bi-flower1', '#1abc9c', 5, 'active'),
('Physique', 'physique', 'Physique et chimie', 'bi-lightning', '#9b59b6', 6, 'active'),
('Histoire', 'histoire', 'Histoire', 'bi-clock-history', '#e67e22', 7, 'active'),
('Géographie', 'geographie', 'Géographie', 'bi-globe', '#16a085', 8, 'active'),
('Education Islamique', 'education-islamique', 'Education islamique', 'bi-star', '#2c3e50', 9, 'active'),
('Education Civique', 'education-civique', 'Education civique et morale', 'bi-people', '#34495e', 10, 'active'),
('Informatique', 'informatique', 'Informatique et TIC', 'bi-laptop', '#3498db', 11, 'active'),
('Technologie', 'technologie', 'Technologie', 'bi-gear', '#95a5a6', 12, 'active');

-- ============================================
-- LIVRES
-- ============================================
INSERT INTO books (isbn, title, author, publisher, publication_year, category, category_id, description, cover_image, total_copies, available_copies, status, created_by) VALUES
-- Romans
('9782070612758', 'Le Petit Prince', 'Antoine de Saint-Exupéry', 'Gallimard', 1943, 'Romans', 1, 'Un conte philosophique et poétique sous l\'apparence d\'un conte pour enfants.', NULL, 5, 5, 'active', 1),
('9782253004226', 'Les Misérables', 'Victor Hugo', 'Le Livre de Poche', 1862, 'Romans', 1, 'Roman social et épique de Victor Hugo.', NULL, 3, 2, 'active', 1),
('9782070360024', 'L\'Étranger', 'Albert Camus', 'Gallimard', 1942, 'Romans', 1, 'Premier roman d\'Albert Camus.', NULL, 4, 4, 'active', 1),
('9782253002208', 'Le Comte de Monte-Cristo', 'Alexandre Dumas', 'Le Livre de Poche', 1844, 'Romans', 1, 'Roman d\'aventures historique.', NULL, 3, 3, 'active', 1),
('9782070368228', '1984', 'George Orwell', 'Gallimard', 1949, 'Romans', 1, 'Dystopie totalitaire célèbre.', NULL, 5, 4, 'active', 1),

-- Sciences
('9782100720200', 'Physique pour les Sciences', 'Eugene Hecht', 'Dunod', 2015, 'Sciences', 2, 'Manuel de physique générale.', NULL, 4, 4, 'active', 2),
('9782804184568', 'Biologie Générale', 'Neil Campbell', 'De Boeck', 2012, 'Sciences', 2, 'Introduction à la biologie.', NULL, 3, 3, 'active', 2),
('9782100738212', 'Mathématiques L1', 'Jean-Pierre Ramis', 'Dunod', 2016, 'Sciences', 2, 'Cours de mathématiques niveau L1.', NULL, 5, 5, 'active', 2),

-- Jeunesse
('9782070612345', 'Harry Potter à l\'école des sorciers', 'J.K. Rowling', 'Gallimard', 1998, 'Jeunesse', 6, 'Premier tome de la saga Harry Potter.', NULL, 6, 5, 'active', 2),
('9782070643028', 'Le Lion, la Sorcière Blanche et l\'Armoire', 'C.S. Lewis', 'Gallimard', 1950, 'Jeunesse', 6, 'Premier tome des Chroniques de Narnia.', NULL, 4, 4, 'active', 2),

-- Histoire
('9782070368228', 'Histoire de l\'Algérie', 'Benjamin Stora', 'La Découverte', 2004, 'Histoire', 3, 'Histoire complète de l\'Algérie.', NULL, 3, 3, 'active', 1),
('9782070453201', 'Histoire de France', 'Jules Michelet', 'Flammarion', 2008, 'Histoire', 3, 'Histoire générale de France.', NULL, 4, 4, 'active', 1);

-- ============================================
-- ÉVÉNEMENTS
-- ============================================
INSERT INTO events (title, description, event_date, event_time, location, max_participants, current_participants, status, created_by) VALUES
('Rencontre avec l\'auteur', 'Rencontre exceptionnelle avec un auteur algérien célèbre', '2025-02-15', '18:00:00', 'Salle de conférence', 50, 0, 'upcoming', 2),
('Club de lecture - Romans', 'Discussion mensuelle du club de lecture autour des romans classiques', '2025-02-20', '19:00:00', 'Espace lecture', 20, 0, 'upcoming', 2),
('Atelier d\'écriture créative', 'Atelier créatif pour tous les niveaux avec un écrivain professionnel', '2025-03-01', '14:00:00', 'Salle 2', 15, 0, 'upcoming', 2),
('Journée du livre scolaire', 'Présentation des nouveaux manuels scolaires', '2025-03-10', '10:00:00', 'Hall principal', 100, 0, 'upcoming', 2),
('Conte pour enfants', 'Séance de conte animée pour les enfants de 5 à 10 ans', '2025-02-25', '15:00:00', 'Espace jeunesse', 30, 0, 'upcoming', 2);

-- ============================================
-- ANNONCES
-- ============================================
INSERT INTO announcements (title, content, type, status, display_order, created_by) VALUES
('Bienvenue !', 'Bienvenue dans notre bibliothèque en ligne ! Inscrivez-vous gratuitement pour accéder à des milliers de livres.', 'success', 'active', 1, 1),
('Nouveaux horaires', 'À partir du 1er février, la bibliothèque sera ouverte du lundi au samedi de 9h à 19h.', 'info', 'active', 2, 1),
('Nouvelle section scolaire', 'Découvrez notre nouvelle section dédiée aux livres et exercices scolaires pour tous les niveaux !', 'success', 'active', 3, 1);

-- ============================================
-- NEWS (CAROUSEL)
-- ============================================
INSERT INTO news (title, subtitle, content, image, link, display_order, status, created_by) VALUES
('Plus de 10 000 livres disponibles', 'Accédez à notre collection complète', 'Romans, sciences, histoire, jeunesse et bien plus encore', NULL, '/categories', 1, 'active', 1),
('Espace Scolaire Complet', 'Manuels et exercices pour tous les niveaux', 'Du primaire au lycée, trouvez tous vos livres scolaires', NULL, '/school', 2, 'active', 1),
('Événements Culturels', 'Participez à nos rencontres et ateliers', 'Découvrez notre programme d\'événements littéraires', NULL, '/events', 3, 'active', 1);

-- ============================================
-- PUBLICITÉS
-- ============================================
INSERT INTO advertisements (title, company_name, contact_email, contact_phone, ad_type, image_url, link_url, description, start_date, end_date, status, price, payment_status, display_pages, created_by) VALUES
('Librairie Moderne - Tout pour vos études', 'Librairie du Centre', 'contact@librairie-centre.dz', '0555111222', 'banner_top', NULL, 'https://www.exemple-librairie.com', 'Votre librairie de proximité - Fournitures scolaires et livres', '2025-01-01', '2025-12-31', 'active', 500.00, 'paid', '["home", "books", "school"]', 1),

('Papeterie Express - Rentrée 2025', 'Papeterie Express', 'info@papeterie-express.dz', '0555333444', 'banner_side', NULL, 'https://www.exemple-papeterie.com', 'Tout pour la rentrée scolaire à prix mini', '2025-01-01', '2025-12-31', 'active', 300.00, 'paid', '["home", "school"]', 1),

('Formation en Ligne EduPro', 'EduPro Academy', 'contact@edupro.dz', '0555555666', 'banner_bottom', NULL, 'https://www.exemple-edupro.com', 'Cours en ligne certifiés - Soutien scolaire tous niveaux', '2025-01-01', '2025-06-30', 'active', 400.00, 'paid', '["home", "school", "categories"]', 1);

-- ============================================
-- EXERCICES (Exemples)
-- ============================================
INSERT INTO exercises (title, description, school_level_id, subject_id, difficulty, duration_minutes, file_path, file_type, has_solutions, status, created_by) VALUES
-- 1ère Année Primaire
('Exercices de calcul - Addition simple', 'Exercices d\'addition pour débutants avec nombres de 1 à 10', 1, 1, 'facile', 30, NULL, 'pdf', 1, 'active', 2),
('Lecture et compréhension', 'Exercices de lecture de mots simples', 1, 2, 'facile', 20, NULL, 'pdf', 1, 'active', 2),

-- 3ème Année Primaire
('Tables de multiplication', 'Exercices sur les tables de multiplication de 1 à 10', 3, 1, 'moyen', 45, NULL, 'pdf', 1, 'active', 2),
('Conjugaison - Présent', 'Exercices de conjugaison au présent', 3, 2, 'moyen', 40, NULL, 'pdf', 1, 'active', 2),

-- 4ème Année Moyenne
('Algèbre - Équations du premier degré', 'Résolution d\'équations simples', 9, 1, 'moyen', 60, NULL, 'pdf', 1, 'active', 2),
('Physique - Les forces', 'Exercices sur les forces et le mouvement', 9, 6, 'moyen', 50, NULL, 'pdf', 1, 'active', 2),

-- 3ème Année Secondaire (BAC)
('Sujet type BAC - Mathématiques', 'Sujet complet type examen du BAC', 12, 1, 'difficile', 180, NULL, 'pdf', 1, 'active', 2),
('Sujet type BAC - Sciences Naturelles', 'Sujet complet type examen du BAC', 12, 5, 'difficile', 180, NULL, 'pdf', 1, 'active', 2);

-- ============================================
-- TAGS POUR EXERCICES
-- ============================================
INSERT INTO exercise_tags (name, slug) VALUES
('Algèbre', 'algebre'),
('Géométrie', 'geometrie'),
('Grammaire', 'grammaire'),
('Conjugaison', 'conjugaison'),
('Orthographe', 'orthographe'),
('Lecture', 'lecture'),
('Écriture', 'ecriture'),
('Compréhension', 'comprehension'),
('Vocabulaire', 'vocabulaire'),
('Révision', 'revision'),
('Examen Blanc', 'examen-blanc'),
('Contrôle', 'controle'),
('Devoir', 'devoir'),
('BAC', 'bac'),
('BEM', 'bem');

-- ============================================
-- RECHERCHES POPULAIRES
-- ============================================
INSERT INTO popular_searches (search_term, search_count, last_searched) VALUES
('mathématiques', 250, NOW()),
('français', 200, NOW()),
('sciences', 180, NOW()),
('histoire', 150, NOW()),
('exercices bac', 300, NOW()),
('livre primaire', 170, NOW()),
('physique', 160, NOW()),
('géométrie', 140, NOW()),
('conjugaison', 130, NOW()),
('arabe', 120, NOW());