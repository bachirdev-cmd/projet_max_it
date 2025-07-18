CREATE TABLE Users (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    login VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    numerocarteidentite VARCHAR(50) NULL UNIQUE,
    photorecto TEXT,
    photoverso TEXT,
    adresse VARCHAR(255),
    typeuser VARCHAR(20) NOT NULL CHECK (typeuser IN ('client', 'service_commercial')) 
);


CREATE TABLE Compte (
    id SERIAL PRIMARY KEY,
    datecreation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    solde DECIMAL(15, 2) DEFAULT 0.00,
    numerotel VARCHAR(20) NOT NULL,
    typecompte VARCHAR(20) NOT NULL CHECK (typecompte IN ('principal', 'secondaire')), 
    userid INTEGER NOT NULL,
    FOREIGN KEY (userid) REFERENCES Utilisateurs(id) 
);


CREATE TABLE Transaction (
    id SERIAL PRIMARY KEY,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    typetransaction VARCHAR(20) NOT NULL CHECK (typetransaction IN ('depot', 'retrait', 'paiement')), 
    montant DECIMAL(15, 2) NOT NULL,
    compteid INTEGER NOT NULL,
    FOREIGN KEY (compteid) REFERENCES compte(id)
);