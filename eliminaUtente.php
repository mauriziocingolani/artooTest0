<?php

require_once 'Utenti.php';

$utenteid = $_POST['UtenteID'];
$ok = Utenti::Elimina($utenteid);
echo json_encode(array('risposta' => $ok));
