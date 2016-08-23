<?
// Server-side PHP for the AJAX "find" calls

// Raccoglie i dati dalla chiamata GET e inizia a queryzzarli
$formdata['id'] =             "=".$_GET['prenid'];
$formdata['nome'] =           " LIKE '%".$_GET['nome']."%'";
$formdata['tel'] =            " LIKE '%".$_GET['tel']."%'";
$formdata['provincia'] =      " LIKE '%".$_GET['prov']."%'";
$formdata['durata'] =         "=".$_GET['durata'];
$formdata['posti'] =          "=".$_GET['posti'];
$formdata['responsabile'] =   " LIKE '%".$_GET['resp']."%'";
$formdata['note'] =           " LIKE '%".$_GET['note']."%'";

// Converte la data in formato database
$year = (new DateTime)->format("Y");
$replaced = str_replace("/", "-", $_GET['arrivo']);
if (!(substr($replaced, -4)== $year)){
    $replaced .= "-".$year;
}
$absdate = date('z', strtotime($replaced)-1);
$formdata['giorno_inizio'] =  "=".$absdate;


$username = "6786_utentesql";
$password = "databasecai";
$hostname = "localhost";

$dbhandle = mysqli_connect($hostname, $username, $password)
  or die( json_encode(array( "error"=> "Impossibile connettersi al server. Contatta il webmaster (Codice C6))") ) );
$selected = mysqli_select_db($dbhandle, "6786_prenotazioni")
  or die( json_encode(array("error"=> "Errore di connessione al server. Contatta il webmaster (Codice D6)") ) );


$query = "SELECT * FROM Pernottamenti WHERE stagione=".$year;

// Compila la query
$firstloop = true;
reset($formdata);
while (list($name, $field) = each($formdata)) {
    
    if($field != " LIKE '%%'" && $field != "=" && $field != null){
        
        if($firstloop){
            $query .= " AND ";
            $firstloop = false;
        }
        $query .= $name.$field." OR ";
    }
}
// Se esiste almeno un valore, firstloop e' falso e devo togliere un OR alla fine
if(!$firstloop){
    $query = substr($query, 0, -3);
}


// Esegue la query
$dbdata = mysqli_query($dbhandle, $query);

// Estrae i dati dalla query
$dbextracted = [];
while ($row = mysqli_fetch_array($dbdata)) {
    $dbextracted[] = $row;
}

// Corregge il formato della data
$dbcorrect = [];
foreach ($dbextracted as $value) {
    $dbcorrect[] = $value;
    $absdate = DateTime::createFromFormat('z', $value['giorno_inizio']);
    $dbcorrect[count($dbcorrect)-1]['giorno_inizio'] = $absdate->format('d-m')."-".$year;
}
unset($value);


// Restituisce i valori JSONati
echo( json_encode($dbcorrect) );


mysqli_close($dbhandle);
?>
