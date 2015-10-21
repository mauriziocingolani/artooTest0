<?php
require_once 'Utenti.php';
require_once 'Ruoli.php';

$title = 'Utente...?';

$utenteid = isset($_GET['utenteid']) ? (int) $_GET['utenteid'] : null;

$ruoli = Ruoli::GetAll();

$user = null;

if (count($_POST) > 0) :
    $user = new Utenti($_POST);
    $valid = $user->isValid();
//    echo $valid;
    if ($valid) :
        $ok = $user->save();
    endif;
elseif ($utenteid > 0) :
    $user = Utenti::CreaUtente($utenteid);
endif;
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $title; ?></title>
    </head>
    <body>

        <p>
            <a href="/Utenti/index.php">Home</a>
        </p>

        <?php if (is_array($ruoli) && count($ruoli) > 0) : ?>

            <?php if (is_null($user) || $user instanceof Utenti) : ?>

                <form action="" method="post">
                    <input type="hidden" name="UtenteID" value="<?php echo $user != null ? $user->UtenteID : null; ?>" />
                    <label>Nome</label><br />
                    <input type="text" name="Nome" value="<?php echo $user != null ? $user->Nome : null; ?>" /><br />
                    <label>Cognome</label><br />
                    <input type="text" name="Cognome" value="<?php echo $user != null ? $user->Cognome : null; ?>" /><br />
                    <label>Email</label><br />
                    <input type="text" name="Email" value="<?php echo $user != null ? $user->Email : null; ?>" /><br />
                    <label>Abilitato</label><br />
                    <input type="checkbox" name="Abilitato" <?php echo $user == null || $user->Abilitato ? 'checked' : null; ?> /><br />
                    <label>Ruolo</label><br />
                    <select name="RuoloID">
                        <?php foreach ($ruoli as $ruolo): ?>
                            <option value="<?php echo $ruolo->RuoloID; ?>" <?php echo $user != null && $user->RuoloID == $ruolo->RuoloID ? 'selected' : null; ?>>
                                <?php echo $ruolo->Descrizione; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <p>
                        <input type="submit" />
                    </p>

                </form>

            <?php else : ?>

                <div style="background: yellow;font-weight: bold;padding: 5px;">
                    <?php echo $user; ?>
                </div>

            <?php endif; ?>



        <?php else : ?>

            <p>
                <em>ERRORE: impossibile ottenere la lista dei ruoli.</em>
            </p>

        <?php endif; ?>



    </body>
</html>
