-- Supprimer les contraintes de clé étrangère
PRAGMA foreign_keys = OFF;

-- Supprimer les tables avec contraintes de clé étrangère
DROP TABLE IF EXISTS "FAVORIS";
DROP TABLE IF EXISTS "ETRE";
DROP TABLE IF EXISTS "ALBUMS";
DROP TABLE IF EXISTS "PLAYLIST"; -- Nouvelle table
DROP TABLE IF EXISTS "NOTE";     -- Nouvelle table

-- Supprimer les tables sans contraintes de clé étrangère
DROP TABLE IF EXISTS "ARTISTES";
DROP TABLE IF EXISTS "GENRE";
DROP TABLE IF EXISTS "UTILISATEUR";

-- Réactiver les contraintes de clé étrangère
PRAGMA foreign_keys = ON;
