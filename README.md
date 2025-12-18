# Biblioteca Scolastica
---

**Studente:** Nizar Souit  
**Classe:** 5C IT  
**Data:** 18/12/2025

---

## Indice
- [Biblioteca Scolastica](#biblioteca-scolastica)
  - [Indice](#indice)
  - [Consegna](#consegna)
  - [Entità](#entità)
  - [Introduzione al progetto](#introduzione-al-progetto)
  - [Analisi dei requisiti](#analisi-dei-requisiti)
    - [Copie dei libri](#copie-dei-libri)
    - [Vincolo di prestiti attivi per utente](#vincolo-di-prestiti-attivi-per-utente)
      - [Esempio in SQL](#esempio-in-sql)
    - [Scadenza prenotazione dopo 48h](#scadenza-prenotazione-dopo-48h)
  - [Diagramma ER](#diagramma-er)
  - [Schema logico](#schema-logico)
  - [Dizionario dei dati](#dizionario-dei-dati)
    - [Entità **libro**](#entità-libro)
    - [Entità **autore**](#entità-autore)
    - [Entità **casa\_editrice**](#entità-casa_editrice)
    - [Entità **genere**](#entità-genere)
    - [Entità **copia**](#entità-copia)
    - [Entità **utente**](#entità-utente)
    - [Entità **prenotazione**](#entità-prenotazione)
    - [Entità **prestito**](#entità-prestito)
    - [Entità **multa**](#entità-multa)
  - [Conclusioni](#conclusioni)
****

## Consegna
Realizzare un database per la gestione di una biblioteca scolastica che permetta di:
- Catalogare libri (titolo, autore, ISBN, genere, casa editrice, anno pubblicazione, numero copie)
- Gestire utenti (studenti e docenti) con dati anagrafici
- Registrare prestiti con date di prestito e restituzione
- Gestire prenotazioni di libri già in prestito
- Tracciare lo storico dei prestiti
- Gestire eventuali multe per ritardi

Vincoli: Un libro può avere più copie, un utente può avere più prestiti attivi (max 3), prenotazione decade dopo 48h se il libro non viene ritirato.

## Entità
- **Libro** --> l'entità centrale di questa base di dati.

- **Autore** --> autori dei libri

- **Case editrice** --> pubblica i libri

- **Genere** --> ogni libro deve e può avere più di un genere

- **Copia** --> entità che si basa sull'ID del libro, e lo ripropone per una o più volte per tener conto delle copie disponibili

- **Utente** --> utente, che può essere docente o studente, che prende in prestito i libri

- **Prenotazione** --> se il libro non è disponibile, può essere prenotato

- **Prestito** --> prestito vero e proprio di una delle copie del libro scelto

- **Multa** --> in caso di ritardo nella consegna del libro, viene generata una multa

## Introduzione al progetto
Il progetto ha come obiettivo la realizzazione di una base di dati per la gestione di una biblioteca scolastica. Il sistema consente di monitorare tutte le operazioni legate ai libri, agli utenti e ai servizi offerti dalla biblioteca, garantendo allo stesso tempo il rispetto di regole e vincoli prestabiliti.

La struttura della base di dati prevede la gestione di diverse entità chiave:
- **Libri**, con informazioni come titolo, autore, ISBN, genere, casa editrice, anno di pubblicazione e numero di copie, il quale verrò gestito tramite un'entità aggiuntiva;

- **Utenti**, che possono essere studenti e docenti, con i loro dati anagrafici;

- **Prestiti** e **Prenotazioni**, per tracciare quali libri sono in uso e quali sono stati richiesti;

- **Multe**, generate in caso di ritardi nella restituzione.

Il modello tiene conto di vincoli specifici:

- ogni libro può avere più copie disponibili;

- ogni utente può avere fino a un massimo di tre prestiti attivi;

- le prenotazioni decadono dopo 48 ore se il libro non viene ritirato.

Questo approccio garantisce alla biblioteca di funzionare in modo efficiente e agli utenti di accedere facilmente alle risorse.

## Analisi dei requisiti
### Copie dei libri
Al centro del modello c’è il libro, attorno al quale ruotano tutte le altre entità, creando un sistema integrato e coerente per la gestione completa della biblioteca scolastica.

Siccome si tratta di una biblioteca e, dal momento che un libro, per facilitare l'esperienza dell'utente, deve essere presente in più di una copia, bisogna anche tenere conto di questo. L'istinto iniziale potrebbe essere quello di includere il numero di libri nell'entità stessa, ma questo complicherebbe solemente la situazione.

Seguendo questa scelta, dovremmo ogni volta, per verificare la disponibilità di un libro, controllare, in base al numero, la quantità in prestito. Inoltre, nasce un altro problema: questa modalità non ci permette di identificare singolarmente ogni libro fisico.

Alla luce di tutti questi problemi, la soluzione con meno problemi è sembrata quella di introdurre un ulteriore entità, **Copia**, che avesse un identificatore univoco, e allo stesso tempo avrebbe come uno degli attributi, e anche come chiave esterna, l'identificatore del libro.

Così facendo i prestiti verranno gestiti completamente da questa entità.

### Vincolo di prestiti attivi per utente
Un'altro requisito è quello di limitare il numero massimo di prestiti attivi che un'utente può avere. Il limite è impostato a 3 dalla consegna.

Per gestire questa limitazione il tutto viene fatto durante l'inserimento del prestito.
Prima di inserire il record ***prestito*** all'interno della tabella, si crea una tupla fittizia contenente i dati nella stessa forma della tabella **Prestito**, tramite l'operatore ```SELECT```. Dopodiché, tramite l'operatore ```WHERE```, si conta quanti libri sono in presitto per l'utente identificato tramite Codice Fiscale. Se il valore è pari o maggiore di 3, l'operatore ```WHERE``` restituisce ```FALSE``` e non si procede con l'inserimendo della tupla. Se invece l'utente è libero di effettuare il prestito, si procede senza problemi all'inserimento della tupla nella tabella **Prestito**.

#### Esempio in SQL
``` SQL
INSERT INTO prestito (CF, IDCopia, dataInizio, dataFinePrevista)
SELECT 'ESPSRA01H45F205R', 20, '2025-03-26', '2025-04-09'
WHERE (SELECT COUNT(*) 
       FROM prestito
       WHERE CF = 'ESPSRA01H45F205R'
         AND dataFineEffettiva IS NULL) < 3;
```

### Scadenza prenotazione dopo 48h
Per far scadere la prenotazione di un libro in caso di mancato ritiro, viene eseguita la Query di seguito:
``` SQL
UPDATE prenotazione
SET stato = 'Decaduta'
WHERE stato = 'Pronto per il prestito' AND dataRichiesta < NOW() - INTERVAL 2 DAY;
```
Viene fatto un controllo all'interno di tutte le prenotazioni e, nel caso la data di richiesta prenotazione più piccola della sottrazione della data di oggi e 2 giorni (dunque 48 ore), viene cambiato il valore dell'attributo stato da ```Pronto per il prestito``` a ```Decaduta```.

## Diagramma ER
``` mermaid
erDiagram
    LIBRO }o--|{ AUTORE : SCRITTO_DA
    LIBRO ||--|{ COPIA : ha
    CASA_EDITRICE ||--o{ LIBRO : PUBBLICA
    GENERE }|--o{ LIBRO : APPARTIENE
    UTENTE ||--o{ PRENOTAZIONE : PRENOTA
    PRENOTAZIONE }o--|| LIBRO : INCLUDE
    UTENTE ||--o{ PRESTITO : PRENOTA
    PRESTITO }o--|| LIBRO : INCLUDE
    MULTA |o--o| PRESTITO : SUBISCE

    LIBRO {
        VARCHAR ISBN   PK
        VARCHAR titolo
        YEAR annoPubblicazione
    }
    COPIA {
        INT IDCopia PK
        VARCHAR ISBN   FK
        ENUM stato
    }
    AUTORE {
        INT id PK
        VARCHAR nome
        VARCHAR cognome
        DATE dataNascita
    }
    CASA_EDITRICE {
        INT PIVA PK
        VARCHAR nome
    }
    GENERE {
        VARCHAR NOME PK
    }
    UTENTE {
        VARCHAR CF PK
        DATE dataNascita
        VARCHAR nome
        VARCHAR cognome
        ENUM tipo
    }
    PRENOTAZIONE {
        INT IDPrenotazione PK
        VARCHAR CF FK
        VARCHAR ISBN FK
        TIMESTAMP dataRichiesta
        ENUM stato
    }
    PRESTITO {
        INT IDPrestito PK
        VARCHAR CF FK
        INT IDCopia FK
        TIMESTAMP dataInizio
        TIMESTAMP dataFinePrevista
        TIMESTAMP dataFineEffettiva*
    }
    MULTA {
        INT ID_MULTA PK
        INT IDPrestito FK
        DECIMAL costo
        ENUM stato
    }
```
## Schema logico
- Libri(<u>ISBN</u>, titolo, annoPubblicazione, CE_PIVA FK)

- Autori(<u>id</u>, nome, cognome, dataNascita)

- Scritti da(<u>ISBN<u>, </u>idAutore</u>)

- Case editrici(<u>PIVA</u>, nome)

- Generi(<u>NOME</u>)

- Copie(<u>IDCopia</u>, ISBN FK, stato)

- Utenti(<u>CF</u>, nome, cognome, dataNascita, tipo)

- Prenotazioni(<u>IDPrenotazione</u>, CF FK, ISBN FK, dataRichiesta, stato)

- Prestiti(<u>IDPrestito</u>, CF FK, IDCopia FK, dataInizio, dataFinePrevista, dataFineEffettiva*)

- Multe(<u>ID_MULTA</u>, IDPrestito FK, costo, stato)

## Dizionario dei dati
### Entità **libro**
Attributo | Tipo | Descrizione
-|-|-
ISBN                | VARCHAR (PK)  | Codice identificativo univoco del libro
titolo              | VARCHAR       | Titolo del libro
annoPubblicazione   | YEAR          | Anno di pubblicazione
PIVA                | INT (FK)      | Casa editrice che ha pubblicato il libro

### Entità **autore**
Attributo | Tipo | Descrizione
-|-|-
id          | INT (PK)  | Identificatore univoco dell’autore
nome        | VARCHAR   | Nome dell’autore
cognome     | VARCHAR   | Cognome dell’autore
dataNascita | DATE      | Data di nascita

### Entità **casa_editrice**
Attributo | Tipo | Descrizione
-|-|-
PIVA      | INT (PK) | Partita IVA della casa editrice
nome      | VARCHAR  | Nome della casa editrice

### Entità **genere**
Attributo | Tipo | Descrizione
-|-|-
nome      | VARCHAR (PK) | Nome del genere

### Entità **copia**
Attributo | Tipo | Descrizione
-|-|-
IDCopia | INT (PK)      | Identificatore univoco della copia fisica
ISBN    | VARCHAR (FK)  | Libro a cui appartiene la copia
stato   | ENUM          | Stato della copia

### Entità **utente**
Attributo | Tipo | Descrizione
-|-|-
CF          | VARCHAR (PK)  | Codice fiscale dell’utente
nome        | VARCHAR       | Nome dell’utente
cognome     | VARCHAR       | Cognome dell’utente
dataNascita | DATE          | Data di nascita
tipo        | ENUM          | Tipo di utente (studente o docente)

### Entità **prenotazione**
Attributo | Tipo | Descrizione
-|-|-
IDPrenotazione  | INT (PK)      | Identificatore della prenotazione
CF              | VARCHAR (FK)  | Utente che prenota
ISBN            | VARCHAR (FK)  | Libro prenotato
dataRichiesta   | TIMESTAMP     | Data e ora della richiesta
stato           | ENUM          | Stato della prenotazion

### Entità **prestito**
Attributo | Tipo | Descrizione
-|-|-
IDPrestito          | INT (PK)      | Identificatore del prestito
CF                  | VARCHAR (FK)  | Utente che effettua il prestito
IDCopia             | INT (FK)      | Copia prestata
dataInizio          | TIMESTAMP     | Data di inizio prestito
dataFinePrevista    | TIMESTAMP     | Data prevista di restituzione
dataFineEffettiva*  | TIMESTAMP     | Data reale di restituzione

### Entità **multa**
Attributo | Tipo | Descrizione
-|-|-
ID_MULTA    | INT (PK)  |Identificatore della multa
IDPrestito  | INT (FK)  | Prestito associato
costo       | DECIMAL   | Costo della multa
stato       | ENUM      | Stato della multa

## Conclusioni

Il progetto ha portato alla realizzazione di una base di dati completa per la gestione di una biblioteca scolastica, in grado di soddisfare tutti i requisiti richiesti dalla consegna.

Il modello progettato consente di:

- gestire in modo efficace libri e copie fisiche;
- tracciare prestiti, prenotazioni e storico delle operazioni;
- applicare vincoli come il limite di prestiti attivi per utente e la scadenza delle prenotazioni;
- gestire eventuali sanzioni tramite il sistema di multe.

La separazione tra **libro** e **copia** permette un controllo preciso delle disponibilità.