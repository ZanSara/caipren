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
        $ris = $_GET['ris'];;
    };
    ?>

    <span class="title">
        <h2>Prenotazioni</h2>
        <h3>Rifugio Del Grande Camerini</h3>
    </span>

    <div class="row">
        <div id="calendario-box">

            <table id="tableid" class="calendario">
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
                <thead>
                    <tr>
                        <th class='giorno' style="border: 1px solid #aaa;">Data</th>
                        <? if ($ris==1)
                            echo("<th class='gestore' style='border: 1px solid #aaa;'>Gestore</th>")
                        ?>
                        <th class='letto'></th>
                        <th class='letto'></th>
                        <th class='letto'></th>
                        <th class='letto'>Letto</th>
                        <th class='letto'></th>
                        <th class='letto'></th>
                        <th class='bivacco' style="border-left: 1px solid #aaa;"></th>
                        <th class='bivacco'>Bivacco</th>
                        <th class='bivacco'></th>
                        <th class='bivacco'></th>
                        <th class="brandina" style="border-left: 1px solid #aaa;">Brandina</th>
                        <th class="brandina"></th>
                        <th class="materasso" style="border-left: 1px solid #aaa;"></th>
                        <th class="materasso">Materasso</th>
                        <th class="materasso"></th>
                        <th class="materasso"></th>
                    </tr>
                </thead>
              <tbody>
    <?

    $today = array();
    $today['absday'] = '5';
    $firstweekday = 4;

    $daysum = 122;
    $calendario = array();
    $gestioni = array();

    for($i=0; $i<$daysum; $i++){
        $gestioni[$i] = 0;
        $calendario[$i] = array();
        for($j=0; $j<17; $j++){
            $calendario[$i][$j] = 0;
        }
    }

    for($absday = 0; $absday<$daysum; $absday++){
        if ($absday - 30 >= 0){
            if ($absday - 61 >= 0){
                if ($absday - 92 >= 0){
                    $month = 9;
                    $monthname = "Set";
                    $day = $absday - 91;
                }else{
                    $month = 8;
                    $monthname = "Ago";
                    $day = $absday - 60;
                }
            }else{
                $month = 7;
                $monthname = "Lug";
                $day = $absday - 29;
            }
        }else{
            $month = 6;
            $monthname = "Giu";
            $day = $absday + 1;
        }

        // Building tr
        echo("<tr id='".$day."-".$month."'");
        if( $day == 1 ) echo ("style='border-top: 4px solid black;'");
        if( $today['absday'] == $absday) echo("id='today' class='border'");
        echo(">");

        // Building Date td
        echo("<td ");
        if( ($firstweekday+$absday-1) % 7 == 6 ) echo("style='color:red;'");
        if( $month == 8 and $day==15 ) echo("style=color:red;");
        echo(">".$day." ".$monthname." / ".$absday);
        echo("</td>");

        // Building Gestore td
        if ($ris == 1){
            echo("<td ");
            echo(">");
            echo("<a id='".$absday."-G' href='javascript:getData(".$gestioni[$absday].", 1)'><div>");
            echo($gestioni[$absday]);
            echo("</div></a>");
            echo("</td>");
        }

        // Some hardcoded placeholder bookings
        $calendario[4][3] = array(1, 'white', 0);
        $calendario[4][1] = array(1, 'white', 0);
        $calendario[4][2] = array(1, 'white', 0);
        $calendario[5][3] = array(1, 'white', 0);
        $calendario[5][1] = array(1, 'white', 0);
        $calendario[5][2] = array(1, 'white', 0);
        $calendario[4][4] = array(2, 'red', 0);
        $calendario[5][4] = array(2, 'red', 0);
        $calendario[6][0] = array(3, 'blue', 1);
        $calendario[4][0] = array(3, 'blue', 1);
        $calendario[5][0] = array(3, 'blue', 1);


        // Filling the rest of the table
        for($i=0; $i<16; $i++){
            if ($calendario[$absday][$i] != 0){
                echo("<td class='booked' style='background:".$calendario[$absday][$i][1].";'");
                echo("class='booked'");
                echo(">");
                if ($ris == 1 ) echo("<a id='".$absday."-".$i."' href='javascript:getData(".$calendario[$absday][$i][0].", 0)' onblur='javascript:hideBox()' ><div>");
                echo("<b>".$calendario[$absday][$i][0]."</b></div></a></td>");
            }else{
                echo ("<td></td>");
            }
        }
    }?>
                </tr>
              </tbody>
            </table>

        </div>

    <? if ($ris==1){ ?>

        <div id="data-box" class="one-third" style="display:none;">
          <div id="left-box" class="inner-box white">
              <h3 id='left-box-num'>Prenotazione № </h3>
              <p id='left-box-nome'><b>Nome Cliente</b>:</p>
              <p id='left-box-tel'><b>№ Telefono</b>:</p>
              <p id='left-box-arrivo'><b>Data Arrivo</b>:</p>
              <p id='left-box-durata'><b>Durata</b>:</p>
              <p id='left-box-posti'><b>Posti prenotati</b>:</p>
              <p id='left-box-resp'><b>Responsabile</b>:</p>
              <button type="button" class="btn btn-success" onclick="javascript:modifyBooking()">Modifica Dati</button>
              <button type="button" class="btn btn-danger" onclick="javascript:deleteBooking()">Elimina Prenotazione</button>
          </div>
        </div>

    <? } ?>



        <div id="new-booking" class="float-center">
          <div id="form-box" class="inner-box white">
              <div class="center">
                <h2>Nuova Prenotazione</h2>
              </div>
              <form id='form'>
                  <input type="text" name="nome" placeholder="Nome Cliente">
                  <input type="text" name="telefono" placeholder="№ Telefono">
                  <input type="text" name="arrivo" placeholder="Data Arrivo" id="datepick">
                  <input type="text" name="durata" placeholder="Durata (notti)">
                  <input type="text" name="posti" placeholder="Posti prenotati">
                  <input type="text" name="responsabile" placeholder="Responsabile">
                  <button type="button" class="btn btn-success" onclick="javascript:submitBooking()">Salva</button>
                  <button type="button" class="btn btn-danger" onclick="javascript:hideBooking()">Annulla</button>
              </form>
          </div>
        </div>


        <footer>
          <div class="footer-div">
            <? if ($ris == 1){ ?>
            <a href="javascript:makeBooking()" class="btn btn-primary">Nuova Prenotazione</a>
            <a href="main.php?ris=0" class="btn btn-warning">Logout</a>
            <? }else{ ?>
            <a href="main.php?ris=1" class="btn btn-success">Area Riservata</a>
            <? } ?>
          </div>
        </footer>

      </div>

        <link href="static/css/datepickr.css" rel="stylesheet">
        <script type="text/javascript" src="static/javascript/datepickr.min.js"></script>
        <script type="text/javascript">
            new datepickr('datepick', {
                'dateFormat': 'd/m/Y'
            });
        </script>

  </body>
</html>
