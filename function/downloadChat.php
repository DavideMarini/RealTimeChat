<?php
    require_once 'threadManager.php';
    if(session_status() === PHP_SESSION_NONE)   session_start();

    $threadManager = new ThreadManager(0);

    $messages = $threadManager->getMessages($_SESSION['title']);
    foreach($messages as &$m){
        $m['date'] = date('h:i:s', $m['timestamp']);
    }

    echo json_encode($messages);
?>