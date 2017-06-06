  <div class="banner">
      <a href="http://www.caisovico.it">
        <img src="../foto/pagebanner.jpg">
      </a>
      <h2 style='display:inline-block;'>Prenotazioni Stagione <? echo $this->Year ?></h2>
      
      <a href="http://www.caisovico.it/rifugio/rifugio.html">
        <img src="../foto/freccia_blu_back.jpg" style='display:inline;' class='pull-right' >
      </a>
    </div>

    <div class="mobile-title">
        <h3>Prenotazioni <? echo $this->Year ?></h3>
        <a href="http://www.caisovico.it/rifugio/rifugio.html">
          <img class='mobile-back' src='static/images/nav-home-green.png'>
        </a>
        <img class='mobile-login' src='static/images/nav-settings-green.gif' onclick="javascript:$('#mobile-dropdown').toggle();">
    </div>
    <div id='mobile-dropdown' class="shadow3">
      <ul>
        <a onclick='javascript:openNewBModal(0, 0, 0)'><li class="btn btn-success">Nuova Prenotazione</li></a>
        <a data-toggle="modal" data-target="#Find_Modal" onclick="$('#mobile-dropdown').toggle();"><li class="btn btn-primary">Cerca</li></a>
        <a data-toggle="modal" data-target="#Adv_Modal" onclick="$('#mobile-dropdown').toggle();"><li class="btn btn-info">Avanzate</li></a>
        <a data-toggle="modal" data-target="#About_Modal" onclick="$('#mobile-dropdown').toggle();"><li class="btn btn-warning">About</li></a>
        <a href="../prenotazioni/" ><li class="btn btn-danger">Logout</li></a>
      </ul>
    </div>
