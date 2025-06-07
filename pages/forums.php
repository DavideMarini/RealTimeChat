<?php
    // ci assicuriamo che arrivi dalla pagina corretta e 
    // che abbia username e password corretti
    require_once '..\function\userManager.php';
    require_once '..\function\threadManager.php';
    
    $userManager = new UserManager(0);

    if(!$userManager->checkSession('userName', 'password')){
        header('refresh:0;url=login.html');
        die();
    }
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Forum</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light p-4">

    <div class="container">

        <!-- Intestazione e bottone per aggiungere un nuovo thread -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Forum</h1>
            <form action="createThread.php" method="get">
                <button type="submit" name="submit" value="forums" class="btn btn-primary-custom">+ Crea Nuovo Thread</button>
            </form>
        </div>

        
        <!-- Elenco forum -->
        <div class="list-group">
            <?php
                $threadManager = new ThreadManager(0);

                $threadsData = $threadManager->getThreads();

                foreach($threadsData as $t){
                    $title = $t['title'];
                    $date = date("d-m-Y", $t['creationTimestamp']);
                    echo "
                        <form action='accessControl.php' method='post' class='mb-2'>
                            <button type='submit' name='submit' value='forums.php:chat.php:$title'
                                class='list-group-item list-group-item-action rounded-3'>
                                <div class='d-flex justify-content-between'>
                                    <span>$title</span>
                                    <small class='text-muted'>$date</small>
                                </div>
                            </button>
                        </form>
                    ";
                }
            ?>

        </div>
    </div>

</body>

</html>