DROP DATABASE IF EXISTS 5cit_biblioteca;

CREATE DATABASE 5cit_biblioteca;

USE 5cit_biblioteca;

-- creazione tabelle
CREATE TABLE casaEditrice (
    nome VARCHAR(30) NOT NULL UNIQUE,
    PRIMARY KEY (nome)
);

CREATE TABLE genere (
    nome VARCHAR(30) NOT NULL,
    PRIMARY KEY(nome)
);

CREATE TABLE libro (
    ISBN VARCHAR(13) NOT NULL,
    titolo VARCHAR(30) NOT NULL,
    annoPubblicazione YEAR NOT NULL,

    casaEditrice VARCHAR(30) NOT NULL,
    genere VARCHAR(30) NOT NULL,

    PRIMARY KEY(ISBN),

    CONSTRAINT libro_ibfk_0
        FOREIGN KEY(casaEditrice) REFERENCES casaEditrice(nome)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT libro_ibfk_1
        FOREIGN KEY(genere) REFERENCES genere(nome)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE copia_libro (
    IDCopia INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    ISBN VARCHAR(13) NOT NULL,
    stato ENUM("Su scaffale", "In prestito") DEFAULT "Su scaffale" NOT NULL,

    PRIMARY KEY(IDCopia),

    CONSTRAINT copia_libro_ibfk_1
        FOREIGN KEY(ISBN) REFERENCES libro(ISBN)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE autore (
    IDAutore INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    nome VARCHAR(30) NOT NULL,
    cognome VARCHAR(30) NOT NULL,
    dataNascita DATE NOT NULL,

    PRIMARY KEY(IDAutore)
);

CREATE TABLE libri_scritti_autore (
    ISBN VARCHAR(13) NOT NULL,
    IDAutore INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,

    PRIMARY KEY(ISBN, IDAutore),

    CONSTRAINT libri_scritti_autore_ibfk_0
        FOREIGN KEY(ISBN) REFERENCES libro(ISBN)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT libri_scritti_autore_ibfk_1
        FOREIGN KEY(IDAutore) REFERENCES autore(IDAutore)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE tipo_utente (
    tipo VARCHAR(30) NOT NULL,

    PRIMARY KEY(tipo)
);

CREATE TABLE utente (
    CF VARCHAR(16) NOT NULL,
    nome VARCHAR(30) NOT NULL,
    cognome VARCHAR(30) NOT NULL,
    dataNascita DATE NOT NULL,
    username VARCHAR(30) UNIQUE NOT NULL,
    passwd VARCHAR(64) NOT NULL, /*sha256*/
    tipo VARCHAR(30) NOT NULL,

    CONSTRAINT utente_ibfk_0
        FOREIGN KEY(tipo) REFERENCES tipo_utente(tipo)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    PRIMARY KEY(CF)
);

CREATE TABLE prenotazione (
    IDPrenotazione INT(7) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    CF VARCHAR(16) NOT NULL,
    ISBN VARCHAR(13) NOT NULL,
    dataRichiesta DATE NOT NULL,
    stato ENUM("In attesa", "Pronto per il prestito", "Completata", "Decaduta") DEFAULT "In attesa" NOT NULL,

    PRIMARY KEY(IDPrenotazione),

    CONSTRAINT prenotazione_autore_ibfk_0
        FOREIGN KEY(CF) REFERENCES utente(CF)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT prenotazione_autore_ibfk_1
        FOREIGN KEY(ISBN) REFERENCES libro(ISBN)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE prestito (
    IDPrestito INT(7) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    CF VARCHAR(16) NOT NULL,
    IDCopia INT(5) UNSIGNED ZEROFILL NOT NULL,
    dataInizio DATE NOT NULL,
    dataFinePrevista DATE NOT NULL,
    dataFineEffettiva DATE NULL,

    PRIMARY KEY(IDPrestito),

    CONSTRAINT prestiti_autore_ibfk_0
        FOREIGN KEY(CF) REFERENCES utente(CF)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT prestiti_autore_ibfk_1
        FOREIGN KEY(IDCopia) REFERENCES copia_libro(IDCopia)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE multa (
    IDMulta INT(7) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    IDPrestito INT(7) UNSIGNED ZEROFILL NOT NULL UNIQUE,
    costo DECIMAL(5, 2) NOT NULL,
    stato ENUM("Pagata", "Non pagata") NOT NULL,

    PRIMARY KEY(IDMulta),

    CONSTRAINT multa_ibfk_0
        FOREIGN KEY(IDPrestito) REFERENCES prestito(IDPrestito)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- inserimento dati all'interno delle tabelle
INSERT INTO genere VALUES
('Romanzo'),
('Saggio'),
('Fantasy'),
('Thriller'),
('Storico'),
('Fantascienza'),
('Biografia');

INSERT INTO casaEditrice VALUES
('Mondadori'),
('Feltrinelli'),
('Einaudi'),
('Rizzoli'),
('Giunti'),
('Newton Compton'),
('Laterza');

INSERT INTO libro VALUES
('9788806219645', 'Il nome della rosa', 1980, 'Einaudi', 'Storico'),
('9788804681161', 'Norwegian Wood', 1987, 'Mondadori', 'Romanzo'),
('9788806229095', '1984', 1949, 'Rizzoli', 'Fantascienza'),
('9788807901983', 'Il Signore degli Anelli', 1954, 'Feltrinelli', 'Fantasy'),
('9788868363449', 'Sapiens', 2011, 'Giunti', 'Saggio'),
('9788822703578', 'Inferno', 2013, 'Newton Compton', 'Thriller'),
('9788842097580', 'Steve Jobs', 2011, 'Laterza', 'Biografia'),
('9788804684193', 'Il vecchio e il mare', 1952, 'Mondadori', 'Romanzo');

INSERT INTO autore (nome, cognome, dataNascita) VALUES
('Umberto', 'Eco', '1932-01-05'),
('Haruki', 'Murakami', '1949-01-12'),
('George', 'Orwell', '1903-06-25'),
('J.R.R.', 'Tolkien', '1892-01-03'),
('Christopher', 'Tolkien', '1924-11-21'),
('Yuval Noah', 'Harari', '1976-02-24'),
('Dan', 'Brown', '1964-06-22'),
('Walter', 'Isaacson', '1952-05-20'),
('Ernest', 'Hemingway', '1899-07-21');

INSERT INTO libri_scritti_autore (ISBN, IDAutore) VALUES
-- Il nome della rosa
('9788806219645', 1),

-- Norwegian Wood
('9788804681161', 2),

-- 1984
('9788806229095', 3),

-- Il Signore degli Anelli
('9788807901983', 4),

-- Sapiens
('9788868363449', 5),

-- Inferno
('9788822703578', 6),

-- Steve Jobs
('9788842097580', 7),

-- Il vecchio e il mare
('9788804684193', 8);

INSERT INTO copia_libro (ISBN) VALUES
-- Il nome della rosa
('9788806219645'),
('9788806219645'),
('9788806219645'),

-- Norwegian Wood
('9788804681161'),
('9788804681161'),
('9788804681161'),

-- 1984
('9788806229095'),
('9788806229095'),
('9788806229095'),

-- Il Signore degli Anelli
('9788807901983'),
('9788807901983'),
('9788807901983'),

-- Sapiens
('9788868363449'),
('9788868363449'),
('9788868363449'),

-- Inferno
('9788822703578'),
('9788822703578'),
('9788822703578'),

-- Steve Jobs
('9788842097580'),
('9788842097580'),
('9788842097580'),

-- Il vecchio e il mare
('9788804684193'),
('9788804684193'),
('9788804684193');

INSERT INTO tipo_utente VALUES
("Docente"),
("Utente");

/*
INSERT INTO utente VALUES
('RSSMRA85M01H501Z', 'Mario', 'Absolute', '1985-08-01', 'Docente'),
('BNCLRA90F41F205X', 'Laura', 'Bianchi', '1990-06-01', 'Studente'),
('VRDGPP78A12L219Y', 'Giuseppe', 'Verdi', '1978-01-12', 'Docente'),
('NRANNA02C62H501T', 'Anna', 'Neri', '2002-03-22', 'Studente'),
('FRRMRC95D15F839K', 'Marco', 'Ferrari', '1995-04-15', 'Studente'),
('CNTLCA88E50G273P', 'Luca', 'Conti', '1988-05-10', 'Docente'),
('ESPSRA01H45F205R', 'Sara', 'Esposito', '2001-08-05', 'Studente'),
('RMNGNN99S20H501U', 'Gianni', 'Romano', '1999-11-20', 'Studente'),
('GLLMRA92L03F205A', 'Marta', 'Orologio', '1992-07-03', 'Studente'),
('DMRPLA80B18H501C', 'Paolo', 'De Marco', '1980-02-18', 'Docente'),
('LNGFRC97T10F839M', 'Francesca', 'Longo', '1997-12-10', 'Studente'),
('BRNRRT75R25G273E', 'Roberto', 'Bruni', '1975-10-25', 'Docente');

-- vengono fatte delle prenotazioni, ma nessuna viene impostata a completata
INSERT INTO prenotazione (CF, ISBN, dataRichiesta, stato) VALUES
('BNCLRA90F41F205X', '9788806219645', '2025-01-10', 'In attesa'),
('NRANNA02C62H501T', '9788804681161', '2025-01-12', 'In attesa'),
('FRRMRC95D15F839K', '9788806229095', '2025-02-01', 'In attesa'),
('ESPSRA01H45F205R', '9788807901983', '2025-02-05', 'In attesa'),
('RMNGNN99S20H501U', '9788868363449', '2025-02-08', 'Pronto per il prestito'),
('GLLMRA92L03F205A', '9788822703578', '2025-02-10', 'In attesa'),
('LNGFRC97T10F839M', '9788842097580', '2025-03-01', 'In attesa'),
('BNCLRA90F41F205X', '9788804684193', '2025-03-05', 'Pronto per il prestito'),
('RSSMRA85M01H501Z', '9788806229095', '2025-03-10', 'In attesa'),
('VRDGPP78A12L219Y', '9788804681161', '2025-03-12', 'In attesa'),
('CNTLCA88E50G273P', '9788806219645', '2025-03-15', 'In attesa'),
('DMRPLA80B18H501C', '9788807901983', '2025-03-18', 'In attesa'),
('BRNRRT75R25G273E', '9788868363449', '2025-03-20', 'In attesa'),
('NRANNA02C62H501T', '9788822703578', '2025-03-22', 'Pronto per il prestito'),
('ESPSRA01H45F205R', '9788804684193', '2025-03-25', 'In attesa');

-- inserisco dei prestiti facendo il controllo per evitare che vengano inseriti più di 3 libri per utente
-- funzionamento descritto nella relazione
INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'BNCLRA90F41F205X', 1, '2025-01-11', '2025-01-25'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'BNCLRA90F41F205X'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'NRANNA02C62H501T', 4, '2025-01-13', '2025-01-27'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'NRANNA02C62H501T'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'FRRMRC95D15F839K', 7, '2025-02-02', '2025-02-16'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'FRRMRC95D15F839K'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'ESPSRA01H45F205R', 10, '2025-02-06', '2025-02-20'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'ESPSRA01H45F205R'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'RMNGNN99S20H501U', 13, '2025-02-09', '2025-02-23'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'RMNGNN99S20H501U'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'GLLMRA92L03F205A', 16, '2025-02-11', '2025-02-25'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'GLLMRA92L03F205A'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'LNGFRC97T10F839M', 19, '2025-03-02', '2025-03-16'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'LNGFRC97T10F839M'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'BNCLRA90F41F205X', 2, '2025-03-06', '2025-03-20'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'BNCLRA90F41F205X'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'RSSMRA85M01H501Z', 3, '2025-03-05', '2025-03-19'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'RSSMRA85M01H501Z'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'VRDGPP78A12L219Y', 5, '2025-03-07', '2025-03-21'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'VRDGPP78A12L219Y'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'CNTLCA88E50G273P', 6, '2025-03-08', '2025-03-22'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'CNTLCA88E50G273P'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'DMRPLA80B18H501C', 9, '2025-03-19', '2025-04-02'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'DMRPLA80B18H501C'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'BRNRRT75R25G273E', 14, '2025-03-21', '2025-04-04'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'BRNRRT75R25G273E'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'NRANNA02C62H501T', 17, '2025-03-23', '2025-04-06'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'NRANNA02C62H501T'
         AND dataFineEffettiva IS NULL) < 3;

INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'ESPSRA01H45F205R', 20, '2025-03-26', '2025-04-09'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'ESPSRA01H45F205R'
         AND dataFineEffettiva IS NULL) < 3;


-- se alcune copie sono state prestate, canbio lo stato a `In prestito`
UPDATE copia_libro CL
JOIN prestito P ON CL.IDCopia = P.IDCopia
SET CL.stato = 'In prestito'
WHERE P.dataFineEffettiva IS NULL;

-- faccio l'update delle prenotazioni che sono pronte
UPDATE prenotazione P
JOIN prestito PR ON P.CF = PR.CF
JOIN copia_libro CL ON PR.IDCopia = CL.IDCopia
SET P.stato = 'Completata'
WHERE CL.ISBN = P.ISBN
  AND PR.dataFineEffettiva IS NULL
  AND P.stato LIKE 'Pronto per il prestito';

-- alcuni libli vengono restituiti
UPDATE prestito
SET dataFineEffettiva = '2025-03-30'
WHERE IDCopia = 1;

UPDATE prestito
SET dataFineEffettiva = '2025-04-30'
WHERE IDCopia = 7;

UPDATE prestito
SET dataFineEffettiva = '2025-09-02'
WHERE IDCopia = 5;

UPDATE prestito
SET dataFineEffettiva = '2025-12-30'
WHERE IDCopia = 3;

UPDATE prestito
SET dataFineEffettiva = '2026-01-20'
WHERE IDCopia = 2;

-- dopo che alcuni sono rientrati aggiono la tabella copia_libro per rimetterli di nuovo disponibili
-- se la data fine essettiva di un prestito è settato allora il libro è rientrato, dunque torna disponibile
UPDATE copia_libro CL
JOIN prestito P ON CL.IDCopia = P.IDCopia
SET CL.stato = 'Su scaffale'
WHERE P.dataFineEffettiva IS NOT NULL;

-- se i libri non vengono ritirati in 48, la prenotazione scade.

UPDATE prenotazione
SET stato = 'Decaduta'
WHERE stato = 'Pronto per il prestito' AND dataRichiesta < NOW() - INTERVAL 2 DAY;

-- faccio un controllo per le multe e, per ogni giorno che è passato moltiplico 50 centesimi.
-- con datediff ottengo la i giorni di differenza fra le due date e, il risultato, dopo essere stato moltiplicato per 0.50, viene
--    memorizzato all'interno dell'attributo costo. la tupla è pronta per essere salvata nella tabella multa se verrà verificata la condizione all'interno del where
INSERT INTO multa (IDPrestito, costo, stato)
SELECT 
    P.IDPrestito,
    DATEDIFF(P.dataFineEffettiva, P.dataFinePrevista) * 0.50 AS costo,
    'Non pagata'
FROM prestito P
WHERE P.dataFineEffettiva IS NOT NULL
  AND P.dataFineEffettiva > P.dataFinePrevista;
-- non possono essere inseriti più multe per la stessa prenotazione dal momento che il campo IDPrestito all'interno della tabella multa è unique.

-- una multa viene pagata
UPDATE multa
SET stato = 'Pagata'
WHERE IDMulta = 1;
*/
