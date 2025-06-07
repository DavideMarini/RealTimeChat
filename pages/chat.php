<?php
    // ci assicuriamo che arrivi dalla pagina corretta e 
    // che abbia username e password corretti
    require_once '..\function\userManager.php';
    require_once '..\function\threadManager.php';
    session_start();
    
    $userManager = new UserManager(0);
    $threadManager = new ThreadManager(0);

    if(!$userManager->checkSession('userName', 'password')){
        header('refresh:0;url=login.html');
        die();
    }

    $title = $_SESSION['title'];
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="container" style="max-width: 600px;">

        <h1 class="mb-4 text-center">
            <?php
                echo $title;
            ?>
        </h1>

        <div id="chat" class="mb-4 p-3 border rounded bg-white" style="height: 600px; overflow-y: auto;">
            
        </div>

        <div class="d-flex gap-2">
            <input type="text" name="message" class="form-control" id="textInput" placeholder="Scrivi un messaggio...">
            <button class="btn btn-primary-custom" onclick="sendMessage()">Invia</button>
        </div>
    </div>
</body>
</html>
<script src="..\function\script.js"></script>