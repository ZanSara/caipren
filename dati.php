<?
// Server-side PHP for the AJAX calls

$gestione = $_GET['gestione'];
$prenid = $_GET['prenid'];

sleep(1);

$username = "6786_utentesql";
$password = "databasecai";
$hostname = "localhost";

$dbhandle = mysqli_connect($hostname, $username, $password)
  or die( json_encode(array( "error"=> "Unable to connect to MySQL") ) );
$selected = mysqli_select_db($dbhandle, "6786_pernottamenti")
  or die( json_encode(array("error"=> "Could not select database") ) );

$data = mysqli_fetch_array( mysqli_query($dbhandle, "SELECT * FROM Pernottamenti WHERE (id=".$prenid.")") );

// Convert data format  -- BE CAREFUL ABOUT THE HARD-CODED 2016
$absdate = DateTime::createFromFormat('z', $data['giorno_inizio']);
$day = $absdate->format('d-m');

echo( json_encode(
    array(
        "nome" => $data['nome'],
        "tel" => $data['tel'],
        "prenid" => $data['id'],
        "arrivo" => $day.'-2016',
        "durata" => $data['durata'],
        "posti" => $data['posti'],
        "resp" => $data['responsabile'],
        "note" => $data['note']
)) );

mysqli_close($dbhandle);
?>
