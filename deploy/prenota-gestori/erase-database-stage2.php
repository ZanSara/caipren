<html>
  <head>
      <meta charset='utf-8'>
      <title>Svuota Database - CAI Sovico</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

$data = mysqli_query($dbhandle, " DELETE FROM Pernottamenti");
$data = mysqli_query($dbhandle, " ALTER TABLE Pernottamenti DROP COLUMN id");
$data = mysqli_query($dbhandle, " ALTER TABLE Pernottamenti AUTO_INCREMENT = 1");
$data = mysqli_query($dbhandle, " ALTER TABLE Pernottamenti ADD id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;");

if($data == True){
    echo("<p>Il database è stato completamente cancellato.</p>");
}else{
    echo("<p>Si è verificato un errore durante la cancellazione del database. Riprova e avverti il webmaster.</p> ");
}

mysqli_close($dbhandle);
?>

    <a class="btn btn-default" href="index.php#<? echo date('j-n', strtotime('yesterday')); ?>"> Torna Indietro</a>

  </body>
</html>
