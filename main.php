<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <title>Prenotazioni - CAI Sovico</title>

    <link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="static/javascript/jQuery/jquery-1.11.2.min.js"></script>
    <script src="static/bootstrap/js/bootstrap.min.js"></script>
    <script src="funzioni.js"></script>

  </head>
  <body>

    <?
    $ris = 0;
    if ($_GET) {
        $ris = $_GET['ris'];
    };

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        if (isset($_POST['delbooking'])){
            try{
                deleteReservation((int)$_POST['prenid']);
            }catch (Exception $e){
                echo('<script>
                        alert("ERRORE Cancellazione!\n'.$e->getMessage().'");
                      </script>');
            }
        }else{
            if (isset($_POST['newbooking'])){
                try{
                    makeReservation();
                }catch (Exception $e){
                    echo('<script>
                            alert("ERRORE Prenotazione!\n'.$e->getMessage().'");
                          </script>');
                }
            }else{
                try{
                    updateReservation((int)$_POST['prenid']);
                }catch (Exception $e){
                    echo('<script>
                            alert("ERRORE Aggiornamento!\n'.$e->getMessage().'");
                          </script>');
                }
            }
        }
    }


    // *************** MAKE RESERVATION *******************************

    function makeReservation(){

        $username = "6786_utentesql";
        $password = "databasecai";
        $hostname = "localhost";

        $dbhandle = mysqli_connect($hostname, $username, $password)
          or die("Unable to connect to MySQL");
        $selected = mysqli_select_db($dbhandle, "6786_pernottamenti")
          or die("Could not select database");

        $validData = validate($dbhandle);
        if (isset($validData['ERROR'])){
            echo("<script>alert('".$validData['ERROR']."');</script>");
        }else{

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
                if ($result2 == 0) throw new Exception('Color update failed!');

            }else{
                mysqli_close($dbhandle);
                throw new Exception('Registration failed!');
                }
        }

        mysqli_close($dbhandle);
    }


// *************** UPDATE RESERVATION *******************************

    function updateReservation($prenid){
        $username = "6786_utentesql";
        $password = "databasecai";
        $hostname = "localhost";

        $dbhandle = mysqli_connect($hostname, $username, $password)
          or die("Unable to connect to MySQL");
        $selected = mysqli_select_db($dbhandle, "6786_pernottamenti")
          or die("Could not select database");

        $validData = validate($dbhandle);
        if (isset($validData['ERROR'])){
            echo("<script>alert('".$validData['ERROR']."');</script>");
        }else{

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
                throw new Exception('Update failed! '.$result);
                }
        }

        mysqli_close($dbhandle);
    }


// *************** DELETE RESERVATION *******************************

    function deleteReservation($prenid){
        $username = "6786_utentesql";
        $password = "databasecai";
        $hostname = "localhost";

        $dbhandle = mysqli_connect($hostname, $username, $password)
          or die("Unable to connect to MySQL");
        $selected = mysqli_select_db($dbhandle, "6786_pernottamenti")
          or die("Could not select database");

        // Delete reservation in DB
        $result = mysqli_query($dbhandle, "DELETE FROM Pernottamenti WHERE ID = ".$prenid);
        if ($result == False){
            mysqli_close($dbhandle);
            throw new Exception('Deletion failed! '.$result);
        }

        mysqli_close($dbhandle);
    }


