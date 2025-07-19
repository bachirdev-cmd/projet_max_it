CREATE TABLE IF NOT EXISTS users (
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


CREATE TABLE IF NOT EXISTS compte (
    id SERIAL PRIMARY KEY,
    numero VARCHAR(50),
    datecreation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    solde DECIMAL(15, 2) DEFAULT 0.00,
    numerotel VARCHAR(20) NOT NULL,
    typecompte VARCHAR(20) NOT NULL CHECK (typecompte IN ('principal', 'secondaire')), 
    userid INTEGER NOT NULL,
    FOREIGN KEY (userid) REFERENCES users(id)
);


CREATE TABLE IF NOT EXISTS transaction (
    id SERIAL PRIMARY KEY,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    typetransaction VARCHAR(20) NOT NULL CHECK (typetransaction IN ('depot', 'retrait', 'paiement')), 
    montant DECIMAL(15, 2) NOT NULL,
    compteid INTEGER NOT NULL,
    FOREIGN KEY (compteid) REFERENCES compte(id)
);


-- 1. Ajouter la colonne sans contrainte
ALTER TABLE compte ADD COLUMN IF NOT EXISTS numero VARCHAR(50);

-- 2. Mettre à jour les lignes existantes avec une valeur unique
UPDATE compte SET numero = 'CPT-' || id WHERE numero IS NULL;

-- 3. Ajouter la contrainte NOT NULL
DO $$
BEGIN
    IF EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name='compte' AND column_name='numero'
    ) THEN
        ALTER TABLE compte ALTER COLUMN numero SET NOT NULL;
    END IF;
END $$;

-- 4. Ajouter la contrainte UNIQUE
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.table_constraints 
        WHERE table_name='compte' AND constraint_type='UNIQUE' AND constraint_name='compte_numero_unique'
    ) THEN
        ALTER TABLE compte ADD CONSTRAINT compte_numero_unique UNIQUE (numero);
    END IF;
END $$;