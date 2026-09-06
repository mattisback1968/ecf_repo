CREATE TABLE role (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- =========================
-- TABLE UTILISATEUR
-- =========================
CREATE TABLE utilisateur (
    utilisateur_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    telephone VARCHAR(50),
    adresse VARCHAR(255),
    ville VARCHAR(50),
    pays VARCHAR(50)
);

-- =========================
-- TABLE UTILISATEUR_ROLE (possede)
-- =========================
CREATE TABLE utilisateur_role (
    utilisateur_id INT NOT NULL,
    role_id INT NOT NULL,
    PRIMARY KEY (utilisateur_id, role_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES role(role_id) ON DELETE CASCADE
);

-- =========================
-- TABLE THEME
-- =========================
CREATE TABLE theme (
    theme_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- =========================
-- TABLE REGIME
-- =========================
CREATE TABLE regime (
    regime_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- =========================
-- TABLE MENU
-- =========================
CREATE TABLE menu (
    menu_id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(50) NOT NULL,
    nombre_personne_minimum INT,
    prix_par_personne DOUBLE,
    description VARCHAR(255),
    quantite_restante INT
);

-- =========================
-- TABLE PLAT
-- =========================
CREATE TABLE plat (
    plat_id INT AUTO_INCREMENT PRIMARY KEY,
    titre_plat VARCHAR(50) NOT NULL,
    photo VARCHAR(255)
);

-- =========================
-- TABLE ALLERGENE
-- =========================
CREATE TABLE allergene (
    allergene_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- =========================
-- TABLE HORAIRE
-- =========================
CREATE TABLE horaire (
    horaire_id INT AUTO_INCREMENT PRIMARY KEY,
    jour VARCHAR(50),
    heure_ouverture TIME,
    heure_fermeture TIME
);

-- =========================
-- TABLE COMMANDE
-- =========================
CREATE TABLE commande (
    commande_id INT AUTO_INCREMENT PRIMARY KEY,
    numero_commande VARCHAR(50),
    date_commande DATE,
    date_prestation DATE,
    heure_livraison TIME,
    prix_menu DOUBLE,
    nombre_personne INT,
    prix_livraison DOUBLE,
    statut VARCHAR(50),
    pret_materiel BOOLEAN,
    restitution_materiel BOOLEAN,
    utilisateur_id INT,
    menu_id INT,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id) ON DELETE SET NULL,
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE SET NULL
);

-- =========================
-- TABLE AVIS
-- =========================
CREATE TABLE avis (
    avis_id INT AUTO_INCREMENT PRIMARY KEY,
    note INT,
    description VARCHAR(255),
    statut VARCHAR(50),
    utilisateur_id INT,
    menu_id INT,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id) ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE CASCADE
);

-- =========================
-- TABLE DE LIAISON MENU - PLAT (propose)
-- =========================
CREATE TABLE menu_plat (
    menu_id INT NOT NULL,
    plat_id INT NOT NULL,
    PRIMARY KEY (menu_id, plat_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE CASCADE,
    FOREIGN KEY (plat_id) REFERENCES plat(plat_id) ON DELETE CASCADE
);

-- =========================
-- TABLE DE LIAISON MENU - REGIME (adapte)
-- =========================
CREATE TABLE menu_regime (
    menu_id INT NOT NULL,
    regime_id INT NOT NULL,
    PRIMARY KEY (menu_id, regime_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE CASCADE,
    FOREIGN KEY (regime_id) REFERENCES regime(regime_id) ON DELETE CASCADE
);

-- =========================
-- TABLE DE LIAISON PLAT - ALLERGENE (contient)
-- =========================
CREATE TABLE plat_allergene (
    plat_id INT NOT NULL,
    allergene_id INT NOT NULL,
    PRIMARY KEY (plat_id, allergene_id),
    FOREIGN KEY (plat_id) REFERENCES plat(plat_id) ON DELETE CASCADE,
    FOREIGN KEY (allergene_id) REFERENCES allergene(allergene_id) ON DELETE CASCADE
);

-- =========================
-- TABLE DE LIAISON MENU - THEME (propose)
-- =========================
CREATE TABLE menu_theme (
    menu_id INT NOT NULL,
    theme_id INT NOT NULL,
    PRIMARY KEY (menu_id, theme_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE CASCADE,
    FOREIGN KEY (theme_id) REFERENCES theme(theme_id) ON DELETE CASCADE
);