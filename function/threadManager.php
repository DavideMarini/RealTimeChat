<?php
require_once '..\vendor\autoload.php';

class ThreadManager{
    private $Redis;
     public function __construct($database){
        $this->Redis = new Predis\Client([
            'scheme'   => 'tcp',
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'password' => '',
            'database' => $database,
        ]);
    }

    public function addThread($title, $author){
        // controllo se esiste gia un thread con quel titolo
        if($this->Redis->hexists('thread:titles', $title))
            return false;
        
        // genero l'id del thread
        $threadId = $this->Redis->incr('thread:id');

        // aggiungo il thread
        if(!$this->Redis->hsetnx('thread:titles', $title, $threadId))
            return false;
        $this->Redis->hmset(
            "thread:$threadId", [
                "title" => $title,
                "author" => $author,
                "creationTimestamp" => time()
            ]);
        $this->Redis->sadd("thread:ids", $threadId);

        return true;
    }

    public function getThreads(){
        $threadIds = $this->Redis->smembers("thread:ids");

        $threadsData = [];
        foreach($threadIds as $id){
            $threadsData [] = $this->Redis->hgetall("thread:$id");
        }

        return $threadsData;
    }

    public function sendMessage($threadTitle, $author, $text){
        // ottengo l'id associato al titolo del thread
        // e al contempo controllo se esiste
        $threadId = $this->Redis->hget("thread:titles", $threadTitle);
        if($threadId === null)
            return false;

        // estraggo l'id del messaggio
        $messageId = $this->Redis->incr("message:id");

        // aggiungo il messaggio
        $this->Redis->hmset(
            "message:$messageId", [
                "author" => $author,
                "text" => $text,
                "timestamp" => time()
            ]);

        // aggiungo il messaggio alla lista degli id messaggio del thread
        $this->Redis->rpush("thread:$threadId:messages", $messageId);
    }

    public function getMessages($title){
        // ottengo l'id associato al titolo del thread
        // e al contempo controllo se esiste
        $threadId = $this->Redis->hget("thread:titles", $title);
        if($threadId === null)
            return null;

        // recupero tutti gli id dei messaggi che ho mandato sulla chat
        $messageIds = $this->Redis->lrange("thread:$threadId:messages", 0, -1);

        // creo un array che contenga tutti i messaggi
        $messages = [];
        foreach($messageIds as $id){
            $messages [] = $this->Redis->hgetall("message:$id");
        }
        
        return $messages;
    }
    
    
}
?>