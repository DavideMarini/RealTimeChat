<?php
    require_once 'threadManager.php';
    if(session_status() === PHP_SESSION_NONE)   session_start();

    $message = json_decode(file_get_contents('php://input'), true);
    
    $threadManager = new ThreadManager(0);
    $title = $_SESSION['title'];
    $author = $_SESSION['userName'];
    echo json_encode($threadManager->sendMessage($title, $author, $message['text']));
?>