<html>
  <head>
      <meta charset='utf-8'>
      <title>Svuota Database - CAI Sovico</title>
      <link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body style="margin:20px;text-align:center;">
    <h2>SVUOTA DATABASE</h2>
    <script>
    if (!confirm('Sei SICURO di voler cancellare completamente il database?\n\nQuesta operazione è IRREVERSIBILE e comporta la perdita di TUTTI i dati della stagione corrente.\n\nClicca OK per proseguire, Cancel per tornare indietro.')) {
      document.location = "main.php?ris=1";
    }
    </script>

<?

$username = "6786_utentesql";
$password = "databasecai";
$hostname = "localhost";

$dbhandle = mysqli_connect($hostname, $username, $password)
    or die( json_encode(array( "error"=> "Unable to connect to MySQL") ) );
$selected = mysqli_select_db($dbhandle, "6786_pernottamenti")
    or die( json_encode(array("error"=> "Could not select database") ) );

$data = mysqli_query($dbhandle, "DELETE FROM `Pernottamenti`");

if($data){
    echo("<p>Il database è stato completamente cancellato.</p>");
}else{
    echo("<p>Si è verificato un errore durante la cancellazione del database. Riprova e avverti il webmaster.</p> ");
}

mysqli_close($dbhandle);
?>

    <!-- CHANGE ME WHEN DEPLOYING!!! -->
    <a class="btn btn-default" href="main.php?ris=1">Torna Indietro</a>

  </body>
</html>
