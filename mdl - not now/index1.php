<!doctype html>
<!--
  Material Design Lite
  Copyright 2015 Google Inc. All rights reserved.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

      https://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License
-->
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Pagina di gestione delle prenotazioni del Rifugio Del Grande Camerini.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prenotazioni - Rifugio M. Del Grande R. Camerini</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Prenotazioni">
    <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="images/favicon.png">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <link href="hhttps://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
    <link href="hhttps://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link rel="stylesheet" href="material.min.css">
    <link rel="stylesheet" href="styles.css">
    <!--style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }
    </style-->
  </head>
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
  <header class="mdl-layout__header">
    <div class="mdl-layout__header-row">
      <!-- Title -->
      <span class="mdl-layout-title">Rifugio!</span>

    </div>
  </header>
  <main class="mdl-layout__content">
    <div class="page-content">
          <? $ris = 1 ?>
      <div id="calendar-box">
        <table id="tableid" class="calendar mdl-data-table  mdl-shadow--2dp">
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
                        echo("<th class='gestore' style='border: 1px solid #aaa;'>Gestore</th>");
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
                echo("<a id='".$absday."-".$i."' href='javascript:getData(".$calendario[$absday][$i][0].", 0)' onblur='javascript:hideBox()' ><div>");
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


        <footer class="mdl-mini-footer">
          <div class="mdl-mini-footer__left-section">
            <div class="mdl-logo">CAI Sovico - Dati del CAI</div>
          </div>
        </footer>

      </div>
  </main>
</div>
    <script src="../../material.min.js"></script>
  </body>
</html>
