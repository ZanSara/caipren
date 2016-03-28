<html>
  <head>
      <meta charset='utf-8'>
      <title>Tabella Ospiti - CAI Sovico</title>
      <link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body style="margin:20px;">
    <? $year = date('Y'); ?>
    <h2>Lista Ospiti al Rifugio Del Grande - Stagione <?echo $year?> </h2>
    <hr>

<?
$username = "6786_utentesql";
$password = "databasecai";
$hostname = "localhost";

$dbhandle = mysqli_connect($hostname, $username, $password)
    or die( json_encode(array( "error"=> "Impossibile connettersi al server. Contatta il webmaster (Codice C6))") ) );
$selected = mysqli_select_db($dbhandle, "6786_pernottamenti")
    or die( json_encode(array("error"=> "Errore di connessione al server. Contatta il webmaster (Codice D6)") ) );

$numBookings = mysqli_fetch_array(mysqli_query($dbhandle, "SELECT COUNT(*) FROM Pernottamenti"))[0];
$numClienti = mysqli_fetch_array(mysqli_query($dbhandle, "SELECT COUNT(*) FROM Pernottamenti WHERE gestione = 0"))[0];
$numGestioni = mysqli_fetch_array(mysqli_query($dbhandle, "SELECT COUNT(*) FROM Pernottamenti WHERE gestione = 1"))[0];

echo('
    <p>In questa stagione ci sono state in totale <b>'.$numBookings.' prenotazioni</b>, di cui '.$numGestioni.' gestioni.</p>
');

?>
    <!-- CHANGE ME WHEN DEPLOYING!!! -->
    <a class="btn btn-default" href="main.php?ris=1">Torna Indietro</a>
    <hr>

    <table class="table table-bordered">
      <tr class="active">
        <td>Nome Cliente</td>
        <td>№ Telefono</td>
        <td>Data Arrivo</td>
        <td>Durata Soggiorno</td>
        <td>Posti Prenotati</td>
        <td>È una gestione?</td>
        <td>Nome Responsabile</td>
        <td>Note</td>
      </tr>

<?

$datalist = mysqli_query($dbhandle, "SELECT * FROM Pernottamenti ORDER BY giorno_inizio");

// Convert data format
while ($data = mysqli_fetch_array($datalist)) {

    $absdate = DateTime::createFromFormat('z', $data['giorno_inizio']);
    $day = $absdate->format('d-m');

    if ($data['gestione']){
        echo("
        <tr class='danger'>
        ");
    }else{
        echo("
        <tr>
        ");
    }

    echo("
          <td>".$data['nome']."</td>
          <td>".$data['tel']."</td>
          <td>".$day."-".$year."</td>
          <td>".$data['durata']."</td>
    ");

    if ($data['gestione']){
        echo("
        <td>X</td>
        <td>Sì</td>
        ");
    }else{
        echo("
        <td>".$data['posti']."</td>
        <td>No</td>
        ");
    }

    echo("
          <td>".$data['responsabile']."</td>
          <td>".$data['note']."</td>
        <tr>
    ");
}


mysqli_close($dbhandle);
?>

    </table>

    <!-- CHANGE ME WHEN DEPLOYING!!! -->
    <a class="btn btn-default" href="main.php?ris=1">Torna Indietro</a>


  </body>
</html>
