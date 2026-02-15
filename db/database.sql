DROP DATABASE IF EXISTS nsouit_biblioteca;

CREATE DATABASE nsouit_biblioteca;

USE nsouit_biblioteca;

-- creazione tabelle
CREATE TABLE casa_editrice (
    IDcasa_editrice INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    nome VARCHAR(30) NOT NULL UNIQUE,

    PRIMARY KEY (IDcasa_editrice)
);

CREATE TABLE genere (
    IDgenere INT(2) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    nome VARCHAR(30) NOT NULL,

    PRIMARY KEY(IDgenere)
);

CREATE TABLE libro (
    isbn VARCHAR(13) NOT NULL,
    titolo VARCHAR(30) NOT NULL,
    anno_pubblicazione YEAR NOT NULL,

    casa_editrice INT(5) UNSIGNED ZEROFILL NOT NULL,
    genere INT(2) UNSIGNED ZEROFILL NOT NULL,

    PRIMARY KEY(isbn),

    CONSTRAINT libro_ibfk_0
        FOREIGN KEY(casa_editrice) REFERENCES casa_editrice(IDcasa_editrice)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT libro_ibfk_1
        FOREIGN KEY(genere) REFERENCES genere(IDgenere)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE stato_copia_libro (
    IDstato_libro INT(2) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    stato VARCHAR(30) NOT NULL UNIQUE,

    PRIMARY KEY (IDstato_libro),
    INDEX(stato)
);

CREATE TABLE copia_libro (
    IDcopia INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    isbn VARCHAR(13) NOT NULL,
    stato INT(2) UNSIGNED ZEROFILL NOT NULL,

    PRIMARY KEY(IDcopia),

    CONSTRAINT copia_libro_ibfk_0
        FOREIGN KEY(isbn) REFERENCES libro(isbn)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT copia_libro_ibfk_1
        FOREIGN KEY(stato) REFERENCES stato_copia_libro(IDstato_libro)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE autore (
    IDautore INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    nome VARCHAR(30) NOT NULL,
    cognome VARCHAR(30) NOT NULL,
    data_nascita DATE NOT NULL,

    PRIMARY KEY(IDautore)
);

CREATE TABLE libri_scritti_autore (
    isbn VARCHAR(13) NOT NULL,
    IDautore INT(5) UNSIGNED ZEROFILL NOT NULL,

    PRIMARY KEY(isbn, IDautore),

    CONSTRAINT libri_scritti_autore_ibfk_0
        FOREIGN KEY(isbn) REFERENCES libro(isbn)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT libri_scritti_autore_ibfk_1
        FOREIGN KEY(IDautore) REFERENCES autore(IDautore)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE tipo_utente (
    IDtipo INT(2) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    tipo VARCHAR(30) NOT NULL UNIQUE,

    PRIMARY KEY(IDtipo),
    INDEX(tipo)
);

CREATE TABLE utente (
    IDutente INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    cf VARCHAR(16) NOT NULL UNIQUE,
    nome VARCHAR(30) NOT NULL,
    cognome VARCHAR(30) NOT NULL,
    data_nascita DATE NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    passwd VARCHAR(64) NOT NULL, /*sha256*/
    tipo INT(2) UNSIGNED ZEROFILL NOT NULL,

    PRIMARY KEY(IDutente),
    INDEX(cf),
    INDEX(email),

    CONSTRAINT utente_ibfk_0
        FOREIGN KEY(tipo) REFERENCES tipo_utente(IDtipo)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE stato_prenotazione (
    IDstato_prenotazione INT(1) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    stato VARCHAR(50) NOT NULL UNIQUE,

    PRIMARY KEY(IDstato_prenotazione),
    INDEX(stato)
);

CREATE TABLE prenotazione (
    IDprenotazione INT UNSIGNED AUTO_INCREMENT NOT NULL,
    cf VARCHAR(16) NOT NULL,
    isbn VARCHAR(13) NOT NULL,
    data_richiesta DATE NOT NULL,
    stato INT(1) UNSIGNED ZEROFILL NOT NULL,

    PRIMARY KEY(IDprenotazione),

    CONSTRAINT prenotazione_autore_ibfk_0
        FOREIGN KEY(cf) REFERENCES utente(cf)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT prenotazione_autore_ibfk_1
        FOREIGN KEY(isbn) REFERENCES libro(isbn)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT prenotazione_autore_ibfk_2
        FOREIGN KEY(stato) REFERENCES stato_prenotazione(IDstato_prenotazione)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE prestito (
    IDprestito INT UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    cf VARCHAR(16) NOT NULL,
    IDcopia INT(5) UNSIGNED ZEROFILL NOT NULL,
    data_inizio DATE NOT NULL,
    data_fine_prevista DATE NOT NULL,
    data_fine_effettiva DATE NULL,

    PRIMARY KEY(IDprestito),

    CONSTRAINT prestiti_autore_ibfk_0
        FOREIGN KEY(cf) REFERENCES utente(cf)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT prestiti_autore_ibfk_1
        FOREIGN KEY(IDcopia) REFERENCES copia_libro(IDcopia)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE stato_multa (
    IDstato_multa INT UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    stato VARCHAR(40) NOT NULL UNIQUE,

    PRIMARY KEY(IDstato_multa),
    INDEX(stato)
);

CREATE TABLE multa (
    IDmulta INT UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
    IDprestito INT(7) UNSIGNED ZEROFILL NOT NULL UNIQUE,
    costo DECIMAL(5, 2) NOT NULL,
    stato INT UNSIGNED ZEROFILL NOT NULL,

    PRIMARY KEY(IDmulta),

    CONSTRAINT multa_ibfk_0
        FOREIGN KEY(IDprestito) REFERENCES prestito(IDprestito)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT multa_ibfk_1
        FOREIGN KEY(stato) REFERENCES stato_multa(IDstato_multa)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- inserimento dati all'interno delle tabelle
INSERT INTO genere (nome) VALUES
('Romanzo'),
('Saggio'),
('Fantasy'),
('Thriller'),
('Storico'),
('Fantascienza'),
('Biografia');

INSERT INTO casa_editrice (nome) VALUES
('Mondadori'),
('Feltrinelli'),
('Einaudi'),
('Rizzoli'),
('Giunti'),
('Newton Compton'),
('Laterza');

INSERT INTO tipo_utente (tipo) VALUES
('Studente'),
('Docente');

/*
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

INSERT INTO libri_scritti_autore (isbn, IDAutore) VALUES
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

INSERT INTO copia_libro (isbn) VALUES
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
INSERT INTO prenotazione (cf, isbn, data_richiesta, stato) VALUES
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
INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'BNCLRA90F41F205X', 1, '2025-01-11', '2025-01-25'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'BNCLRA90F41F205X'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'NRANNA02C62H501T', 4, '2025-01-13', '2025-01-27'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'NRANNA02C62H501T'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'FRRMRC95D15F839K', 7, '2025-02-02', '2025-02-16'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'FRRMRC95D15F839K'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'ESPSRA01H45F205R', 10, '2025-02-06', '2025-02-20'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'ESPSRA01H45F205R'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'RMNGNN99S20H501U', 13, '2025-02-09', '2025-02-23'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'RMNGNN99S20H501U'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'GLLMRA92L03F205A', 16, '2025-02-11', '2025-02-25'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'GLLMRA92L03F205A'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'LNGFRC97T10F839M', 19, '2025-03-02', '2025-03-16'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'LNGFRC97T10F839M'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'BNCLRA90F41F205X', 2, '2025-03-06', '2025-03-20'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'BNCLRA90F41F205X'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'RSSMRA85M01H501Z', 3, '2025-03-05', '2025-03-19'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'RSSMRA85M01H501Z'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'VRDGPP78A12L219Y', 5, '2025-03-07', '2025-03-21'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'VRDGPP78A12L219Y'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'CNTLCA88E50G273P', 6, '2025-03-08', '2025-03-22'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'CNTLCA88E50G273P'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'DMRPLA80B18H501C', 9, '2025-03-19', '2025-04-02'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'DMRPLA80B18H501C'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'BRNRRT75R25G273E', 14, '2025-03-21', '2025-04-04'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'BRNRRT75R25G273E'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'NRANNA02C62H501T', 17, '2025-03-23', '2025-04-06'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'NRANNA02C62H501T'
         AND data_fine_effettiva IS NULL) < 3;

INSERT INTO prestito (cf, IDcopia, data_inizio, data_fine_prevista)
SELECT 'ESPSRA01H45F205R', 20, '2025-03-26', '2025-04-09'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE cf = 'ESPSRA01H45F205R'
         AND data_fine_effettiva IS NULL) < 3;


-- se alcune copie sono state prestate, canbio lo stato a `In prestito`
UPDATE copia_libro CL
JOIN prestito P ON CL.IDcopia = P.IDcopia
SET CL.stato = 'In prestito'
WHERE P.data_fine_effettiva IS NULL;

-- faccio l'update delle prenotazioni che sono pronte
UPDATE prenotazione P
JOIN prestito PR ON P.cf = PR.cf
JOIN copia_libro CL ON PR.IDcopia = CL.IDcopia
SET P.stato = 'Completata'
WHERE CL.isbn = P.isbn
  AND PR.data_fine_effettiva IS NULL
  AND P.stato LIKE 'Pronto per il prestito';

-- alcuni libli vengono restituiti
UPDATE prestito
SET data_fine_effettiva = '2025-03-30'
WHERE IDcopia = 1;

UPDATE prestito
SET data_fine_effettiva = '2025-04-30'
WHERE IDcopia = 7;

UPDATE prestito
SET data_fine_effettiva = '2025-09-02'
WHERE IDcopia = 5;

UPDATE prestito
SET data_fine_effettiva = '2025-12-30'
WHERE IDcopia = 3;

UPDATE prestito
SET data_fine_effettiva = '2026-01-20'
WHERE IDcopia = 2;

-- dopo che alcuni sono rientrati aggiono la tabella copia_libro per rimetterli di nuovo disponibili
-- se la data fine essettiva di un prestito è settato allora il libro è rientrato, dunque torna disponibile
UPDATE copia_libro CL
JOIN prestito P ON CL.IDcopia = P.IDcopia
SET CL.stato = 'Su scaffale'
WHERE P.data_fine_effettiva IS NOT NULL;

-- se i libri non vengono ritirati in 48, la prenotazione scade.

UPDATE prenotazione
SET stato = 'Decaduta'
WHERE stato = 'Pronto per il prestito' AND data_richiesta < NOW() - INTERVAL 2 DAY;

-- faccio un controllo per le multe e, per ogni giorno che è passato moltiplico 50 centesimi.
-- con datediff ottengo la i giorni di differenza fra le due date e, il risultato, dopo essere stato moltiplicato per 0.50, viene
--    memorizzato all'interno dell'attributo costo. la tupla è pronta per essere salvata nella tabella multa se verrà verificata la condizione all'interno del where
INSERT INTO multa (IDprestito, costo, stato)
SELECT 
    P.IDprestito,
    DATEDIFF(P.data_fine_effettiva, P.data_fine_prevista) * 0.50 AS costo,
    'Non pagata'
FROM prestito P
WHERE P.data_fine_effettiva IS NOT NULL
  AND P.data_fine_effettiva > P.data_fine_prevista;
-- non possono essere inseriti più multe per la stessa prenotazione dal momento che il campo IDprestito all'interno della tabella multa è unique.

-- una multa viene pagata
UPDATE multa
SET stato = 'Pagata'
WHERE IDmulta = 1;
*/
