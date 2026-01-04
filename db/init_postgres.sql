-- Schéma PostgreSQL initial pour Menma-Shop

CREATE TABLE IF NOT EXISTS produits (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nom varchar(255) NOT NULL,
    prix numeric(10,2) NOT NULL DEFAULT 0.00,
    stock integer NOT NULL DEFAULT 0,
    description text,
    image_url varchar(1024),
    image_url2 varchar(1024),
    image_url3 varchar(1024),
    image_url4 varchar(1024),
    image_url5 varchar(1024),
    video_url varchar(1024),
    created_at timestamp with time zone DEFAULT now()
);

CREATE TABLE IF NOT EXISTS commentaires (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_produit integer NOT NULL REFERENCES produits(id) ON DELETE CASCADE,
    nom_client varchar(255) NOT NULL,
    note integer,
    texte text,
    created_at timestamp with time zone DEFAULT now()
);

CREATE TABLE IF NOT EXISTS commandes (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_produit integer NOT NULL REFERENCES produits(id),
    nom_client varchar(255) NOT NULL,
    adresse_livraison text NOT NULL,
    statut_livraison varchar(50) NOT NULL DEFAULT 'en attente',
    created_at timestamp with time zone DEFAULT now()
);

-- Indexes d'exemple
CREATE INDEX IF NOT EXISTS idx_commentaires_id_produit ON commentaires(id_produit);
CREATE INDEX IF NOT EXISTS idx_commandes_id_produit ON commandes(id_produit);
