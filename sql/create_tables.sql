-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Kayttaja (
	id SERIAL PRIMARY KEY,
	kayttajatunnus varchar(20) UNIQUE NOT NULL,
	salasana varchar(255) NOT NULL,
	yllapitaja boolean DEFAULT FALSE   
);

CREATE TABLE Aanestys (
	id SERIAL PRIMARY KEY,
	tekija INTEGER REFERENCES Kayttaja(id),   -- Viite äänestyksen luojaan
	luotu timestamp DEFAULT CURRENT_TIMESTAMP,
	aihe varchar(50) NOT NULL,
	kuvaus varchar(1000),
	alkupvm date NOT NULL,
	loppupvm date NOT NULL,
	piilotettu boolean DEFAULT FALSE NOT NULL,
	tyyppi INTEGER DEFAULT 1
);

CREATE TABLE Aanestaneet (
	kayttaja INTEGER REFERENCES Kayttaja(id),
	aanestys INTEGER REFERENCES Aanestys(id)
);

CREATE TABLE Vaihtoehto (
	id SERIAL PRIMARY KEY,
	aanestys INTEGER REFERENCES Aanestys(id), -- Mihin äänestykseen vaihtoehto liittyy
	nimi varchar(30) NOT NULL,
	lisatieto varchar(100)
);

CREATE TABLE Aani (
	id SERIAL PRIMARY KEY,
	vaihtoehto INTEGER REFERENCES Vaihtoehto(id), -- mihin vaihtoehtoon ääni liittyy
	aika TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);