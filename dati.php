
<?
// Server-side PHP for the AJAX calls

$gestione = $_GET['gestione'];
$prenid = $_GET['prenid'];

sleep(1);

$username = "6786_utentesql";
$password = "databasecai";
$hostname = "localhost";

$dbhandle = mysqli_connect($hostname, $username, $password)
  or die("Unable to connect to MySQL");
$selected = mysqli_select_db($dbhandle, "6786_pernottamenti")
  or die("Could not select database");

$data = mysqli_fetch_array( mysqli_query($dbhandle, "SELECT * FROM Pernottamenti WHERE (id=".$prenid.")") );


echo( json_encode(
    array(
        "nome" => $data['nome'],
        "tel" => $data['tel'],
        "prenid" => $data['id'],
        "arrivo" => $data['giorno_inizio'],
        "durata" => $data['durata'],
        "posti" => $data['posti'],
        "resp" => $data['responsabile'],
        "note" => $data['note']
)) );


mysqli_close($dbhandle);

?>
