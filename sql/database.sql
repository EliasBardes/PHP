-- Base de données AUTO-MATCH
CREATE DATABASE IF NOT EXISTS automatch CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE automatch;

-- Table des véhicules
CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    version VARCHAR(150) NOT NULL,
    year INT NOT NULL,
    mileage INT NOT NULL,
    fuel VARCHAR(50) NOT NULL,
    price INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des demandes de contact
CREATE TABLE IF NOT EXISTS contact_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(50) NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_contact_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des réservations
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT NOT NULL,
    user_id INT NULL,
    customer_name VARCHAR(150) NULL,
    customer_email VARCHAR(150) NULL,
    customer_phone VARCHAR(50) NULL,
    status VARCHAR(50) DEFAULT 'confirmée',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_res_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des utilisateurs (pour Mon Compte / Espace Client)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(50) NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'client',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Données initiales pour les 3 véhicules de la maquette Figma
INSERT INTO vehicles (id, brand, model, version, year, mileage, fuel, price, image, description)
VALUES 
(
    1,
    'BMW',
    'Série 2',
    'Active - 216i M Sport 5p',
    2018,
    74540,
    'Essence',
    18490,
    'public/images/bmw-serie-2.jpg',
    'BMW Série 2 Active Tourer finition M Sport avec boîte manuelle, motorisation 216i essence de 2018 avec 74 540 km. Véhicule en excellent état, révisé et garanti.'
),
(
    2,
    'Peugeot',
    '3008',
    'GT Line - 1.2 PureTech 130ch',
    2019,
    71821,
    'Diesel',
    19490,
    'public/images/peugeot-3008.jpg',
    'Peugeot 3008 finition haut de gamme GT Line motorisation 1.2 PureTech 130 chevaux, année 2019, 71 821 km. Confort exceptionnel et design dynamique.'
),
(
    3,
    'Honda',
    'Civic',
    'Executive - 1.0 i-VTEC 126',
    2019,
    93350,
    'Essence',
    14890,
    'public/images/honda-civic.jpg',
    'Honda Civic 1.0 i-VTEC finition Executive, année 2019, 93 350 km. Berline compacte spacieuse et fiable avec feux LED, régulateur adaptatif et caméra de recul.'
)
ON DUPLICATE KEY UPDATE 
    brand=VALUES(brand),
    model=VALUES(model),
    version=VALUES(version),
    year=VALUES(year),
    mileage=VALUES(mileage),
    fuel=VALUES(fuel),
    price=VALUES(price),
    image=VALUES(image),
    description=VALUES(description);

-- Utilisateur exemple pour test
INSERT INTO users (id, name, email, phone, password, role)
VALUES (
    1,
    'Camille Martin',
    'camille.martin@exemple.fr',
    '06 12 34 56 78',
    '$2y$10$Kgnf.9s1If86X4razMhDAek2X8VC.AUevXrQRRguiL7ccRVXr7zmK',
    'client'
)
ON DUPLICATE KEY UPDATE name=VALUES(name);
