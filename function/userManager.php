<?php
require_once '..\vendor\autoload.php';

class UserManager {
    private $Redis;

    public function  __construct($database){
        $this->Redis = new Predis\Client([
            'scheme'   => 'tcp',
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'password' => '',
            'database' => $database,
        ]);
    }

    // ritorna falso se la password è sbagliata o se l'utente non esiste
    public function checkPassword($userName, $password){
   
        //estraggo l'id dello user
        $userId = $this->Redis->hget('user:usernames', $userName);

        // se l'id non esiste ritorno falso
        if($userId === null) return false;

        // estraggo la password
        $passwordHash = $this->Redis->hget("user:$userId", "passwordHash");

        // controllo se la password è corretta
        if(password_verify($password, $passwordHash))
            return true;
        else return false;
    }

    // ritorna falso se l'inserimento fallisce (l'utente è gia presente)
    public function addUser($userName, $password){

        // controlliamo se lo user esiste gia
        if($this->Redis->hexists("user:usernames", $userName))
            return false;

        // ricavo lo user id dal contatore 'user:id', una volta aggiunto ogni utente 
        // andrò a incrementarlo facendo in modo di avrere un id diverso per ogni user
        $userId = $this->Redis->incr("user:id");
        
        // provo a settare lo userName nell'hash set 'user:usernames' allo user name fornito,
        // se l'utente esiste già il metodo HSETNX ritornerà 0, altimenti impopsterà i valori corretti
        if(!$this->Redis->hsetnx("user:usernames", $userName, $userId)) 
            return false;
        
        // andiamo a creare l'id corretto user:<id> all'inetrno del database una volta che 
        // ci siamo assicurati che non esiste un'altro
        $this->Redis->hmset(
            "user:$userId", [
                "name" => $userName, 
                "passwordHash" => password_hash($password, PASSWORD_DEFAULT)
            ]);
        
        // infine aggiungiamo lo userName -> id al suo set user:ids
        $this->Redis->sadd("user:ids", $userId);

        return true;
    }

    public function checkSession($sessionUserName, $sessionPassword){
        if(session_status() === PHP_SESSION_NONE)   session_start();

        if(!isset($_SESSION[$sessionUserName]) || !isset($_SESSION[$sessionPassword]))
            return false;

        $userName = $_SESSION[$sessionUserName];
        $password = $_SESSION[$sessionPassword];

        session_write_close();

        if(!$this->checkPassword($userName, $password))
            return false;

        return true;
    }
}
?>