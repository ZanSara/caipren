    <!-- NEW BOOKING MODAL -->
    <div class="modal fade" id="newBookingModal" tabindex="-1" role="dialog" aria-labelledby="newBookingModalLabel" data-fillme=0, data-prenid=0, data-gestione=0>
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header center-text">
            <!-- modal titles -->
            <h2 id="nBM-dataTitle" class="modal-dataTitle modal-title">Prenotazione</h2>
            <h2 id="nBM-loadingTitle" class="modal-title" style='display:none;'>Caricamento...</h2>
            <h2 id="nBM-errorTitle" class="modal-title" style='display:none;'>Errore!</h2>
            <!-- top buttons -->
            <a id="nBM-ModificaBtn" class="btn btn-warning" href="javascript:nBM_enableEditing();">Modifica</a>
            <button id="nBM-ChiudiTopBtn" class="btn btn-default" data-dismiss="modal">Chiudi</button>

          </div>

          <form class='form-horizontal' method='POST'>
            <div class="modal-body">
              
              <!-- main form -->
              <div id="nBM-mainForm" class="modal-databox" >
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9 checkbox">
                      <label>
                        <input id="nBM-gestioneChk" name='gestione' type="checkbox" onclick="javascript:toggleGestione();"> Sono gestori
                      </label>
                    </div>
                </div>
                <hr />
                <div class="form-group" >
                  <label class="col-sm-3 control-label">Nome Cliente</label>
                  <div class="col-sm-9" >
                      <input id="nBM-nome" type="text" class="form-control" name="nome" placeholder="Nome Cliente" readonly="readonly"
                      data-rule-required="true" data-msg-required="Inserire il nome del cliente">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">№ Telefono</label>
                  <div class="col-sm-9" >
                      <input id="nBM-tel" type="text" class="form-control" name="telefono" placeholder="№ Telefono" readonly="readonly"
                      data-rule-required="true" data-msg-required="Inserire un numero di telefono"
                      data-rule-customPhone="true">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Provincia di Residenza</label>
                  <div class="col-sm-9" >
                      <input id="nBM-provincia" type="text" class="form-control" name="provincia" placeholder="Codice Provincia" readonly="readonly">
                      <!-- data-rule-required="true" data-msg-required="Inserire la provincia di residenza" -- RIMOSSO NELLA VERSIONE 2.3 -->
                  </div>
                </div>
                <hr/>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Data di Arrivo</label>
                  <div class="col-sm-9">
                      <label id="nBM-arrivoLbl" class="control-label"><i>inserisci una data</i></label>
                      <input id="nBM-arrivo" class="form-control hidden" type="text" name="arrivo" readonly="readonly"
                      data-rule-required="true" data-msg-required="Inserire una data di arrivo"
                      data-rule-customData="true">
                 </div>
                 <div id="nBM-arrivoBtn" class="col-sm-9 pull-right" style="padding:0;" style="display:hidden;">
                      <div class="col-sm-9" style="padding-right:2%; padding-top:2%;">
                        <?
                            $day = 0;
                            for($row = 0; $row < 4 ;$row++){
                                
                                echo '<div class="btn-group btn-group-justified" role="group" aria-label="...">';
                                for($num = 0; $num < 8 ;$num++){
                                    $day++;
                                    echo '<a href="#" id="btnDay-'.$day.'" class="btn btn-default" role="button" onclick="javascript:setDay('.$day.')">'.$day.'</a>';
                                }
                                echo "</div>";
                            }
                        ?>                     
                      </div>
                      <div class="col-sm-3" style="padding-left:2%; padding-top:2%;">
                        <div class="btn-group btn-group-vertical" role="group" aria-label="...">
                          <a href="#" id="btnMonth-Giu" class="btn btn-default" role="button" onclick="javascript:setMonth('6')">Giugno</a>
                          <a href="#" id="btnMonth-Lug" class="btn btn-default" role="button" onclick="javascript:setMonth('7')">Luglio</a>
                          <a href="#" id="btnMonth-Ago" class="btn btn-default" role="button" onclick="javascript:setMonth('8')">Agosto</a>
                          <a href="#" id="btnMonth-Set" class="btn btn-default" role="button" onclick="javascript:setMonth('9')">Settembre</a>
                        </div>
                      </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-3 control-label">Durata del Soggiorno</label>
                  <div class="col-sm-9" >
                    <div class="input-group">
                      <input id="nBM-durata" type="text" class="form-control" name="durata" placeholder="Durata del Soggiorno" readonly="readonly"
                      data-rule-required="true" data-msg-required="Inserire la durata del soggiorno"
                      data-rule-digits="true" data-msg-digits="Inserire una durata del soggiorno valida"
                      data-rule-customDurata="true">
                      <span class="input-group-addon">giorni</span>
                    </div>
                  </div>
                </div>
                <div class="form-group" id="nBM-linePosti">
                  <label class="col-sm-3 control-label">Posti Prenotati</label>
                  <div class="col-sm-9" >
                      <label id="nBM-postiLbl" class="control-label"><i>Inserisci il numero di posti</i></label>
                      <input id="nBM-posti" type="text" class="form-control hidden" name="posti" placeholder="Posti Prenotati" readonly="readonly"
                      data-rule="true" data-msg-required="Inserire il numero di posti letto prenotati"
                      data-rule-digits="true" data-msg-digits="Inserire un numero di posti letto valido"
                      data-rule-customPosti="true">
                      <div id="nBM-postiBtn" style="display:hidden; padding-top:2%;" >
                        <?
                            $bed = 0;
                            for($row = 0; $row < 2 ;$row++){
                                
                                echo '<div class="btn-group btn-group-justified" role="group" aria-label="...">';
                                for($num = 0; $num < 8 ;$num++){
                                    $bed++;
                                    echo '<a href="#" id="btnPosti-'.$bed.'" class="btn btn-default" role="button" onclick="javascript:setPosti('.$bed.')">'.$bed.'</a>';
                                }
                                echo "</div>";
                            }
                        ?>
                      </div>
                  </div>
                </div>
                
                <hr/>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Responsabile Prenotazione</label>
                  <div class="col-sm-9" >
                      <input id="nBM-resp" type="text" class="form-control" name="responsabile" placeholder="Responsabile Prenotazione" readonly="readonly"
                      data-rule-required="true" data-msg-required='Inserisci il tuo nome nel campo "Responsabile della prenotazione"'>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Note</label>
                  <div class="col-sm-9" >
                      <textarea id="nBM-note" type="textarea" rows="3" class="form-control" name="note" placeholder="Note..." readonly="readonly"></textarea>
                  </div>
                </div>
                <div class="hidden checkbox">
                  <input id="nBM-newChk" name='newbooking' type="checkbox" checked="checked" readonly="readonly">
                </div>
                <div class="hidden checkbox">
                  <input id="nBM-delChk" name='delbooking' type="checkbox" readonly="readonly">
                </div>
                <div class="hidden">
                  <input id="nBM-prenid" name='prenid' type="text" readonly="readonly">
                </div>
              </div> <!-- modal-databox -->
              
              
              <!-- Loading box -->
              <img id="nBM-spinningWheel" class="spinningWheel" src="static/images/spinningwheel.gif"style='display:none;' />
              
              <!-- Error reporting elements-->
              <div id="nBM-errorBox" style='display:none;'>
                <div id="nBM-errorAlert" class="alert alert-danger center-text" role="alert" ></div>
              </div>
              
              <!-- Footer -->
              <div class="modal-footer center-text">
                <input id="nBM-SalvaBtn" class="btn btn-primary" disabled="disabled" type="submit" value="Salva" style="display:none;">
                <a id="nBM-EliminaBtn" class="btn btn-danger" href="javascript:prepareDelete()" style="display:none;">Elimina</a>

                <!-- This one should be always visible - thus no ID -->
                <button class="btn btn-default" data-dismiss="modal">Chiudi</button>
              </div>
              

            </div>
          </form>
        </div>
      </div>
    </div>
