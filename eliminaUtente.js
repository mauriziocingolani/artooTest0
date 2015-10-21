function eliminaUtente(utenteid) {
    if (confirm('Sei sicuro di voler eliminare questo utente?')) {
        $.ajax('eliminaUtente.php', {
            method: 'post',
            dataType: 'json',
            data: {
                UtenteID: utenteid
            },
        }).success(function (json) {
            if (json.risposta == true) {
                return true;
            } else {
                alert(json.risposta);
                return false;
            }
        }).error(function (a, b, c, d) {
            alert('Errore:');
            return false;
        });
    } else {
        return false;
    }
}