# Marini Grassetti Davide Progetto Redis

Una breve introduzione al funzionamento e alla struttura del mio progetto utilizzando Redis come database

## Struttura del database

Tutti i dati vengono salvati nel medesimo database logico

### Gestione degli utenti

Premettiamo che ogni user name è unico e ad ogni username è associato un user id anchesso univoco.

Per salvare gli utenti ci appoggimao su tre sturtture dati al fine di semplificare il lavoro,
la prima sono gli hash set, ogni utente avrà il propio, la chiave corrispondera all'id e i campi all'interno saranno le informazioni:<br>
`"user:100" -> "userName":"Marco123", "passwordHash":$sdjkhfg76h85fdg765`<br>
`"user:200" -> "userName":"Claudia05", "passwordHash":$sd908f90sdsdf8sd`<br>
`"user:300" -> "userName":"Giorgione00", "passwordHash":$3jg5432jgh3jgasd2`<br>

Per poter accedere rapidamente ad un utente partendo dallo userName salviamo la coppia chiave valore sempre con un secondo hash set<br>
`"user:usernames -> "Marco123":100, "Claudia05":200, "Giorgione00":300`<br>

Per iterare rapidamente tra tutti gli elementi utenti abbaimo un set che contiene tutti gli id degli utenti:<br>
`"user:ids" -> 100, 200, 300`<br>

Per assegnare gli id utilizzo una variabile che vado a incrementare man mano e per ogni numero ho un nuovo id:<br>
`"user:id" -> 234`<br>

### Gestione dei thread

La gestione dei thread è molto simile a qulla degli user<br>

Anche qui ogni thread sara unico e associato a un id univoco, pertanto salveremo i thread in questo modo:<br>
`"thread:100 -> "title":"cotto e mangiato", "author":"Giulio", "timestamp":"1747907819"`<br>
`"thread:200 -> "title":"in cucina con pina", "author":"Pina", "timestamp":"1747907969"`<br>
`"thread:300 -> "title":"100 ricette per un uovo", "author":"Marco", "timestamp":"1747907919"`<br>

Avremo un hash set incui associamo title -> id chiamato thread:titles<br>
`thread:titles -> "cotto e mangiato":100, "buongiornissimo":200, ecc...`<br>

Per iterare rapidamente tra tutti i thread abbiamo un set con tutti gli id:<br>
`thread:ids -> 100, 200, 300`<br>

Per gestire i messaggi di ogni chat abbiamo una lista che contiene gli id dei messaggi inviati nel corso del tempo salavti in ordine di invio:<br>
`thread:100:messages -> [12, 45, 67, 91, ecc...]`<br>
`thread:200:messages -> [1, 2, 78, 90, ecc...]`<br>
`thread:300:messages -> [11, 50, 22, 64, ecc...]`<br>

Ed infine abbiamo un contatore che incrementiamo in modo da assegnare id univoci per ogni thread:<br>
`thread:id -> 52`<br>

### Gestione dei messaggi

I messaggi sono salvati in un hash set in cui la chiave corrisponde all'id<br>
`message:100 -> "author":"Gino", "text":"Buongiorno a tutti!!", "timestamp":"1747907819"`<br>
`message:200 -> "author":"Pietro", "text":"Buona pasqua", "timestamp":"1747907819"`<br>
`message:300 -> "author":"Pina", "text":"Oggi si cucina", "timestamp":"1747907819"`<br>

Per assegnare un id univo ad ogni messaggio ho un contatore per i messaggio:<br>
`message:id -> 3246`<br>

| **Chiave / Struttura** | **Tipo di dato Redis** | **Descrizione**                                                                    |
| ---------------------- | ---------------------- | ---------------------------------------------------------------------------------- |
| `user:<id>`            | Hash                   | Contiene le informazioni di un singolo utente (es. `userName`, `passwordHash`)     |
| `user:usernames`       | Hash                   | Associa ogni `userName` al relativo `userId` per ricerca veloce                    |
| `user:ids`             | Set                    | Contiene tutti gli ID utente per poter iterare su tutti gli utenti                 |
| `user:id`              | int64                  | Contatore incrementale per generare nuovi ID utente univoci                        |
| `thread:<id>`          | Hash                   | Contiene le informazioni di un singolo thread (es. `title`, `author`, `timestamp`) |
| `thread:titles`        | Hash                   | Associa il titolo di un thread al suo ID per ricerca veloce                        |
| `thread:ids`           | Set                    | Contiene tutti gli ID dei thread per iterazione                                    |
| `thread:<id>:messages` | Lista                  | Lista ordinata di ID messaggi associati al thread, nell’ordine di invio            |
| `thread:id`            | int64                  | Contatore incrementale per generare nuovi ID thread univoci                        |
| `message:<id>`         | Hash                   | Contiene le informazioni di un singolo messaggio (`author`, `text`, `timestamp`)   |
| `message:id`           | int64                  | Contatore incrementale per generare nuovi ID messaggio univoci                     |
