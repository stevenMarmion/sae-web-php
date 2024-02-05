CREATE TABLE "UTILISATEUR" (
	"idU"	INTEGER NOT NULL,
	"isAdmin"	INTEGER NOT NULL,
	"pseudo"	TEXT NOT NULL UNIQUE,
	"mdp"	TEXT NOT NULL,
	"adresseMail"	TEXT,
	PRIMARY KEY("idU" AUTOINCREMENT)
);

CREATE TABLE "GENRE" (
	"idG"	INTEGER NOT NULL,
	"nomG"	INTEGER NOT NULL UNIQUE,
	PRIMARY KEY("idG" AUTOINCREMENT)
);

CREATE TABLE "ARTISTES" (
	"idA"	INTEGER NOT NULL,
	"nomA"	TEXT NOT NULL UNIQUE,
	PRIMARY KEY("idA" AUTOINCREMENT)
);

CREATE TABLE "ALBUMS" (
	"id"	INTEGER NOT NULL,
	"img"	BLOB,
	"dateDeSortie"	INTEGER NOT NULL,
	"titre"	TEXT NOT NULL,
	PRIMARY KEY("id")
);

-- Table COMPOSER
CREATE TABLE COMPOSER (
    "idAl" INTEGER NOT NULL,
    "idA" INTEGER NOT NULL,
    FOREIGN KEY("idAl") REFERENCES "ALBUMS"("id"),
    FOREIGN KEY("idA") REFERENCES "ARTISTES"("idA"),
    PRIMARY KEY("idAl", "idA")
);

-- Table INTERPRETER
CREATE TABLE INTERPRETER (
    "idAl" INTEGER NOT NULL,
    "idA" INTEGER NOT NULL,
    FOREIGN KEY("idAl") REFERENCES "ALBUMS"("id"),
    FOREIGN KEY("idA") REFERENCES "ARTISTES"("idA"),
    PRIMARY KEY("idAl", "idA")
);

CREATE TABLE "ETRE" (
	"idAl"	INTEGER NOT NULL,
	"idG"	INTEGER NOT NULL,
	FOREIGN KEY("idG") REFERENCES "GENRE"("idG"),
	FOREIGN KEY("idAl") REFERENCES "ALBUMS"("id"),
	PRIMARY KEY("idAl","idG")
);

CREATE TABLE "FAVORIS" (
	"idU"	INTEGER NOT NULL,
	"idAl"	INTEGER NOT NULL,
	FOREIGN KEY("idAl") REFERENCES "ALBUMS"("id"),
	FOREIGN KEY("idU") REFERENCES "UTILISATEUR"("idU"),
	PRIMARY KEY("idU","idAl")
);

-- Table PLAYLIST
CREATE TABLE PLAYLIST (
    "idPlaylist" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    "idU" INTEGER NOT NULL,
    "nomPlaylist" TEXT NOT NULL,
    FOREIGN KEY("idU") REFERENCES "UTILISATEUR"("idU")
);

-- Table NOTE
CREATE TABLE NOTE (
    "idAl" INTEGER NOT NULL,
    "idU" INTEGER NOT NULL,
    "note" REAL CHECK(note >= 0 AND note <= 5) NOT NULL,
    FOREIGN KEY("idAl") REFERENCES "ALBUMS"("id"),
    FOREIGN KEY("idU") REFERENCES "UTILISATEUR"("idU"),
    PRIMARY KEY("idAl", "idU")
);

-- Table CONTENIR
CREATE TABLE CONTENIR (
    "idPlaylist" INTEGER NOT NULL,
    "idAl" INTEGER NOT NULL,
    FOREIGN KEY("idPlaylist") REFERENCES "PLAYLIST"("idPlaylist"),
    FOREIGN KEY("idAl") REFERENCES "ALBUMS"("id"),
    PRIMARY KEY("idPlaylist", "idAl")
);
