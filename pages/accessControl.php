<?php
    // includiamo funzioni varie
    require_once '..\function\userManager.php';
    require_once '..\function\threadManager.php';

    if(!isset($_POST['submit'])){
        header('refresh:0;url=login.html');
        die();
    }
    
    
    session_start();
    
    $pages = explode(':', $_POST['submit']);
    $originPage = $pages[0];
    $destinationPage = $pages[1];
    $data = $pages[2];
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Esito Login</title>

        <!-- Bootstrap CSS -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />
        <link rel="stylesheet" href="style.css" />
    </head>
    <body
        class="d-flex justify-content-center align-items-center vh-100 bg-light"
    >
        <?php
        
        if($originPage === 'login.html' && $destinationPage === 'forums.php'){
    
            // inizializzamo le variabili che ci serviranno
            $userManager = new UserManager(0);
            $userName = $_POST['userName'];
            $password = $_POST['password'];
    
            if($userManager->checkPassword($userName, $password)){
                // ci salviamolo username e la password
                $_SESSION['userName'] = $userName;
                $_SESSION['password'] = $password;

                echo "<div class='alert alert-success text-center' role='alert'>
                        <h4 class='alert-heading'>Login riuscito</h4>
                        <p>Benvenuto! L'accesso è stato effettuato con successo.</p>
                    </div>";

                header("refresh:2;url=$destinationPage");
                die();
            }
            else{
                echo "<div class='alert alert-danger text-center' role='alert'>
                        <h4 class='alert-heading'>Login fallito</h4>
                        <p>Username o password non validi. Riprova.</p>
                </div>";

                header('refresh:2;url=login.html');
                die();
            }
            
        }
        elseif($originPage === 'singup.html' && $destinationPage === 'login.html'){
    
            // inizializzamo le variabili che ci serviranno
            $userManager = new UserManager(0);
            $userName = $_POST['userName'];
            $password = $_POST['password'];
    
            if($userManager->addUser($userName, $password)){

                echo "<div class='alert alert-success text-center' role='alert'>
                        <h4 class='alert-heading'>Signup riuscito</h4>
                        <p>Benvenuto! La registrazione è stata effettuata con successo.</p>
                    </div>";

                header("refresh:2;url=$destinationPage");
                die();
            }
            else{

                echo "<div class='alert alert-danger text-center' role='alert'>
                        <h4 class='alert-heading'>Signup fallito</h4>
                        <p>Username non valido</p>
                    </div>";

                header('refresh:2;url=signup.html');
            }
        }
        elseif($originPage === 'createThread.php' && $destinationPage === 'forums.php'){
    
            // inizializzamo le varibili che ci servono
            $threadManager = new ThreadManager(0);
            $threadTitle = $_POST['threadTitle'];
            // creiamo il nuovo thread e ci salviamo com'è andata
            if($threadManager->addThread($threadTitle, $_SESSION['userName'])){
                echo "<div class='alert alert-success text-center' role='alert'>
                        <h4 class='alert-heading'>Creazione thread riuscita</h4>
                        <p>Thread creato con successo</p>
                    </div>";
            }
            else{
                echo "<div class='alert alert-danger text-center' role='alert'>
                        <h4 class='alert-heading'>Creazione thread fallita</h4>
                        <p>Nome thread non valido</p>
                    </div>";
            }
            header("refresh:2;url=$destinationPage");
        }
        elseif($originPage === 'forums.php' && $destinationPage === 'chat.php'){
            $_SESSION['title'] = $data;
            header("refresh:0;url=$destinationPage");
            die();
        }
        else{
            echo "<div class='alert alert-danger text-center' role='alert'>
                        <h4 class='alert-heading'>Pagina non trovata 404</h4>
                </div>";
        }
        
        ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
