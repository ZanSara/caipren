<html>
  <head>
      <meta charset='utf-8'>
      <title>Tabella Ospiti - CAI Sovico</title>
      <link href="style.css" rel="stylesheet">
      <link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <script src="static/javascript/jQuery/jquery-1.11.2.min.js"></script>
      <script src="static/javascript/jQuery/jquery.validate.min.js"></script>
  </head>
  <body style="margin:20px;">
    <h2>Lista Prenotazioni per Gestore</h2>
    <hr>

<?

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $username = "6786_utentesql";
    $password = "databasecai";
    $hostname = "localhost";

    $dbhandle = mysqli_connect($hostname, $username, $password)
        or die( json_encode(array( "error"=> "Impossibile connettersi al server. Contatta il webmaster (Codice C3))") ) );
    $selected = mysqli_select_db($dbhandle, "6786_pernottamenti")
        or die( json_encode(array("error"=> "Errore di connessione al server. Contatta il webmaster (Codice D3)") ) );

    $dataInizio = $_POST['inizio'];
    $dataFine = $_POST['fine'];
    $giornoInizio = date('z', strtotime($dataInizio)-1);
    $giornoFine = date('z', strtotime($dataFine)-1);

    $numClienti = mysqli_fetch_array(mysqli_query($dbhandle, "SELECT COUNT(*) FROM Pernottamenti WHERE gestione = 0 AND NOT (
                giorno_inizio+durata <  ".$giornoInizio."  OR giorno_inizio > ".$giornoFine.")"))[0];
    echo('
        <p>Dal '.$dataInizio.' al '.$dataFine.' ci sono in totale <b>'.$numClienti.' prenotazioni</b>.</p>
    ');

    ?>
        <div class="noprint">
          <a class="btn btn-default" href="javascript:window.print()">Stampa la pagina</a>
          <!-- CHANGE ME WHEN DEPLOYING!!! -->
          <a class="btn btn-default"href="main.php?ris=1">Torna Indietro</a>
        </div>

        <h3>Calendario:</h3>
        <table class="table table-bordered calendario" style='border:1px solid black;'>
<?

    $lista = [];
    for($g=$giornoInizio; $g<$giornoFine+1; $g++){

        $absdate = DateTime::createFromFormat('z', $g);
        $day = $absdate->format('d-m');

        // Building TR
        echo("<tr>");
        // Building Date td
        echo("<td class='warning'>".$day."</td>");

        // Filling the rest of the table
        $listadb = mysqli_query($dbhandle, "SELECT *
                                            FROM Pernottamenti
                                            WHERE (gestione=0 AND giorno_inizio=".$g.")");
        while ($row = mysqli_fetch_array($listadb)) {
            $lista[] = $row; // This appends the NEW bookings to $lista, which is usually NOT empty!
        }
        $tot = 0;
        foreach($lista as $pren){
            for($i=0; $i<$pren['posti']; $i++, $tot++){
                echo("<td class='active'>№".$pren['id']."</td>");
            }
            if ($pren['giorno_inizio']+ $pren['durata']-1 <= $g){
                $key = array_search($pren, $lista);  // need to look for it, because the index is not normalized...
                unset($lista[$key]);  // pop from array
            }
        }
        for(;$tot<16; $tot++){
            echo ("<td></td>");
        }
        // Close TR
        echo("</tr>");

    }

?>
        </table>

        <hr>

        <h3>Lista Clienti:</h3>

        <table class="table table-bordered">
          <tr class="active">
            <td>№</td>
            <td>Nome Cliente</td>
            <td>№ Telefono</td>
            <td>Data Arrivo</td>
            <td>Durata Soggiorno</td>
            <td>Posti Prenotati</td>
            <td>Nome Responsabile</td>
            <td>Note</td>
          </tr>
    <?

    $datalist = mysqli_query($dbhandle, "SELECT * FROM Pernottamenti WHERE gestione = 0 AND NOT (
                giorno_inizio+durata <  ".$giornoInizio."  OR giorno_inizio > ".$giornoFine.")");

    // Convert data format  -- BE CAREFUL ABOUT THE HARD-CODED 2016
    while ($data = mysqli_fetch_array($datalist)) {

        $absdate = DateTime::createFromFormat('z', $data['giorno_inizio']);
        $day = $absdate->format('d-m');

        echo("
            <tr>
              <td>".$data['id']."</td>
              <td>".$data['nome']."</td>
              <td>".$data['tel']."</td>
              <td>".$day.'-2016'."</td>
              <td>".$data['durata']."</td>
              <td>".$data['posti']."</td>
              <td>".$data['responsabile']."</td>
              <td>".$data['note']."</td>
            <tr>
        ");
    }
?>
        </table>
<?
    mysqli_close($dbhandle);



}
else
{
?>

    <script type="text/javascript">
    // jQuery validation plugin settings

        $.validator.addMethod("customData", function(value) {
        // test this terrible regex here http://www.regular-expressions.info/javascriptexample.html
        // Matches only days between 1 June and 30 Sept

        // SHOULD CHECK THE CURRENT YEAR TOO!!
        var re = new RegExp("^(((0[1-9]|1[0-9]|2[0-9]|30)-(0[6-9]))|((31)-(0[7-8])))-20[0-9][0-9]$")
            return re.test(value);
        }, 'Inserire una data di arrivo valida (GG-MM-AAAA) compresa tra 01-06-2016 e 30-09-2016');

        $().ready(function() {
            $("#booking-form").validate({
                //debug: true,
                errorElement: "li",
                errorContainer: $("#message-alert"),
                errorPlacement: function(error) {
                  $("#message-alert").show();
                    $("#message-alert").append(error);
                }
            });
        });
    </script>

      <form id='booking-form' class='form-horizontal' method='POST'>
        <div id="message-alert" class="alert alert-danger" role="alert" style='display:none; padding-left:10%;padding-right:10%;'></div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Data di Inizio Gestione</label>
          <div class="col-sm-9" >
              <input id="inizio" type="text" class="form-control" name="inizio" placeholder="Giorno-Mese-Anno"
              data-rule-required="true" data-msg-required="Inserire una data di inizio gestione"
              data-rule-customData="true">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">Data di Fine Gestione</label>
          <div class="col-sm-9" >
              <input id="fine" type="text" class="form-control" name="fine" placeholder="Giorno-Mese-Anno"
              data-rule-required="true" data-msg-required="Inserire una data di fine gestione"
              data-rule-customData="true">
          </div>
        </div>
        <div class="form-group col-sm-3">
        </div>
        <div class="form-group col-sm-9 pull-right">
          <input class="btn btn-default col-sm-2 pull-left" type="submit" value="Invia">
          <a class="btn btn-default"href="main.php?ris=1">Torna Indietro</a>
        </div>
      </form>


<?
}
?>


  </body>
</html>
