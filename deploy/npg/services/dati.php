<?
// Server-side PHP for the AJAX calls

sleep(1);

$gestione = $_GET['gestione'];
$prenid = $_GET['prenid'];

$username = "6786_utentesql";
$password = "databasecai";
$hostname = "localhost";

$dbhandle = mysqli_connect($hostname, $username, $password)
  or die( json_encode(array( "error"=> "Impossibile connettersi al server. Contatta il webmaster (Codice C5))") ) );
$selected = mysqli_select_db($dbhandle, "6786_prenotazioni")
  or die( json_encode(array("error"=> "Errore di connessione al server. Contatta il webmaster (Codice D5)") ) );

$data = mysqli_fetch_array( mysqli_query($dbhandle, "SELECT * FROM Pernottamenti WHERE (id=".$prenid.")") );

// Convert data format
$absdate = DateTime::createFromFormat('z', $data['giorno_inizio']);
$day = $absdate->format('d-m');
$year = date('Y');

if (!$data){
    echo "Dati della prenotazione non trovati";
}

echo( json_encode(
    array(
        "nome" => $data['nome'],
        "tel" => $data['tel'],
        "provincia" => $data['provincia'],
        "prenid" => $data['id'],
        "arrivo" => $day."-".$year,
        "durata" => $data['durata'],
        "posti" => $data['posti'],
        "resp" => $data['responsabile'],
        "note" => $data['note'],
        "gestione" => $data['gestione']
)) );

mysqli_close($dbhandle);
?>
