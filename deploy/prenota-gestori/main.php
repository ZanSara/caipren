<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <title>Prenotazioni - CAI Sovico</title>

    <link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="static/css/style.css" rel="stylesheet">
    <script src="static/javascript/jQuery/jquery-1.11.2.min.js"></script>
    <script src="static/javascript/jQuery/jquery.validate.min.js"></script>
    <script src="static/bootstrap/js/bootstrap.min.js"></script>
    <script src="static/javascript/funzioni.js"></script>

    <script type="text/javascript">
    // jQuery validation plugin settings

        var now     = new Date();//(2017, 01, 01);
        var year    = now.getFullYear();

        $.validator.addMethod("customData", function(value) {
        // test this terrible regex here http://www.regular-expressions.info/javascriptexample.html
        // Matches only days between 1 June and 30 Sept

        var re = new RegExp("^(((0[1-9]|1[0-9]|2[0-9]|30)-(0[6-9]))|((31)-(0[7-8])))-"+year+"$")
            return re.test(value);
        }, 'Inserire una data di arrivo valida (GG-MM-AAAA) compresa tra 01-06-'+year+' e 30-09-'+year);

        $.validator.addMethod("customDurata", function(value) {
            return (value < 122 && value > 0);
        }, 'Inserire una durata del soggiorno valida');

        $.validator.addMethod("customPosti", function(value) {
            return (value < 17 && value > 0);
        }, 'Inserire un numero di posti letto valido');


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

  </head>
  <body>

    <?

    // Some global variables moved here

    $year = date('Y'); //date('Y', strtotime('01-01-2017'));
    // To test on different year remember to modify this string also in
    // the other php files and inside JS validators!

    $error = false;
    $error_message = "";

    // Obviously, strtotime likes to mess up everything in case of leap years
    if($year % 4 == 0){
        $firstday = date('z', strtotime('01-06-'.$year)-1); //   ONLY dd-mm-yyyy OR mm/dd/yyyy are recognized correctly
        $lastday = date('z', strtotime('1-10-'.$year)-1);
        $today = date('z', strtotime('05-06-'.$year)-1);  // -----> CHANGE ME WHEN DEPLOYING!!!
    }else{
        $firstday = date('z', strtotime('01-06-'.$year));
        $lastday = date('z', strtotime('1-10-'.$year));
        $today = date('z', strtotime('05-06-'.$year));
      }
    //$today = date('z') - $firstday;





    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $username = "6786_utentesql";
        $password = "databasecai";
        $hostname = "localhost";

        $dbhandle = mysqli_connect($hostname, $username, $password)
            or die("Impossibile connettersi al server. Contatta il webmaster (Codice C1))");
        $selected = mysqli_select_db($dbhandle, "6786_prenotazioni")
            or die("Errore di connessione al server. Contatta il webmaster (Codice D1)");


        if (isset($_POST['delbooking'])){
            try{
                deleteReservation($dbhandle, (int)$_POST['prenid']);
            }catch (Exception $e){
                $error = true;
                $error_message = $e->getMessage();
            }
        }else{
            if (isset($_POST['newbooking'])){
                try{
                    makeReservation($dbhandle, $year);
                }catch (Exception $e){
                    $error = true;
                    $error_message = $e->getMessage();
                }
            }else{
                try{
                    updateReservation($dbhandle, (int)$_POST['prenid'], $year);
                }catch (Exception $e){
                    $error = true;
                    $error_message = $e->getMessage();
                }
            }
        }

        mysqli_close($dbhandle);
    }


    // *************** MAKE RESERVATION *******************************

    function makeReservation($dbhandle, $year){

        $validData = validate($dbhandle);
        checkAssertions($dbhandle, $validData, 0, $year);

        // Retrieve colors
        $lastColor = mysqli_fetch_array(mysqli_query($dbhandle, "SELECT ID FROM Colori WHERE last IN (SELECT MAX(last) FROM Colori)") )[0];
        $colorNum = mysqli_fetch_array(mysqli_query($dbhandle, "SELECT COUNT(*) FROM Colori") )[0];
        if ($lastColor >= $colorNum-1 ){
            $newColor = mysqli_fetch_array(mysqli_query($dbhandle, "SELECT ID FROM Colori WHERE ID = 0"))[0];
        }else{
            $newColor = mysqli_fetch_array(mysqli_query($dbhandle, "SELECT ID FROM Colori WHERE ID = ".($lastColor+1)) )[0];
        }

        // Actually write reservation in DB
        $values = "(NULL, '".$validData['nome']."', '".
                    $validData['telefono']."', '".
                    $validData['arrivo']."', '".
                    $year."', '".
                    $validData['durata']."', '".
                    $validData['posti']."', '".
                    $validData['note']."', '".
                    $validData['gestione']."', '".
                    $validData['resp']."', '".
                    $newColor."')";
        $result = mysqli_query($dbhandle, "INSERT INTO `Pernottamenti`
                                (`id`, `nome`, `tel`, `giorno_inizio`, `stagione`, `durata`, `posti`, `note`, `gestione`, `responsabile`, `colore`)
                                VALUES".$values);
        if ($result == 1){
            // Update Last Color
            $maxLast = mysqli_fetch_array(mysqli_query($dbhandle, "SELECT MAX(last) FROM Colori") )[0];

            $result2 = mysqli_query( $dbhandle, "UPDATE Colori SET last = ".($maxLast+1)." WHERE ID = ".$newColor);
            if ($result2 == 0) throw new Exception("Errore interno al server:<br>la prenotazione È stata comunque effettuata.<br>Avverti il webmaster (Codice C).");

        }else{
            mysqli_close($dbhandle);
            throw new Exception("Errore interno al server durante la registrazione della prenotazione:<br>la prenotazione NON è stata effettuata.<br>Avverti il webmaster (Codice R).".$result);
            }

    }


// *************** UPDATE RESERVATION *******************************

    function updateReservation($dbhandle, $prenid, $year){

        $validData = validate($dbhandle);
        checkAssertions($dbhandle, $validData, $prenid, $year);
        // Update reservation in DB
        $result = mysqli_query($dbhandle, "UPDATE Pernottamenti SET
                                nome = '".$validData['nome'].
                                "', tel = '".$validData['telefono'].
                                "', giorno_inizio = ".$validData['arrivo'].
                                ", durata = ".$validData['durata'].
                                ", posti = ".$validData['posti'].
                                ", note = '".$validData['note'].
                                "', gestione = ".$validData['gestione'].
                                ", responsabile = '".$validData['resp'].
                                "' WHERE ID = ".$prenid);
        if ($result == False){
            mysqli_close($dbhandle);
            throw new Exception("Errore interno al server:<br>L'aggiornamento NON è stato effettuato.<br>Avverti il webmaster (Codice U).");
        }

    }


// *************** DELETE RESERVATION *******************************

    function deleteReservation($dbhandle, $prenid){

        $result = mysqli_query($dbhandle, "DELETE FROM Pernottamenti WHERE ID = ".$prenid);

        if ($result == False){
            mysqli_close($dbhandle);
            throw new Exception("Errore interno al server.<br>La prenotazione NON è stato cancellata.<br>Avverti il webmaster (Codice D).");//.$result);
        }

    }


// *************** FALLBACK DATA VALIDATION ****************************
// Throws errors only if js validation falied its work - should never be seen

    function validate($dbhandle) {

        if ($_POST['nome']== '')  throw new Exception("Nome del cliente non valido!");
        if ($_POST['telefono']== '')  throw new Exception("Numero di telefono non valido!");
        if ((int)($_POST['durata'])<= 0 or (int)($_POST['durata'])>= 122) throw new Exception("Durata della prenotazione non valida!");
        if ($_POST['arrivo']== '')  throw new Exception("Inserire una data di arrivo valida.");
         $replaced = str_replace("/", "-", $_POST['arrivo']);
         $absdate = mysqli_real_escape_string($dbhandle, date('z', strtotime($replaced)-1));

        if ((int)($_POST['posti'])<= 0 or (int)($_POST['posti'])> 16) throw new Exception("Numero di posti prenotati non valido!");

        $gestione = 0;
        if (isset($_POST['gestione'])) {
           $gestione = 1;
        }

        if ($_POST['responsabile']== '' and $gestione == 0) throw new Exception("Nome del responsabile non valido!");

        return array(
            'nome' => mysqli_real_escape_string($dbhandle, $_POST['nome']),
            'telefono' => mysqli_real_escape_string($dbhandle, $_POST['telefono']),
            'arrivo' => (int)mysqli_real_escape_string($dbhandle, $absdate),
            'durata' => (int)mysqli_real_escape_string($dbhandle, $_POST['durata']),
            'posti' => mysqli_real_escape_string($dbhandle, $_POST['posti']),
            'note' => mysqli_real_escape_string($dbhandle, $_POST['note']),
            'gestione' => $gestione,
            'resp' => mysqli_real_escape_string($dbhandle, $_POST['responsabile']),
        );
    }

// *************** DB ASSERTIONS ****************************************

    function checkAssertions($dbhandle, $data, $prenid, $year){
        // Notice: here I assume that no booking will have ID = 0, because 0 means basically "do not check"

        $dayslist = array();
        for($giorno=$data['arrivo']; $giorno < ($data['arrivo']+$data['durata']); $giorno++ ){
            $result = mysqli_fetch_array(mysqli_query($dbhandle, "
                    SELECT SUM(posti) FROM Pernottamenti
                    WHERE stagione = ".$year." AND ( giorno_inizio <= ".$giorno." AND (giorno_inizio + durata) >= ".$giorno.")
                    AND gestione = ".$data['gestione']." AND id <> ".$prenid) );
            if(!$data['gestione'] && $result[0] + $data['posti'] > 16){
                $dayslist[] = DateTime::createFromFormat('z', $giorno);
            }
            if($data['gestione'] && $result[0]){
                $dayslist[] = DateTime::createFromFormat('z', $giorno);
            }
        }

        if(count($dayslist) > 0){
            $errorstring = "";
            foreach ($dayslist as $day){
                $errorstring = $errorstring.'<br><br>'.(string)$day->format('d-m-Y');
            }
            mysqli_close($dbhandle);
            if (!$data['gestione']){
                throw new Exception("Impossibile prenotare!<br>Il Rifugio è già pieno nelle date:".$errorstring."<br><br>NON è stata registrata nessuna modifica nel database: ripetere l'operazione.");
            }else{
                throw new Exception("Attenzione!<br>C'è già un gestore in queste date: ".$errorstring."<br><br>NON è stata registrata nessuna modifica nel database: ripetere l'operazione.");
            }
        }
    }

    ?>

    <div class="title shadow1">

        <h3>Prenotazioni <? echo $year ?></h3>
        <h2>Prenotazioni Stagione <? echo $year ?></h2>
        <h4>Rifugio M. Del Grande - R. Camerini</h4>
        <p>ATTENZIONE: I tipi di sistemazione (letto, brandina, bivacco etc...)
        sono provvisori e la disposizione effettiva dei posti letto verrà
        concordata con i gestori una volta giunti al Rifugio.</p>

        <!-- I'LL PUT BUTTONS HERE WHEN I IMPLEMENT MOBILE VERSION
        <button class="btn btn-success" onclick='javascript:openNewBModal(0, 0, 0)'>Nuova Prenotazione</button>
        <button class="btn btn-info" data-toggle="modal" data-target="#Adv_Modal">Avanzate</button>
        <a href="main.php?ris=0" class="btn btn-danger">Logout</a>
        -->

    </div>


    <!-- ERROR ALERT -->
    <div class="modal fade" id="Error_Modal" data-error="<? if($error) {?>1<?}else{?>0<?}?>" tabindex="-1" role="dialog" aria-labelledby="Error_ModalLabel">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header center">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title" style='color:red;'><b>ERRORE</b></h3>
          </div>

          <div class="modal-body center" style='background:#FFE0E0'>
            <p id='errormsg'><? echo($error_message); ?></p>
          </div>

          <div class="modal-footer center">
              <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
          </div>

        </div>
      </div>
    </div>

    <!-- ADVANCED MODAL -->
    <div class="modal fade" id="Adv_Modal" tabindex="-1" role="dialog" aria-labelledby="Adv_ModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header center">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title">Funzioni Avanzate</h2>
            <h4 class="modal-title" style="color:red;">Attenzione! Usare con cautela!</h4>
          </div>

          <div class="modal-body">

            <div style="padding-left:5%;padding-right:5%;" >
              <div class="row">
                <a class="col-sm-3 btn btn-info" href="gen-tabella-prenotazioni.php">Prenotazioni</a>
                <p class="col-sm-8 pull-right">Genera una tabella stampabile delle prenotazioni di ogni gestore.</p>
              </div>
              <hr>
              <div class="row">
                <a class="col-sm-3 btn btn-success" href="gen-tabella-ospiti.php">Tabella Ospiti</a>
                <p class="col-sm-8 pull-right">Genera una tabella stampabile di tutti gli ospiti che hanno pernottato al rifugio durate questa stagione.</p>
              </div>
              <hr>
              <div class="row">
                <a class="col-sm-3 btn btn-danger" href="erase-database-stage1.php">Svuota Database</a>
                <p class="col-sm-8 pull-right">Svuota completamente il database delle prenotazioni.<br>ATTENZIONE! Questa operazione è IRREVERSIBILE!</p>
              </div>

            </div>

          </div>

          <div class="modal-footer center">
              <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
          </div>

        </div>
      </div>
    </div>

    <!-- NEW BOOKING MODAL -->
    <div class="modal fade" id="newB_Modal" tabindex="-1" role="dialog" aria-labelledby="newB_ModalLabel" data-fillme=0, data-prenid=0, data-gestione=0>
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header center">
            <h2 class="loadingTitle modal-title" style='display:none;'>Caricamento...</h2>
            <h2 class="modal-dataTitle modal-title">Prenotazione</h2>
            <a id="enable-btn" class="btn btn-warning modify-btn" href="javascript:enableEditing(0);">Modifica</a>
          </div>

          <form id='booking-form' class='form-horizontal' method='POST'>
            <div class="modal-body">

              <img class="loading" src="static/images/spinningwheel.gif" style='width:40%; margin:30%; display:none;' />
              <div id="error-alert" class="alert alert-danger" role="alert" style='display:none; text-align:center;'></div>
              <div id="message-alert" class="alert alert-danger" role="alert" style='display:none; padding-left:10%;padding-right:10%;'></div>

              <div class="modal-databox" >
                <div class="form-group" >
                  <label class="col-sm-3 control-label">Nome Cliente</label>
                  <div class="col-sm-9" >
                      <input id="modnome" type="text" class="mod-nome form-control" name="nome" placeholder="Nome Cliente" readonly="readonly"
                      data-rule-required="true" data-msg-required="Inserire il nome del cliente">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">№ Telefono</label>
                  <div class="col-sm-9" >
                      <input id="modtel" type="text" class="mod-tel form-control" name="telefono" placeholder="№ Telefono" readonly="readonly"
                      data-rule-required="true" data-msg-required="Inserire il numero di telefono"
                      data-rule-digits="true" data-msg-digits="Inserire un numero di telefono valido">
                  </div>
                </div>
                <hr/>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Data di Arrivo</label>
                  <div class="col-sm-9" >
                      <input id="modarrivo" type="text" class="mod-arrivo form-control" name="arrivo" placeholder="Giorno-Mese-Anno" readonly="readonly"
                      data-rule-required="true" data-msg-required="Inserire una data di arrivo"
                      data-rule-customData="true">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Durata del Soggiorno</label>
                  <div class="col-sm-9" >
                      <input id="moddurata" type="text" class="mod-durata form-control" name="durata" placeholder="Durata del Soggiorno" readonly="readonly"
                      data-rule-required="true" data-msg-required="Inserire la durata del soggiorno"
                      data-rule-digits="true" data-msg-digits="Inserire una durata del soggiorno valida"
                      data-rule-customDurata="true">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Posti Prenotati</label>
                  <div class="col-sm-9" >
                      <input id="modposti" type="text" class="mod-posti form-control" name="posti" placeholder="Posti Prenotati" readonly="readonly"
                      data-rule="true" data-msg-required="Inserire il numero di posti letto prenotati"
                      data-rule-digits="true" data-msg-digits="Inserire un numero di posti letto valido"
                      data-rule-customPosti="true">
                  </div>
                </div>
                <hr/>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Responsabile Prenotazione</label>
                  <div class="col-sm-9" >
                      <input id="modresp" type="text" class="mod-resp form-control" name="responsabile" placeholder="Responsabile Prenotazione" readonly="readonly"
                      data-rule-required="true" data-msg-required='Inserisci il tuo nome nel campo "Responsabile della prenotazione"'>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Note</label>
                  <div class="col-sm-9" >
                      <input id="modnote" type="textarea" class="mod-note form-control" name="note" placeholder="Note..." readonly="readonly">
                  </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9 checkbox">
                      <label>
                        <input id="modgest" name='gestione' class='mod-gest' type="checkbox" onclick="javascript:togglePosti();" readonly="readonly"> Sono gestori
                      </label>
                    </div>
                </div>
                <div class="hidden checkbox">
                  <input id="modnew" name='newbooking' class='mod-new' type="checkbox" checked='checked' readonly="readonly">
                </div>
                <div class="hidden checkbox">
                  <input id="moddel" name='delbooking' class='mod-del' type="checkbox" readonly="readonly">
                </div>
                <div class="hidden">
                  <input id="modprenid" name='prenid' class='mod-prenid' type="text" readonly="readonly">
                </div>
              </div> <!-- modal-databox -->

              <div class="modal-footer center">
                <input id="new-btn" class="btn btn-primary" disabled="disabled" type="submit" style='display:none;' value="Salva">
                <a id="del-btn" class="btn btn-danger" href="javascript:prepareDelete()" style='display:none;'>Elimina</a>
                <button class="btn btn-default" data-dismiss="modal">Chiudi</button>
              </div>
              <div class="modal-errfooter center" style='display:none;'>
                <button class="btn btn-danger" data-dismiss="modal">Chiudi</button>
              </div>

            </div>
          </form>
        </div>
      </div>
    </div>



    <!-- MAIN TABLE -->
    <div class="row">
        <div id="calendario-box" class="calendario-outer-box shadow2">
          <table id="tableid" style="overflow:hidden;">
              <tr><td>
                <div class='scrollable'>
                <table class="calendario">
                    <tr>
                        <td class='giorno' style="border: 1px solid #fff;">Data</td>
                        <td class='gestore' style='border: 1px solid #fff;'>Gestore</td>
                        <td class='letto'></td>
                        <td class='letto'></td>
                        <td class='letto'></td>
                        <td class='letto'>Letto</td>
                        <td class='letto'></td>
                        <td class='letto'></td>
                        <td class='bivacco' style="border-left: 1px solid #fff;"></td>
                        <td class='bivacco'>Bivacco</td>
                        <td class='bivacco'></td>
                        <td class='bivacco'></td>
                        <td class="brandina" style="border-left: 1px solid #fff;">Brandina</td>
                        <td class="brandina"></td>
                        <td class="materasso" style="border-left: 1px solid #fff;"></td>
                        <td class="materasso">Materasso</td>
                        <td class="materasso"></td>
                        <td class="materasso"></td>
                    </tr>
                </table>
                </div>
              </td></tr>
              <tr><td>
                <div class="calendario-inner-box scrollable">
                <table class="calendario grid">
                  <colgroup>
                        <col class='giorno'>
                        <col class='gestore'>
                        <col class='letto'>
                        <col class='letto'>
                        <col class='letto'>
                        <col class='letto'>
                        <col class='letto'>
                        <col class='letto'>
                        <col class="bivacco">
                        <col class="bivacco">
                        <col class="bivacco">
                        <col class="bivacco">
                        <col class="brandina">
                        <col class="brandina">
                        <col class="materasso">
                        <col class="materasso">
                        <col class="materasso">
                        <col class="materasso">
                  </colgroup>
                  <tbody>
    <?

    $lista = array();
    $gest = 0;
    $gestdb = array();

    $username = "6786_utentesql";
    $password = "databasecai";
    $hostname = "localhost";

    $dbhandle = mysqli_connect($hostname, $username, $password)
     or die("Impossibile connettersi al server. Contatta il webmaster (Codice C2)");
    $selected = mysqli_select_db($dbhandle, "6786_prenotazioni")
      or die("Errore di connessione al server. Contatta il webmaster (Codice D2)");

    /*
    echo('<tr><td></td><td>DEBUG: Colors</td>');
    $coloridb = mysqli_query($dbhandle, "SELECT * FROM Colori");
    while ($row = mysqli_fetch_array($coloridb)) {
        $listaColori[] = $row;
        if ($row['last'] == 1) {
            $lastColor = $row;
        }}
    foreach ($listaColori as $color){
        echo("<td style='background:".$color['colore'].";'>");
        echo('<b>'.$color['ID'].'</b>');
        echo("</td>");
    } echo('</tr>');   */


    // Start building the table

    for($absday = $firstday; $absday<$lastday; $absday++){

        $absdate = DateTime::createFromFormat('z', $absday);

        $day = $absdate->format('j');
        $weekday = $absdate->format('N');
        $month = $absdate->format('n');

        switch ($month):
            case '6':
                $monthname = 'Giu';
                break;
            case '7':
                $monthname = 'Lug';
                break;
            case '8':
                $monthname = 'Ago';
                break;
            case '9':
                $monthname = 'Set';
                break;
            default:
                $monthname = 'ERR';
        endswitch;

        // Building tr
        echo("<tr id='".$day."-".$month."'");
            if( $day == 1 ) echo ("style='border-top: 4px solid black;'");
            if( $absday == $lastday-1 ) echo ("style='border-bottom: 4px solid #000;'");
            if( $today == $absday) echo("id='today' class='border'");
        echo(">");

        // Building Date td
        echo("<td ");
            if( $weekday == 7 ) echo("style='color:red;'");
            if( $month == 8 and $day==15 ) echo("style=color:red;");
        echo(">");
            echo($day." ".$monthname);//." / ".$absday);
        echo("</td>");


        // Building Gestore td
        // WARNING! Does not deal with overlapping
        if ($gest == 0){
            $gestdb =  mysqli_query($dbhandle, "SELECT * FROM Pernottamenti WHERE stagione = ".$year." AND (gestione=1 AND giorno_inizio=".$absday.")");
            $gest = mysqli_fetch_array($gestdb);
        }
        if ( ($gest['giorno_inizio'] + $gest['durata']) <= $absday){
            $gest = 0;
        }
        if ($gest == 0){
            echo("<td class='nogestore'>Nessuno!</td>");
        }else{
            echo("<td>");
            echo("<a id='".$absday."-G' onclick='javascript:openNewBModal(1, ".$gest['id'].", 1);' ><div>");
            echo($gest['nome']);//.' '.var_dump($listag[0]) );
            echo("</div></a>");
            echo("</td>");
        }

        // Filling the rest of the table
        $listadb = mysqli_query($dbhandle, "SELECT *
                                            FROM Pernottamenti AS p INNER JOIN Colori AS c ON p.colore = c.ID
                                            WHERE p.stagione = ".$year." AND (p.gestione=0 AND p.giorno_inizio=".$absday.")");
        while ($row = mysqli_fetch_array($listadb)) {
            $lista[] = $row; // This appends the NEW bookings to $lista, which is usually NOT empty!
        }
        $tot = 0;
        foreach($lista as $pren){
            for($i=0; $i<$pren['posti']; $i++, $tot++){
                echo("<td style='background:".$pren['colore'].";'>");
                echo("<a id='".$absday."-".$i."' onclick='javascript:openNewBModal(1, ".$pren['id'].", 0);' ><div>"); //echo("<a id='".$absday."-".$i."' href='javascript:getData(".$pren['id'].",0)'><div>");
                echo('<b>P '.$pren['id'].'</b>');
                echo("</div></a>");
                echo("</td>");
            }
            if ($pren['giorno_inizio']+ $pren['durata']-1 <= $absday){
                $key = array_search($pren, $lista);  // need to look for it, because the index is not normalized...
                unset($lista[$key]);  // pop from array
            }
        }
        for(;$tot<16; $tot++){
            echo ("<td></td>");
        }

        // Close tr
        echo ("</tr>");

    }
    mysqli_close($dbhandle);
    ?>
                  </tbody>
                </table>
              </div>
            </td></tr>
          </tbody>
        </table>

      </div>
    </div>


    <!-- FOOTER -->
    <footer>
      <div>
        <button class="btn btn-success" onclick='javascript:openNewBModal(0, 0, 0)'>Nuova Prenotazione</button>
        <!--a class="btn btn-success" onclick='javascript:openNewBModal(1, 0, 0)'>ERROR BOX</a-->
        <button class="btn btn-info" data-toggle="modal" data-target="#Adv_Modal">Avanzate</button>
        <a href="../prenotazioni/main.php" class="btn btn-danger">Logout</a>
      </div>
    </footer>



    <script type="text/javascript">

        // Reset NewB_Modal when closed
        $('#newB_Modal').on('hidden.bs.modal', function (event) {
            $('#message-alert').hide();
            $('#error-alert').hide();
            $('.modal-errfooter').hide();

            $('.modal-dataTitle').text("Prenotazione");
            $('#newB_Modal form')[0].reset();

            $('input').prop('readonly', true);
            $('#new-btn').prop('disabled', true);
            $('#new-btn').hide();
            $('#del-btn').hide();
            $('#enable-btn').show();
        });

        // Open Error_Modal in case of PHP errors (like "No more beds on these days")
        if( $('#Error_Modal').data('error') ){
            $('#Error_Modal').modal('show');
        }

    </script>


  </body>
</html>
