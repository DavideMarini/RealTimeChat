<?php
    // ci assicuriamo che arrivi dalla pagina corretta e 
    // che abbia username e password corretti
    require_once '..\function\userManager.php';
    
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Crea Thread</title>

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />
        <link rel="stylesheet" href="style.css" />
    </head>

    <body
        class="bg-light d-flex justify-content-center align-items-center vh-100"
    >
        <div class="card shadow p-4" style="width: 100%; max-width: 500px">
            <h1 class="text-center mb-4">Crea Thread</h1>

            <form action="accessControl.php" method="post">
                <div class="mb-3">
                    <label for="threadTitle" class="form-label"
                        >Titolo Thread</label
                    >
                    <input
                        type="text"
                        id="threadTitle"
                        name="threadTitle"
                        class="form-control"
                        placeholder="Inserisci il titolo del Thread"
                        required
                    />
                </div>

                <button
                    type="submit"
                    name="submit"
                    value="createThread.php:forums.php:"
                    class="btn btn-primary-custom w-100"
                >
                    CREA
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="forums.php" style="color: var(--bs-body-color)"
                    >Torna ai forum</a
                >
            </div>
        </div>
    </body>
</html>
