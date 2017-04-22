    <!-- FIND MODAL -->
    <div class="modal fade" id="Find_Modal" tabindex="-1" role="dialog" aria-labelledby="Find_ModalLabel" data-fillme=0, data-prenid=0, data-gestione=0>
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header center">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h2 class="loadingTitle modal-title" style='display:none;'>Caricamento...</h2>
            <h2 id="find-dataTitle" class="modal-dataTitle modal-title">Cerca</h2>
          </div>

          <div class="modal-body">
                      
          <!-- Find Interface -->  
          <!--form id='find-form' class='form-horizontal' method='GET'-->
            <div id="findwarning" class="alert alert-warning" role="alert" style="padding-left:10%;padding-right:10%;">
              <p>Attenzione! Se non compili nessun campo, ti verranno mostrate tutte le prenotazioni di questa stagione.</p>
            </div>
            <div class="modal-databox" >
              <div class="row" >
                <label class="col-sm-3 control-label">Numero Pren.</label>
                <div class="col-sm-9" >
                    <input id="cercaid" type="text" class="mod-prenid form-control" name="prenid" placeholder="Numero Prenotazione">
                </div>
              </div>
              <div class="row" >
                <label class="col-sm-3 control-label">Nome Cliente</label>
                <div class="col-sm-9" >
                    <input id="cercanome" type="text" class="mod-nome form-control" name="nome" placeholder="Nome Cliente">
                </div>
              </div>
              <div class="row">
                <label class="col-sm-3 control-label">№ Telefono</label>
                <div class="col-sm-9" >
                    <input id="cercatel" type="text" class="mod-tel form-control" name="telefono" placeholder="№ Telefono">
                </div>
              </div>
              <div class="row">
                <label class="col-sm-3 control-label">Data di Arrivo</label>
                <div class="col-sm-9" >
                    <input id="cercaarrivo" type="text" class="mod-arrivo form-control" name="arrivo" placeholder="Giorno-Mese-Anno">
                </div>
              </div>
              
              <div class="center" style="padding: 15px;">
              <a href=# class="btn btn-default" onclick="javascript:$('#findothers').toggle();">Parametri Avanzati</a>
              </div>
                
              <div id="findothers" style='display:none;'>
                  <div class="row">
                    <label class="col-sm-3 control-label">Provincia</label>
                    <div class="col-sm-9" >
                        <input id="cercaprov" type="text" class="mod-prov form-control" name="provincia" placeholder="Codice Provincia">
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-sm-3 control-label">Durata Soggiorno</label>
                    <div class="col-sm-9" >
                        <input id="cercadurata" type="text" class="mod-durata form-control" name="durata" placeholder="Durata del Soggiorno">
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-sm-3 control-label">Posti Prenotati</label>
                    <div class="col-sm-9" >
                        <input id="cercaposti" type="text" class="mod-posti form-control" name="posti" placeholder="Posti Prenotati">
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-sm-3 control-label">Responsabile</label>
                    <div class="col-sm-9" >
                        <input id="cercaresp" type="text" class="mod-resp form-control" name="responsabile" placeholder="Responsabile Prenotazione">
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-sm-3 control-label">Note</label>
                    <div class="col-sm-9" >
                        <textarea id="cercanote" type="textarea" rows="3" class="mod-note form-control" name="note" placeholder="Note..."></textarea>
                    </div>
                  </div>
                </div>
              
                
              </div> <!-- modal-databox -->
              
              <img class="loading" src="static/images/spinningwheel.gif" style='width:40%; margin-left:30%;margin-right:30%; display:none;' />
              <div id="finderror-alert" class="alert alert-danger" role="alert" style='display:none; text-align:center;'></div>
              <div id="findmessage-alert" class="alert alert-danger" role="alert" style='display:none; padding-left:10%;padding-right:10%;'></div>


              <!-- Results Interface -->
              <div class="modal-resultbox center" style='display:none;'>
                  <p>Ho trovato <b><span id="nresults"></span></b> prenotazioni che corrispondono ai tuoi criteri di ricerca.<p>
                  
                  <table id="findresults" class="table table-hover center">
                  </table>
              </div>

              <div class="modal-footer center">
                <button id="find-btn" class="btn btn-primary" onclick="javascript:findBooking();">Cerca</button>
                <button class="btn btn-default" data-dismiss="modal" >Chiudi</button>
              </div>
              <div class="modal-errfooter center" style='display:none;'>
                <button class="btn btn-danger" data-dismiss="modal">Chiudi</button>
              </div>

          <!--/form-->
          </div>
          
        </div>
      </div>
    </div>
