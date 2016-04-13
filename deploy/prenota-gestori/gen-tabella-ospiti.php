<html>
  <head>
      <meta charset='utf-8'>
      <title>Tabella Ospiti - CAI Sovico</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body style="margin:20px;">
    <? $year = date('Y'); ?>
    <h2>Lista Ospiti al Rifugio Del Grande - Stagione <?echo $year?> </h2>
    <hr>

<?

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $username = "6786_utentesql";
    $password = "databasecai";
    $hostname = "localhost";

    $dbhandle = mysqli_connect($hostname, $username, $password)
        or die( json_encode(array( "error"=> "Impossibile connettersi al server. Contatta il webmaster (Codice C6))") ) );
    $selected = mysqli_select_db($dbhandle, "6786_prenotazioni")
        or die( json_encode(array("error"=> "Errore di connessione al server. Contatta il webmaster (Codice D6)") ) );
        
    $dataInizio = $_POST['inizio'];
    $dataFine = $_POST['fine'];
    $giornoInizio = date('z', strtotime($dataInizio)-1);
    $giornoFine = date('z', strtotime($dataFine)-1);

    $year = date('Y');

    $numBookings = mysqli_fetch_array(mysqli_query($dbhandle, "SELECT COUNT(*) FROM Pernottamenti"))[0];
    $numClienti = mysqli_fetch_array(mysqli_query($dbhandle, "SELECT COUNT(*) FROM Pernottamenti WHERE gestione = 0 AND NOT (
                                                    giorno_inizio+durata-1 <  ".$giornoInizio."  OR giorno_inizio > ".$giornoFine.")"))[0];
    $numGestioni = mysqli_fetch_array(mysqli_query($dbhandle, "SELECT COUNT(*) FROM Pernottamenti WHERE gestione = 1 AND NOT (
                                                    giorno_inizio+durata-1 <  ".$giornoInizio."  OR giorno_inizio > ".$giornoFine.")"))[0];

    echo('
        <p>In questa stagione ci sono state in totale <b>'.$numBookings.' prenotazioni</b>, di cui '.$numGestioni.' gestioni.</p>
    ');

?>
    <a class="btn btn-default" href="index.php#<? echo date('j-n', strtotime('yesterday')); ?>">Torna Indietro</a>
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
                        
    $datalist = mysqli_query($dbhandle, "SELECT * FROM Pernottamenti WHERE NOT (
                                                    giorno_inizio+durata-1 <  ".$giornoInizio."  OR giorno_inizio > ".$giornoFine.") ORDER BY giorno_inizio");

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
    
    <a class="btn btn-default" href="index.php#<? echo date('j-n', strtotime('yesterday')); ?>">Torna Indietro</a>
    
<?
}
else
{
?>

    <script type="text/javascript">
    // jQuery validation plugin settings

        var now     = new Date();
        var year    = now.getFullYear();

        $.validator.addMethod("customData", function(value) {
        var re = new RegExp("^(((0[1-9]|1[0-9]|2[0-9]|30)-(0[6-9]))|((31)-(0[7-8])))-"+year+"$")
            return re.test(value);
        }, 'Inserire una data di arrivo valida (GG-MM-AAAA) compresa tra 01-06-'+year+' e 30-09-'+year);

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
          <label class="col-sm-3 control-label">Data di Inizio</label>
          <div class="col-sm-9" >
              <input id="inizio" type="text" class="form-control" name="inizio" placeholder="Giorno-Mese-Anno" value="01-06-<? echo (date('Y')); ?>"
              data-rule-required="true" data-msg-required="Inserire una data di inizio gestione"
              data-rule-customData="true">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">Data di Fine</label>
          <div class="col-sm-9" >
              <input id="fine" type="text" class="form-control" name="fine" placeholder="Giorno-Mese-Anno" value="30-09-<? echo (date('Y')); ?>"
              data-rule-required="true" data-msg-required="Inserire una data di fine gestione"
              data-rule-customData="true">
          </div>
        </div>
        <div class="form-group col-sm-3">
        </div>
        <div class="form-group col-sm-9 pull-right">
          <input class="btn btn-default" type="submit" value="Invia">
          <a class="btn btn-default" href="index.php#<? echo date('j-n', strtotime('yesterday')); ?>">Torna Indietro</a>
        </div>
      </form>


<?
}
?>

  </body>
</html>