// *************** VALIDATION ****************************************

    function validate($dbhandle) {
        if ($_POST['nome']== '')  throw new Exception("Inserire il nome del cliente.");
        if (strlen($_POST['nome'])> 100)  throw new Exception("Inserire solo il nome del cliente nella prima riga!");

        if ($_POST['telefono']== '')  throw new Exception("Inserire un numero di telefono.");
        if (strlen($_POST['telefono'])> 15)  throw new Exception("Inserire un numero di telefono valido.");

        if ((int)$_POST['arrivo']== '')  throw new Exception("Inserire una data di arrivo valida.");
        $absdate = mysqli_real_escape_string($dbhandle, date('z', strtotime($_POST['arrivo'])-1));

        if ((int)($_POST['durata'])<= 0 or (int)($_POST['durata'])>= 122) throw new Exception("La durata del soggiorno non e' valida.");

        if ((int)($_POST['posti'])<= 0 or (int)($_POST['durata'])>= 15) throw new Exception("Inserire un numero di posti prenotati valido.");

        if (strlen($_POST['note'])> 1000) throw new Exception("Note troppo lunghe! Massimo 1000 caratteri.");

        $gestione = 0;
        if (isset($_POST['gestione'])) {
           $gestione = 1;
        }

        if ($_POST['responsabile']== '' and $gestione == 0) throw new Exception("Inserire il nome del responsabile della prenotazione.");
        if (strlen($_POST['responsabile'])> 100) throw new Exception("Nome del responsabile della prenotazione troppo lungo!");

        return array(
            'nome' => mysqli_real_escape_string($dbhandle, $_POST['nome']),
            'telefono' => mysqli_real_escape_string($dbhandle, $_POST['telefono']),
            'arrivo' => $absdate,
            'durata' => (int)mysqli_real_escape_string($dbhandle, $_POST['durata']),
            'posti' => mysqli_real_escape_string($dbhandle, $_POST['posti']),
            'note' => mysqli_real_escape_string($dbhandle, $_POST['note']),
            'gestione' => $gestione,
            'resp' => mysqli_real_escape_string($dbhandle, $_POST['responsabile']),
        );
    }

    ?>

    <div class="title shadow1">
        <h2>Prenotazioni Stagione 2016</h2>
        <h4>Rifugio M. Del Grande - R. Camerini</h4>
        <p>ATTENZIONE: I tipi di sistemazione (letto, brandina, bivacco etc...)
        sono provvisori e la disposizione effettiva dei posti letto verrà
        concordata con i gestori una volta giunti al Rifugio.</p>
    </div>


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

    // BE CAREFUL HERE - I SET EVERYWHERE A FIXED 2016

    $firstday = date('z', strtotime('01-06-2016')-1); //   ONLY dd-mm-yyyy OR mm/dd/yyyy are recognized correctly
    $lastday = date('z', strtotime('1-10-2016')-1);   // -1 is due to an apparent incompatible conversion between strtotime() and format()
    $today = date('z', strtotime('05-06-2016')-1);
    //$today = date('z') - $firstday;


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
                echo("<a id='".$absday."-G' href='javascript:getData(".$gest['id'].", 1)' ><div>");
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
                    if ($ris == 1 ) echo("<a id='".$absday."-".$i."' href='javascript:getData(".$pren['id'].",0)'><div>");
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


    <!-- LEFT BOX -->
    <? if ($ris==1){ ?>
        <div id="data-box" class="data-hidden">
          <a id='close-btn' onclick='javascript:hideBox()'>CHIUDI</a>
          <div id="left-box" class="inner-box white shadow2">
              <img id="loading" src="static/images/spinningwheel.gif" style='display:none;' />
              <h3 id='left-box-title'><span id='left-span-bt'>Prenotazione № </span><span id='left-span-num'></span></h3>
              <p id='left-box-pn'><b id='left-bn'>Nome Cliente</b>: <span id='left-span-nome'></span></p>
              <p id='left-box-pt'><b>№ Telefono</b>: <span id='left-span-tel'></span></p>
              <p id='left-box-pa'><b>Data Arrivo</b>: <span id='left-span-arrivo'></span></p>
              <p id='left-box-pd'><b>Durata</b>: <span id='left-span-durata'></span></p>
              <p id='left-box-pp'><b>Posti prenotati</b>: <span id='left-span-posti'></span></p>
              <p id='left-box-pr'><b>Responsabile</b>: <span id='left-span-resp'></span></p>
              <a id='modify-btn' class="btn btn-success" data-toggle="modal"  data-fillme=1 data-title="Modifica" data-gestione='0' data-target="#newB_Modal">Modifica Dati</a>
              <form method='POST'>
                <div class="hidden">
                  <input type="checkbox" name='delbooking' id='chk_del'  checked='true'>
                </div>
                <div class="hidden">
                  <input type="text" name='prenid' id='left-prenid' >
                </div>
                <input type='submit' id='delete-btn' class='btn btn-danger' onclick='javascript:deleteBooking()' value='Elimina Prenotazione'>
              </form>
          </div>
        </div>


    <!-- MODAL -->
        <div class="modal fade" id="newB_Modal" tabindex="-1" role="dialog" aria-labelledby="newB_ModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 id='modal-title' class="modal-title">Prenotazione</h2>
              </div>
              <form class='form-horizontal' method='POST'>
                <div class="modal-body">
                  <div class="form-group" >
                    <label class="col-sm-3 control-label">Nome Cliente</label>
                    <div class="col-sm-9" >
                        <input type="text" class="form-control" name="nome" id='mod_nome' placeholder="Nome Cliente">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">№ Telefono</label>
                    <div class="col-sm-9" >
                        <input type="text" class="form-control" name="telefono" id='mod_tel' placeholder="№ Telefono">
                    </div>
                  </div>
                  <hr/>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Data di Arrivo</label>
                    <div class="col-sm-9" >
                        <input type="date" class="form-control" name="arrivo" id='mod_arrivo' placeholder="Giorno-Mese-Anno">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Durata del Soggiorno</label>
                    <div class="col-sm-9" >
                        <input type="text" class="form-control" name="durata" id='mod_durata' placeholder="Durata del Soggiorno">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Posti Prenotati</label>
                    <div class="col-sm-9" >
                        <input type="text" class="form-control" name="posti" id='mod_posti' placeholder="Posti Prenotati">
                    </div>
                  </div>
                  <hr/>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Responsabile Prenotazione</label>
                    <div class="col-sm-9" >
                        <input type="text" class="form-control" name="responsabile" id='mod_resp' placeholder="Responsabile Prenotazione">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Note</label>
                    <div class="col-sm-9" >
                        <input type="textarea" class="form-control" name="note" id='mod_note'placeholder="Note...">
                    </div>
                  </div>
                  <div class="form-group">
                      <div class="col-sm-offset-3 col-sm-9 checkbox">
                        <label>
                          <input name='gestione' id='mod_gest' type="checkbox"> Sono gestori
                        </label>
                      </div>
                  </div>
                  <div class="hidden checkbox">
                    <input name='newbooking' id='mod_new' type="checkbox" checked='true'> Nuova Prenotazione
                  </div>
                  <div class="hidden">
                    <input name='prenid' id='mod_prenid' type="text">
                  </div>
                </div>
                <div class="modal-footer center">
                  <input type="submit" class="btn btn-primary" value='Salva'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                </div>
              </form>
            </div>
          </div>
        </div>

    <? } ?>

    <!-- FOOTER -->
        <footer>
          <div class="footer-div">
            <? if ($ris == 1){ ?>
            <a class="btn btn-success" data-toggle="modal" data-fillme=0 data-title="Nuova" data-gestione='0' data-target="#newB_Modal">Nuova Prenotazione</a>
            <a href="main.php?ris=0" class="btn btn-danger" style='position:relative;float:right;'>Logout</a>
            <? }else{ ?>
            <a href="main.php?ris=1" class="btn btn-success">Area Riservata</a>
            <? } ?>
          </div>
        </footer>



    <script type="text/javascript">

    // Fill the modal when loaded
        $('#newB_Modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);  // Button that triggered the modal
            var title = button.data('title') + ' Prenotazione';  // Extract info from data-* attributes

            if(button.data('fillme')==1) {
                var decoded = retrieveData(button.data('gestione'), button.data('num'));
                var modal = $(this);
                title = title + ' № ' + button.data('num')
                $('#mod_prenid').val(button.data('num'));
                $('#mod_nome').val(decoded.nome);
                $('#mod_tel').val(decoded.tel);
                $('#mod_arrivo').val(decoded.arrivo);
                $('#mod_durata').val(decoded.durata);
                $('#mod_posti').val(decoded.posti);
                $('#mod_resp').val(decoded.resp);
                $('#mod_note').val(decoded.note);
                $('#mod_gest').prop('checked', button.data('gestione'));
                $('#mod_new').prop('checked', false);
            }
            $('#modal-title').text(title);
        });

    // Reset the modal when closed
        $('#newB_Modal').on('hide.bs.modal', function (event) {
            var title = 'Nuova Prenotazione';
            $(this).find('form')[0].reset()
        });

    </script>


  </body>
</html>
