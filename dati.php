
<?
// Server-side PHP for the AJAX calls
if ($gestione == 0){
    echo('
        {"nome" : "Nome",
            "tel" : "Telefono",
            "prenid" : "ID",
            "arrivo": "Arrivo",
            "durata": "X notti",
            "posti": "X"
            "resp" : "Responsabile",
            "note": "Note"
         }
    ');
}else{
    echo('
        {
            "nome" : "Nome",
            "tel" : "Telefono",
            "prenid" : "ID",
            "arrivo": "Arrivo",
            "durata": "X notti",
            "posti": "X"
            "resp" : "",
            "note": "Note"
         }
    ');

    }
?>
