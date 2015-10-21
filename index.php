<?php
require_once 'Utenti.php';

$title = 'Utenti';
$utenti = Utenti::GetAll();
?>


<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $title; ?></title>
        <script src="jquery-1.11.3.js"></script>
        <script src="eliminaUtente.js"></script>
    </head>
    <body>

        <!-- ERRORE -->
        <?php if (is_string($utenti)) : ?>

            <div style="background: yellow;font-weight: bold;padding: 5px;">
                <?php echo $utenti; ?>
            </div>

            <!-- ARRAY -->
        <?php elseif (is_array($utenti)) : ?>

            <p>
                <a href="/Utenti/utente.php">Crea nuovo utente</a> 
            </p>

            <!-- dati presenti -->
            <?php if (count($utenti) > 0) : ?>

                <table border="1">
                    <thead>
                        <tr>
                            <th>Nome e cognome</th>
                            <th>Email</th>
                            <th>Ruolo</th>
                            <th>Ultimo accesso</th>
                            <th />
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($utenti as $utente) : ?>
                            <tr>
                                <td><?php echo $utente->getNomeCognome(); ?></td>
                                <td><?php echo $utente->Email; ?></td>
                                <td><?php echo (isset($utente->Ruolo) ? $utente->Ruolo : null); ?></td>
                                <td><?php echo $utente->UltimoAccesso; ?></td>
                                <td>
                                    <a href="http://localhost/Utenti/utente.php?utenteid=<?php echo $utente->UtenteID; ?>">Modifica</a>
                                    &centerdot;
                                    <a href="" onclick="return eliminaUtente(<?php echo $utente->UtenteID; ?>);">Elimina</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>

                <!-- dati mancanti -->
            <?php else : ?>

                <p><em>Nessun utente presente nel database :-(</em></p>

            <?php endif; ?>

            <!-- CASO NON PREVISTO -->
        <?php else : ?>

            <p>BACO!!! Caso non previsto!</p>

        <?php endif; ?>
    </body>
</html>
