<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <title>Prenotazioni - CAI Sovico</title>

    <link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="static/javascript/jQuery/jquery-1.11.2.min.js"></script>
    <script src="static/javascript/jQuery/jquery.validate.min.js"></script>
    <script src="static/bootstrap/js/bootstrap.min.js"></script>
    <script src="funzioni.js"></script>
    
    <script type="text/javascript">


	    $.validator.addMethod("customData", function(value) {
	    // test this terrible regex here http://www.regular-expressions.info/javascriptexample.html
	    // Matches only days between 1 June and 30 Sept
	    
	    // SHOULD CHECK THE CURRENT YEAR TOO!!
	    var re = new RegExp("^(((0[1-9]|1[0-9]|2[0-9]|30)-(0[6-9]))|((31)-(0[7-8])))-20[0-9][0-9]$")
		    return re.test(value);
	    }, 'Inserire una data di arrivo valida (GG-MM-AAAA) compresa tra 01-06-2016 e 30-09-2016');
	    
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
    $ris = 0;
    if ($_GET) {
        $ris = $_GET['ris'];
    };

    // Some global variables moved here

    $error = false;
    $error_message = "";

    // BE CAREFUL HERE - I SET EVERYWHERE A FIXED 2016
    $firstday = date('z', strtotime('01-06-2016')-1); //   ONLY dd-mm-yyyy OR mm/dd/yyyy are recognized correctly
    $lastday = date('z', strtotime('1-10-2016')-1);
    $today = date('z', strtotime('05-06-2016')-1);
    //$today = date('z') - $firstday;



    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $username = "6786_utentesql";
        $password = "databasecai";
        $hostname = "localhost";

        $dbhandle = mysqli_connect($hostname, $username, $password)
            or die("Unable to connect to MySQL");
        $selected = mysqli_select_db($dbhandle, "6786_pernottamenti")
            or die("Could not select database");


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
                    makeReservation($dbhandle);
                }catch (Exception $e){
                    $error = true;
                    $error_message = $e->getMessage();
                }
            }else{
                try{
                    updateReservation($dbhandle, (int)$_POST['prenid']);
                }catch (Exception $e){
                    $error = true;
                    $error_message = $e->getMessage();
                }
            }
        }

        mysqli_close($dbhandle);
    }


    // *************** MAKE RESERVATION *******************************

    function makeReservation($dbhandle){

        $validData = validate($dbhandle);
        checkAssertions($dbhandle, $validData, 0);

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
                    $validData['durata']."', '".
                    $validData['posti']."', '".
                    $validData['note']."', '".
                    $validData['gestione']."', '".
                    $validData['resp']."', '".
                    $newColor."')";
        $result = mysqli_query($dbhandle, "INSERT INTO `Pernottamenti`
                                (`id`, `nome`, `tel`, `giorno_inizio`, `durata`, `posti`, `note`, `gestione`, `responsabile`, `colore`)
                                VALUES".$values);
        if ($result == 1){
            // Update Last Color
            $maxLast = mysqli_fetch_array(mysqli_query($dbhandle, "SELECT MAX(last) FROM Colori") )[0];

            $result2 = mysqli_query( $dbhandle, "UPDATE Colori SET last = ".($maxLast+1)." WHERE ID = ".$newColor);
            if ($result2 == 0) throw new Exception("Errore interno al server:<br>la prenotazione È stata comunque effettuata.<br>Avverti il webmaster (Codice C).");

        }else{
            mysqli_close($dbhandle);
            throw new Exception("Errore interno al server durante la registrazione della prenotazione:<br>la prenotazione NON è stata effettuata.<br>Avverti il webmaster (Codice R).");
            }

    }


// *************** UPDATE RESERVATION *******************************

    function updateReservation($dbhandle, $prenid){

        $validData = validate($dbhandle);
        checkAssertions($dbhandle, $validData, $prenid);
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
            throw new Exception($result."Errore interno al server:<br>L'aggiornamento NON è stato effettuato.<br>Avverti il webmaster (Codice U).".$result);
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


// *************** DATA VALIDATION ****************************************

    function validate($dbhandle) {
        
        echo("here!!");
        
        $firstday = date('z', strtotime('01-06-2016')-1); //   ONLY dd-mm-yyyy OR mm/dd/yyyy are recognized correctly
        $lastday = date('z', strtotime('1-10-2016')-1);

        if ($_POST['nome']== '')  throw new Exception("Nome del cliente non valido!");
        
        if ($_POST['telefono']== '')  throw new Exception("Numero di telefono non valido!");
        
        if ((int)($_POST['durata'])<= 0 or (int)($_POST['durata'])>= 122) throw new Exception("Durata della prenotazione non valida!");

        if ((int)$_POST['arrivo']= '')  throw new Exception("Data di arrivo non valida!");
        $absdate = date('z', strtotime($_POST['arrivo'])-1);
        echo(strtotime($_POST['arrivo']));
        echo($_POST['arrivo']);

        if ((int)($_POST['posti'])<= 0 or (int)($_POST['posti'])>= 15) throw new Exception("Numero di posti prenotati non valido!");

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

    function checkAssertions($dbhandle, $data, $prenid){
        // Notice: here I assume that no booking will have ID = 0, because 0 means basically "do not check"

        $dayslist = array();

        for($giorno=$data['arrivo']; $giorno < ($data['arrivo']+$data['durata']); $giorno++ ){
            $result = mysqli_fetch_array(mysqli_query($dbhandle, "
                    SELECT SUM(posti) FROM Pernottamenti
                    WHERE ( giorno_inizio <= ".$giorno." AND (giorno_inizio + durata) >= ".$giorno.")
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

            //print_r($dayslist);

            mysqli_close($dbhandle);
            if (!$data['gestione']){
                throw new Exception("Impossibile prenotare!<br>Il Rifugio è già pieno nelle date:".$errorstring."<br>La prenotazione NON è stata registrata.");
            }else{
                throw new Exception("Attenzione!<br>C'è già un gestore in queste date: ".$errorstring."<br>La prenotazione NON è stata registrata.");
            }

        }


    }

    ?>

    <div class="title shadow1">
        <h2>Prenotazioni Stagione 2016</h2>
        <h4>Rifugio M. Del Grande - R. Camerini</h4>
        <p>ATTENZIONE: I tipi di sistemazione (letto, brandina, bivacco etc...)
        sono provvisori e la disposizione effettiva dei posti letto verrà
        concordata con i gestori una volta giunti al Rifugio.</p>
    </div>


    <? if ($ris==1){ ?>

    <!-- ERROR ALERT -->
    <div class="modal fade" id="Error_Modal" data-error="<? if($error) {?>1<?}else{?>0<?}?>" tabindex="-1" role="dialog" aria-labelledby="Error_ModalLabel">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-dataTitle modal-title" style='color:red;'><b>ERRORE</b></h3>
              </div>

              <div class="modal-body center">
                <p id='errormsg'><? echo($error_message); ?></p>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="loadingTitle modal-title" style='display:none;'>Caricamento...</h2>
                <h2 id='newB_ModalTitle..' class="modal-dataTitle modal-title">Prenotazione</h2> 
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
                          <input id="modnome" type="text" class="mod-nome form-control" name="nome" placeholder="Nome Cliente" readonly="readonly" data-rule-required="true" data-msg-required="Inserire il nome del cliente">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">№ Telefono</label>
                      <div class="col-sm-9" >
                          <input id="modtel" type="text" class="mod-tel form-control" name="telefono" placeholder="№ Telefono" readonly="readonly" data-rule-required="true" data-msg-required="Inserire il numero di telefono" data-rule-digits="true" data-msg-digits="Inserire unnumero di telefono valido">
                      </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Data di Arrivo</label>
                      <div class="col-sm-9" >
                          <input id="modarrivo" type="text" class="mod-arrivo form-control" name="arrivo" placeholder="Giorno-Mese-Anno" readonly="readonly" data-rule-required="true" data-msg-required="Inserire una data di arrivo" data-rule-customData="true">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Durata del Soggiorno</label>
                      <div class="col-sm-9" >
                          <input id="moddurata" type="text" class="mod-durata form-control" name="durata" placeholder="Durata del Soggiorno" readonly="readonly" data-rule-required="true" data-msg-required="Inserire la durata del soggiorno" data-rule-digits="true" data-msg-digits="Inserire una durata del soggiorno valida" data-rule-customDurata="true">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Posti Prenotati</label>
                      <div class="col-sm-9" >
                          <input id="modposti" type="text" class="mod-posti form-control" name="posti" placeholder="Posti Prenotati" readonly="readonly" data-rule="true" data-msg-required="Inserire il numero di posti letto prenotati" data-rule-digits="true" data-msg-digits="Inserire un numero di posti letto valido" data-rule-customPosti="true">
                      </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Responsabile Prenotazione</label>
                      <div class="col-sm-9" >
                          <input id="modresp" type="text" class="mod-resp form-control" name="responsabile" placeholder="Responsabile Prenotazione" readonly="readonly" data-rule-required="true" data-msg-required='Inserisci il tuo nome nel campo "Responsabile della prenotazione"'>
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
                            <input id="modgest" name='gestione' class='mod-gest' type="checkbox" readonly="readonly"> Sono gestori
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
                    <a id="enable-btn" class="btn btn-warning" href="javascript:enableEditing(0);">Modifica</a>
                    <input id="del-btn" class="btn btn-danger" disabled="disabled" type="submit" value="Elimina" style='display:none;'>
                    <input id="new-btn" class="btn btn-primary" disabled="disabled" type="submit" value="Salva">
                    <!-- onclick="javascript:validate_and_send(0,0);" -->
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

    <? } ?>



    <!-- MAIN TABLE -->
    <div class="row">
        <div id="calendario-box" class="cal-full calendario-outer-box shadow2">
          <table id="tableid" style="overflow:hidden;">
              <tr><td>
                <div class='scrollable'>
                <table class="calendario">
                    <tr>
                        <td class='giorno' style="border: 1px solid #fff;">Data</td>
                        <? if ($ris==1)
                            echo("<td class='gestore' style='border: 1px solid #fff;'>Gestore</td>")
                        ?>
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
                        <? if ($ris == 1)
                            echo("<col class='gestore'>")
                        ?>
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

    $lista = [];
    $gest = 0;
    $gestdb = [];

    $username = "6786_utentesql";
    $password = "databasecai";
    $hostname = "localhost";

    $dbhandle = mysqli_connect($hostname, $username, $password)
     or die("Unable to connect to MySQL");
    $selected = mysqli_select_db($dbhandle, "6786_pernottamenti")
      or die("Could not select database");

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
            echo($day." ".$monthname." / ".$absday);
        echo("</td>");


        // Building Gestore td
        if ($ris == 1){
            // WARNING! Does not deal with overlapping
            if ($gest == 0){
                $gestdb =  mysqli_query($dbhandle, "SELECT * FROM Pernottamenti WHERE (gestione=1 AND giorno_inizio=".$absday.")");
                $gest = mysqli_fetch_array($gestdb);
            }
            if ( ($gest['giorno_inizio'] + $gest['durata']) <= $absday){
                $gest = 0;
            }
            if ($gest == 0){
                echo("<td style='background:#EFB8A3'>Nessuno!</td>");
            }else{
                echo("<td>");
                echo("<a id='".$absday."-G' onclick='javascript:openNewBModal(1, ".$gest['id'].", 1);' ><div>");
                echo($gest['nome']);//.' '.var_dump($listag[0]) );
                echo("</div></a>");
                echo("</td>");
            }

        }

        // Filling the rest of the table
        $listadb = mysqli_query($dbhandle, "SELECT *
                                            FROM Pernottamenti AS p INNER JOIN Colori AS c ON p.colore = c.ID
                                            WHERE (p.gestione=0 AND p.giorno_inizio=".$absday.")");
        while ($row = mysqli_fetch_array($listadb)) {
            $lista[] = $row; // This appends the NEW bookings to $lista, which is usually NOT empty!
        }
        $tot = 0;
        foreach($lista as $pren){
            for($i=0; $i<$pren['posti']; $i++, $tot++){
                echo("<td style='background:".$pren['colore'].";'>");
                    if ($ris == 1 ) echo("<a id='".$absday."-".$i."' onclick='javascript:openNewBModal(1, ".$pren['id'].", 0);' ><div>"); //echo("<a id='".$absday."-".$i."' href='javascript:getData(".$pren['id'].",0)'><div>");
                echo('<b>P '.$pren['id'].'</b>');
                    if ($ris == 1 ) echo("</div></a>");
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
          <div class="footer-div">
            <? if ($ris == 1){ ?>
            <a class="btn btn-success" onclick='javascript:openNewBModal(0, 0, 0)'>Nuova Prenotazione</a>
            <a class="btn btn-success" onclick='javascript:openNewBModal(1, 0, 0)'>LEFT BOX</a>
            <a href="main.php?ris=0" class="btn btn-danger" style='position:relative;float:right;'>Logout</a>
            <? }else{ ?>
            <a href="main.php?ris=1" class="btn btn-success">Area Riservata</a>
            <? } ?>
          </div>
        </footer>



    <script type="text/javascript">
    

      /*
        $('new').click( function() {

            var form = $('#booking-form');
            var alertbox = $('#message-alert');
            var haderror = false

            if( $('.mod-nome').value == undefined ){
                haderror = true;
                alertbox.text("Inserire il nome del cliente.")
            }
            else if( $('.mod-nome').value.lenght > 100 ){
                haderror = true;
                alertbox.text("Inserire solo il nome del cliente nella prima riga (massimo 100 caratteri).");
            }
            else if( $('.mod-tel').value == undefined ){
                haderror = true;
                alertbox.text("Inserire un numero di telefono.");
            }
            else if( $('.mod-tel').value.lenght > 15 ){
                haderror = true;
                alertbox.text("Inserire un numero di telefono valido.");
            }
            // Controllare che il numero di telefono sia composto di sole cifre!!
            else if( $('.mod-durata').value < 1 || $('.mod-durata').value > 122 ){
                haderror = true;
                alertbox.text("Inserire una durata del pernottamento valida.");
            }
            else if( $('.mod-arrivo').value == undefined ){
                haderror = true;
                alertbox.text("Inserire una data di arrivo.");
            }else if( $('.mod-posti').value < 1 || $('.mod-posti').value > 16 ){
                haderror = true;
                alertbox.text("Inserire un numero di posti prenotati valido. Attenzione: sono disponibili al massimo 16 posti letto.");
            }
            else if( $('.mod-note').value != undefined ){
                if( $('.mod-note').value.lenght > 1000 ){
                    haderror = true;
                    alertbox.text("Attenzione! La nota è troppo lunga (massimo 1000 caratteri)");
                }
            }
            else if( $('.mod-resp').value == undefined && $('.mod-gest').prop('checked') != 'checked' ){
                haderror = true;
                alertbox.text("Inserisci il tuo nome nel campo 'Responsabile della Prenotazione'.");
            }

            if( haderror ){
                alertbox.show();
                haderror = false;
            }else{
                form.submit();
                $('#newB_Modal').modal('hide');
            }

        return false;
        } );

      */

        // Reset NewB_Modal when closed
        $('#newB_Modal').on('hidden.bs.modal', function (event) {
            $('#message-alert').hide();
            $('#error-alert').hide();
            $('.modal-errfooter').hide();
            
            $('.modal-dataTitle').text("Prenotazione");
            $('#newB_Modal form')[0].reset();
            
            $('input').prop('readonly', true);
            $('#new-btn').prop('disabled', true);
            $('#del-btn').hide();
            $('#del-btn').prop('disabled', true);
            $('#enable-btn').show();
        });

        // Open Error_Modal in case of errors  -- fallback error catcher, I may think about removing this...
        if( $('#Error_Modal').data('error') ){
            $('#Error_Modal').modal('show');
        }

    </script>


  </body>
</html>
