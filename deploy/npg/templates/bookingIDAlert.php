    <!-- BOOKING ID ALERT -->
    <div class="modal fade" id="Id_Modal" data-open="0" tabindex="-1" role="dialog" aria-labelledby="Id_ModalLabel">
    
    <!-- data-open="<? if($open) {?>1<?}else{?>0<?}?>" -->
    
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header center">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title">Nuova Prenotazione</h3>
          </div>

          <div class="modal-body center">
            <p>La nuova prenotazione è stata registrata.<br>Il numero della prenotazione è</p>
            <h3 id='newId'><? echo($last_prenid); ?></h3>
            <p style="font-size:11px;"><br>RICORDA: i tipi di sistemazione (letto, brandina, bivacco etc...) sono provvisori 
            e la disposizione effettiva dei posti letto verrà concordata con i gestori una volta giunti al Rifugio.</p>
          </div>

          <div class="modal-footer center">
              <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
          </div>

        </div>
      </div>
    </div>
