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

        $username = "6786_utentesql";
        $password = "databasecai";
        $hostname = "localhost";

        $dbhandle = mysqli_connect($hostname, $username, $password)
            or die("Unable to connect to MySQL");
        $selected = mysqli_select_db($dbhandle, "6786_pernottamenti")
            or die("Could not select database");


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

        mysqli_close($dbhandle);
    }


    // *************** MAKE RESERVATION *******************************

    function makeReservation(){

        $validData = validate($dbhandle);
        checkAssertions($validData);
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

    }


// *************** UPDATE RESERVATION *******************************

    function updateReservation($prenid){

        $validData = validate($dbhandle);
        checkAssertions($validData);
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

    }


// *************** DELETE RESERVATION *******************************

    function deleteReservation($prenid){

        $result = mysqli_query($dbhandle, "DELETE FROM Pernottamenti WHERE ID = ".$prenid);
        if ($result == False){
            mysqli_close($dbhandle);
            throw new Exception('Deletion failed! '.$result);
        }

    }


// *************** DATA VALIDATION ****************************************

    function validate($dbhandle) {
        if ($_POST['nome']== '')  throw new Exception("Inserire il nome del cliente.");
        if (strlen($_POST['nome'])> 100)  throw new Exception("Inserire solo il nome del cliente nella prima riga!");

        if ($_POST['telefono']== '')  throw new Exception("Inserire un numero di telefono.");
        if (strlen($_POST['telefono'])> 15)  throw new Exception("Inserire un numero di telefono valido.");

        if ((int)$_POST['arrivo']== '')  throw new Exception("Inserire una data di arrivo valida.");
        $replaced = str_replace("/", "-", $_POST['arrivo']);
        $replaced = str_replace(".", "-", $replaced);
        $replaced = str_replace("\\", "-", $replaced);
        $replaced = str_replace(" ", "-", $replaced);
        echo $replaced;
        echo $_POST['arrivo'];
        $absdate = mysqli_real_escape_string($dbhandle, date('z', strtotime($replaced)-1));

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

// *************** DB ASSERTIONS ****************************************

    function checkAssertions($data){
        return;

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
                echo("<a id='".$absday."-G' href=# onclick='javascript:prepareLeftModal(".$gest['id'].", 1);' ><div>");
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
                    if ($ris == 1 ) echo("<a id='".$absday."-".$i."' href=# onclick='javascript:prepareLeftModal(".$pren['id'].", 0);' ><div>"); //echo("<a id='".$absday."-".$i."' href='javascript:getData(".$pren['id'].",0)'><div>");
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



    <? if ($ris==1){ ?>


    <!-- DATA MODAL -->
    <div class="modal fade" id="LeftBox_Modal" tabindex="-1" role="dialog" aria-labelledby="LeftBox_ModalLabel">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header center">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 id='loadingL-title' class="modal-title">Caricamento...</h3>
                <h3 id='leftmodal-title' class="modal-dataTitle modal-title"></h3>
                <h3 id='lefts-num' class="modal-title"></h3>
              </div>
              <div class="modal-body">

                <img id="loadingL" src="static/images/spinningwheel.gif" style='width:40%; margin:30%; display:none;' />
                <p id='messageL' style='display:none;'></p>

                <div class="modal-databox form-group" style='display:none;'>
                  <p id='left-pn'><b>Nome Cliente</b>: <span class='mod-nome'></span></p>
                  <p id='left-pt'><b>№ Telefono</b>: <span class='mod-tel'></span></p>
                  <p id='left-pa'><b>Data Arrivo</b>: <span class='mod-arrivo'></span></p>
                  <p id='left-pd'><b>Durata</b>: <span class='mod-durata'></span></p>
                  <p id='left-pp'><b>Posti prenotati</b>: <span class='mod-posti'></span></p>
                  <p id='left-pr'><b>Responsabile</b>: <span class='mod-resp'></span></p>
                  <p id='left-pno'><b>Note</b>: <span class='mod-note'></span></p>
                </div>

              </div>
              <div id='left-footer' class="modal-footer center" style='display:none;'>

                <a id='modify-btn' class="btn btn-success" onclick='javascript:switch2NewBModal(0, 0, 0)' >Modifica Dati</a>
                <a id='delete-btn' class='btn btn-danger' onclick='javascript:deleteBooking(0, 0)' >Elimina Prenotazione</a>

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
                <h2 id='loadingNB-title' class="modal-title">Caricamento...</h2>
                <h2 id='newB_ModalTitle' class="modal-dataTitle modal-title">Prenotazione</h2>
              </div>
              <form class='form-horizontal' method='POST'>
                <div class="modal-body">

                  <img id="loadingNB" src="static/images/spinningwheel.gif" style='width:40%; margin:30%; display:none;' />
                  <p id='messageNB' style='display:none;'></p>

                <div class="modal-databox" >
                  <div class="form-group" >
                    <label class="col-sm-3 control-label">Nome Cliente</label>
                    <div class="col-sm-9" >
                        <input type="text" class="mod-nome form-control" name="nome" placeholder="Nome Cliente">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">№ Telefono</label>
                    <div class="col-sm-9" >
                        <input type="text" class="mod-tel form-control" name="telefono" placeholder="№ Telefono">
                    </div>
                  </div>
                  <hr/>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Data di Arrivo</label>
                    <div class="col-sm-9" >
                        <input type="date" class="mod-arrivo form-control" name="arrivo"placeholder="Giorno-Mese-Anno">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Durata del Soggiorno</label>
                    <div class="col-sm-9" >
                        <input type="text" class="mod-durata form-control" name="durata" placeholder="Durata del Soggiorno">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Posti Prenotati</label>
                    <div class="col-sm-9" >
                        <input type="text" class="mod-posti form-control" name="posti" placeholder="Posti Prenotati">
                    </div>
                  </div>
                  <hr/>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Responsabile Prenotazione</label>
                    <div class="col-sm-9" >
                        <input type="text" class="mod-resp form-control" name="responsabile" placeholder="Responsabile Prenotazione">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Note</label>
                    <div class="col-sm-9" >
                        <input type="textarea" class="mod-note form-control" name="note" placeholder="Note...">
                    </div>
                  </div>
                  <div class="form-group">
                      <div class="col-sm-offset-3 col-sm-9 checkbox">
                        <label>
                          <input name='gestione' class='mod-gest' type="checkbox"> Sono gestori
                        </label>
                      </div>
                  </div>
                  <div class="hidden checkbox">
                    <input name='newbooking' class='mod-new' type="checkbox" checked='true'> Nuova Prenotazione
                  </div>
                  <div class="hidden">
                    <input name='prenid' class='mod-prenid' type="text">
                  </div>
                </div>
                <div class="modal-footer center">
                  <input type="submit" class="btn btn-primary" value='Salva'>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>

                </div> <!-- modal-databox -->
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
            <a class="btn btn-success" onclick='javascript:openNewBModal(0, 0, 0)'>Nuova Prenotazione</a>
            <a class="btn btn-success" onclick='javascript:openLeftModal(1, 0, 0)'>LEFT BOX</a>
            <a href="main.php?ris=0" class="btn btn-danger" style='position:relative;float:right;'>Logout</a>
            <? }else{ ?>
            <a href="main.php?ris=1" class="btn btn-success">Area Riservata</a>
            <? } ?>
          </div>
        </footer>



    <script type="text/javascript">


    // Reset NewB_Modal when closed
        $('#newB_Modal').on('hidden.bs.modal', function (event) {
            $(this).find('form')[0].reset()
        });

        $('#LeftBox_Modal').on('hidden.bs.modal', function (event) {
            $("#leftmodal-title").text('');
            $("#lefts-num").text('');
            $('#modal-databox').hide();
            $('#left-footer').hide();
            $('#loadingL').show();
            $('#loadingTitle').show();
            $('#message').hide();
            $('#message').text('');
        });

    </script>


  </body>
</html>
