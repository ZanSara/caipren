<html>
  <head>
      <meta charset='utf-8'>
      <title>Svuota Database - CAI Sovico</title>
      <link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body style="margin:20px;text-align:center;">
    <h2>SVUOTA DATABASE</h2>

<?

$username = "6786_utentesql";
$password = "databasecai";
$hostname = "localhost";

$dbhandle = mysqli_connect($hostname, $username, $password)
    or die( json_encode(array( "error"=> "Impossibile connettersi al server. Contatta il webmaster (Codice C4))") ) );
$selected = mysqli_select_db($dbhandle, "6786_prenotazioni")
    or die( json_encode(array("error"=> "Errore di connessione al server. Contatta il webmaster (Codice D4)") ) );

$data = mysqli_query($dbhandle, "DELETE FROM `Pernottamenti`");

if($data){
    echo("<p>Il database è stato completamente cancellato.</p>");
}else{
    echo("<p>Si è verificato un errore durante la cancellazione del database. Riprova e avverti il webmaster.</p> ");
}

mysqli_close($dbhandle);
?>

    <a class="btn btn-default" href="index.php">Torna Indietro</a>

  </body>
</html>
