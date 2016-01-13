<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <title>Prenotazioni - CAI Sovico</title>

    <link href="app/static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="app/static/javascript/jQuery/jquery-1.11.2.min.js"></script>     <!-- required for Bootstrap-->
    <script src="app/static/bootstrap/js/bootstrap.min.js"></script>
    <script src="funzioni.js"></script>

  </head>
  <body>

    <span class="title">
        <h2>Prenotazioni</h2>
        <h3>Rifugio Del Grande Camerini</h3>
    </span>

    <? $ris = 1?>

    <div class="row">
        <div id="calendar-box" <? if ($ris==1) echo('class="two-third"')  ?> >

            <table id="tableid" class="calendar">
                <colgroup>
                    <col class='giorno'>
                    <? if ($ris==1)
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

        // Building the tr
        echo("<tr id=".$day."-".$month);
        if( $today['absday'] == $absday) echo("id='today' class='border'");
        echo(">");

        // Building the Date td
        echo("<td ");
        if( $day == 1 ) echo ("class='blackline'");
        if( ($firstweekday+$absday-1) % 7 == 6 ) echo("style='color:red;'");
        if( $month == 8 and $day==15 ) echo("style=color:red;");
        echo(">".$day." ".$monthname." / ".$absday);
        echo("</td>");

        // Building Gestore td
        if ($ris == 1){
            echo("<td ");
            if( $day == 1 ) echo ("class='blackline'");
            echo(">");
            echo("<a id='".$absday."-G' href='javascript:getGestore(".$gestioni[$absday].")'>");
            echo($gestioni[$absday]);
            echo("</a>");
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
                if( $day == 1 ){
                    echo ("class='booked blackline'");
                }else{
                    echo ("class='booked'");
                    }
                echo(">");
                echo("<a id='".$absday."-".$i."' href='javascript:getData(".$calendario[$absday][$i][0].")'><div>");
                echo("<b>".$calendario[$absday][$i][0]."</b></div></a></td>");
            }else{
                if( $day == 1 ){
                    echo ("<td class='blackline' ></td>");
                }else{
                    echo ("<td></td>");
                    }
            }
        }
    }?>
                </tr>
              </tbody>
            </table>

        </div>

    <? if ($ris==1){ ?>

        <div id="data-box" class="one-third" style="display:none;">
          <div class="inner-box center">
              <button type="button" class="btn btn-primary" onclick="javascript:makeBooking()">Nuova Prenotazione</button>
          </div>

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

      </div>

  </body>
</html>
