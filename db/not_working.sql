DROP DATABASE IF EXISTS nsouit_biblioteca;

CREATE DATABASE nsouit_biblioteca;

USE nsouit_biblioteca;

CREATE TABLE autore (
  IDautore int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  nome_autore varchar(30) NOT NULL,
  cognome_autore varchar(30) NOT NULL,
  data_nascita_autore date NOT NULL,
  PRIMARY KEY (IDautore)
);

CREATE TABLE libro (
  isbn varchar(13) NOT NULL,
  titolo varchar(30) NOT NULL,
  anno_pubblicazione int(10) unsigned NOT NULL,
  IDcasa_editrice int(5) unsigned zerofill NOT NULL,
  IDgenere int(2) unsigned zerofill NOT NULL,
  PRIMARY KEY (isbn),
  KEY libro_ibfk_0 (IDcasa_editrice),
  KEY libro_ibfk_1 (IDgenere),
  CONSTRAINT libro_ibfk_0 FOREIGN KEY (IDcasa_editrice) REFERENCES casa_editrice (IDcasa_editrice) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT libro_ibfk_1 FOREIGN KEY (IDgenere) REFERENCES genere (IDgenere) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO autore(nome_autore, cognome_autore, data_nascita_autore) VALUES
('Umberto','Eco','1932-01-05'),
('Alessandro','Manzoni','1785-03-07'),
('Elena','Ferrante','1943-04-05'),
('Italo','Calvino','1923-10-15'),
('Primo','Levi','1919-07-31'),
('Valerio','Massimo Manfredi','1943-02-20'),
('Andrea','Camilleri','1925-09-06'),
('Roberto','Saviano','1979-09-22');


CREATE TABLE casa_editrice (
  IDcasa_editrice int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  nome_casa_editrice varchar(30) NOT NULL,
  PRIMARY KEY (IDcasa_editrice),
  UNIQUE KEY nome_casa_editrice (nome_casa_editrice)
);


INSERT INTO casa_editrice(nome_casa_editrice) VALUES
('Einaudi'),
('Feltrinelli'),
('Giunti'),
('Laterza'),
('Mondadori'),
('Newton Compton'),
('Rizzoli');

CREATE TABLE copia_libro (
  IDcopia int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  isbn varchar(13) NOT NULL,
  stato int(2) unsigned zerofill NOT NULL,
  PRIMARY KEY (IDcopia),
  KEY copia_libro_ibfk_0 (isbn),
  KEY copia_libro_ibfk_1 (stato),
  CONSTRAINT copia_libro_ibfk_0 FOREIGN KEY (isbn) REFERENCES libro (isbn) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT copia_libro_ibfk_1 FOREIGN KEY (stato) REFERENCES stato_copia_libro (IDstato_libro) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE genere (
  IDgenere int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  nome_genere varchar(30) NOT NULL,
  PRIMARY KEY (IDgenere)
);

INSERT INTO genere(nome_genere) VALUES
('Romanzo'),
('Saggio'),
('Fantasy'),
('Thriller'),
('Storico'),
('Fantascienza'),
('Biografia');

CREATE TABLE libri_scritti_autore (
  isbn varchar(13) NOT NULL,
  IDautore int(5) unsigned zerofill NOT NULL,
  PRIMARY KEY (isbn,IDautore),
  KEY libri_scritti_autore_ibfk_1 (IDautore),
  CONSTRAINT libri_scritti_autore_ibfk_0 FOREIGN KEY (isbn) REFERENCES libro (isbn) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT libri_scritti_autore_ibfk_1 FOREIGN KEY (IDautore) REFERENCES autore (IDautore) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO libri_scritti_autore VALUES
('9788845210662',1),
('9788817121149',2),
('9788866320326',3),
('9788804598893',4),
('8820101319',5),
('9788483007723',6),
('8838910170',7),
('9788483468463',8);

INSERT INTO copia_libro () VALUES
('9788845210662',1),
('9788817121149',2),
('9788866320326',3),
('9788804598893',4),
('8820101319',5),
('9788483007723',6),
('8838910170',7),
('9788483468463',8);



INSERT INTO libro VALUES
('8820101319','Se questo e un uomo',1947,00007,07),
('8838910170','La forma dell\'acqua',1994,00001,04),
('9788483007723','Alexandros',1998,00004,05),
('9788483468463','Gomorra',2006,00002,07),
('9788804598893','Il barone rampante',1957,00003,01),
('9788817121149','I promessi sposi',1827,00003,01),
('9788845210662','Il nome della rosa',1980,00001,05),
('9788866320326','L\'amica geniale',2011,00002,01);

CREATE TABLE multa (
  IDmulta int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  IDprestito int(7) unsigned zerofill NOT NULL,
  costo decimal(5,2) NOT NULL,
  stato int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (IDmulta),
  UNIQUE KEY IDprestito (IDprestito),
  KEY multa_ibfk_1 (stato),
  CONSTRAINT multa_ibfk_0 FOREIGN KEY (IDprestito) REFERENCES prestito (IDprestito) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT multa_ibfk_1 FOREIGN KEY (stato) REFERENCES stato_multa (IDstato_multa) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE prenotazione (
  IDprenotazione int(10) unsigned NOT NULL AUTO_INCREMENT,
  cf varchar(16) NOT NULL,
  isbn varchar(13) NOT NULL,
  data_richiesta date NOT NULL,
  stato int(1) unsigned zerofill NOT NULL,
  PRIMARY KEY (IDprenotazione),
  KEY prenotazione_autore_ibfk_0 (cf),
  KEY prenotazione_autore_ibfk_1 (isbn),
  KEY prenotazione_autore_ibfk_2 (stato),
  CONSTRAINT prenotazione_autore_ibfk_0 FOREIGN KEY (cf) REFERENCES utente (cf) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT prenotazione_autore_ibfk_1 FOREIGN KEY (isbn) REFERENCES libro (isbn) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT prenotazione_autore_ibfk_2 FOREIGN KEY (stato) REFERENCES stato_prenotazione (IDstato_prenotazione) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE prestito (
  IDprestito int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  cf varchar(16) NOT NULL,
  IDcopia int(5) unsigned zerofill NOT NULL,
  data_inizio date NOT NULL,
  data_fine_prevista date NOT NULL,
  data_fine_effettiva date DEFAULT NULL,
  PRIMARY KEY (IDprestito),
  KEY prestiti_autore_ibfk_0 (cf),
  KEY prestiti_autore_ibfk_1 (IDcopia),
  CONSTRAINT prestiti_autore_ibfk_0 FOREIGN KEY (cf) REFERENCES utente (cf) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT prestiti_autore_ibfk_1 FOREIGN KEY (IDcopia) REFERENCES copia_libro (IDcopia) ON DELETE CASCADE ON UPDATE CASCADE
);



CREATE TABLE stato_multa (
  IDstato_multa int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  stato varchar(40) NOT NULL,
  PRIMARY KEY (IDstato_multa),
  UNIQUE KEY stato (stato),
  KEY stato_2 (stato)
);

CREATE TABLE stato_prenotazione (
  IDstato_prenotazione int(1) unsigned zerofill NOT NULL AUTO_INCREMENT,
  stato varchar(50) NOT NULL,
  PRIMARY KEY (IDstato_prenotazione),
  UNIQUE KEY stato (stato),
  KEY stato_2 (stato)
);

CREATE TABLE tipo_utente (
  IDtipo int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  tipo varchar(30) NOT NULL,
  PRIMARY KEY (IDtipo),
  UNIQUE KEY tipo (tipo),
  KEY tipo_2 (tipo)
);

INSERT INTO tipo_utente(tipo) VALUES
('Bibliotecario'),
('Docente'),
('Studente');

CREATE TABLE utente (
  IDutente int(11) NOT NULL AUTO_INCREMENT,
  cf varchar(16) NOT NULL,
  nome varchar(128) NOT NULL,
  cognome varchar(128) NOT NULL,
  data_nascita date NOT NULL,
  email varchar(255) NOT NULL,
  passwd char(64) NOT NULL,
  salt char(64) NOT NULL,
  tipo int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (IDutente),
  UNIQUE KEY cf (cf),
  UNIQUE KEY email (email),
  KEY cf_2 (cf),
  KEY email_2 (email),
  KEY utente_ibfk_0 (tipo),
  CONSTRAINT utente_ibfk_0 FOREIGN KEY (tipo) REFERENCES tipo_utente (IDtipo) ON UPDATE CASCADE
);

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
INSERT INTO prenotazione (cf, isbn, data_richiesta, stato) VALUES
('BNCLRA90F41F205X', '9788845210662', '2025-01-10', 'In attesa'),
('NRANNA02C62H501T', '9780375704024', '2025-01-12', 'In attesa'),
('FRRMRC95D15F839K', '9781943138432', '2025-02-01', 'In attesa'),
('ESPSRA01H45F205R', '9788845210273', '2025-02-05', 'In attesa'),
('RMNGNN99S20H501U', '9788868363449', '2025-02-08', 'Pronto per il prestito'),
('GLLMRA92L03F205A', '9788822703578', '2025-02-10', 'In attesa'),
('LNGFRC97T10F839M', '9788842097580', '2025-03-01', 'In attesa'),
('BNCLRA90F41F205X', '9788804684193', '2025-03-05', 'Pronto per il prestito'),
('RSSMRA85M01H501Z', '9781943138432', '2025-03-10', 'In attesa'),
('VRDGPP78A12L219Y', '9780375704024', '2025-03-12', 'In attesa'),
('CNTLCA88E50G273P', '9788845210662', '2025-03-15', 'In attesa'),
('DMRPLA80B18H501C', '9788845210273', '2025-03-18', 'In attesa'),
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


-- se alcune copie sono state prestate, canbio lo stato a In prestito
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
