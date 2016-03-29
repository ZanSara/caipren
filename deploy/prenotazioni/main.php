<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <title>Prenotazioni - CAI Sovico</title>

    <link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="static/css/style.css" rel="stylesheet">

  </head>
  <body>

    <?
    $year = date('Y'); //date('Y', strtotime('01-01-2017'));
    // To test on different year remember to modify this string also in
    // the other php files and inside JS validators!

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
?>

    <div class="title shadow1">

        <h3>Prenotazioni <? echo $year ?></h3>
        <h2>Prenotazioni Stagione <? echo $year ?></h2>
        <h4>Rifugio M. Del Grande - R. Camerini</h4>
        <p>ATTENZIONE: I tipi di sistemazione (letto, brandina, bivacco etc...)
        sono provvisori e la disposizione effettiva dei posti letto verrà
        concordata con i gestori una volta giunti al Rifugio.</p>

        <!-- I'LL PUT BUTTONS HERE WHEN I IMPLEMENT MOBILE VERSION
        <a href="main.php?ris=1" class="btn btn-success">Area Riservata</a>
        -->

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

    $username = "6786_utentesql";
    $password = "databasecai";
    $hostname = "localhost";

    $dbhandle = mysqli_connect($hostname, $username, $password)
        or die("Impossibile connettersi al server. Contatta il webmaster (Codice C2)");
    $selected = mysqli_select_db($dbhandle, "6786_prenotazioni")
        or die("Errore di connessione al server. Contatta il webmaster (Codice D2)");

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
                echo('<b>P '.$pren['id'].'</b>');
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
        <a href="../prenota-gestori/main.php" class="btn btn-success">Area Riservata</a>
      </div>
    </footer>


  </body>
</html>
