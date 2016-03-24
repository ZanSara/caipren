
function enableEditing(fillme){
    $('input').prop('readonly', false);
    $('#new-btn').prop('disabled', false);
    $('#enable-btn').hide();
    
    if(fillme){
        $('#del-btn').show();
        $('#del-btn').prop('disabled', false);
    }
}


function openNewBModal(fillme, prenid, gestione){
    
    $('#newB_Modal').modal('show');

    if(fillme) {

        $('.modal-dataTitle').hide();
        $('.modal-databox').hide();
        $('.modal-footer').hide();
        $('.loading').show();
        $('.loadingTitle').show();
        $('.message').hide();
        $('.message').text('');

        var decoded = 0;
        $('.loading').show();
        $.get('dati.php', {
                gestione : gestione,
                prenid: prenid
            }).done(function(gotData) {
                try{
                    var decoded = JSON.parse(gotData);
                }catch (Exception) {
                    renderError(Exception, gotData);
                    return;
                };
                $('.loading').hide();
                $('.loadingTitle').hide();
                if(gestione == 1) {
                    $('.modal-dataTitle').text('Gestione');
                }else{
                    $('.modal-dataTitle').text('Prenotazione');
                }

                $(".modal-dataTitle").append(' № '+ decoded.prenid);
                $('.modal-dataTitle').show();
                $('.modal-databox').show();
                $('.modal-footer').show();
                $('.mod-nome').val(decoded.nome);
                $('.mod-tel').val(decoded.tel);
                $('.mod-arrivo').val(decoded.arrivo);
                $('.mod-durata').val(decoded.durata);
                $('.mod-posti').val(decoded.posti);
                $('.mod-resp').val(decoded.resp);
                $('.mod-note').val(decoded.note);
                $('.mod-prenid').val(decoded.prenid);
                $('.mod-new').prop('checked', gestione );
                if(gestione){
                    //alert('qui!');
                    $('.mod-gest').prop('checked', 'checked');
                }
                $('.mod-new').prop('checked', '' );

                $('#enable-btn').attr("href", "javascript:enableEditing("+fillme+");");

        }).fail(function() {
            renderError("[$.get().fail() message]", "[$.get().fail() message]")
        });
        
    }else{
        
        $('#loadingNB').hide();
        $('#loadingTitle').hide();
        $('.modal-dataTitle').text('Nuova Prenotazione');
        $('.modal-databox').show();
        $('.modal-footer').show();
        $('.mod-new').prop('checked', 'checked' );
        
        enableEditing(0);
    }

    //$('#new-btn').attr('onclick', 'javascript:validate_and_send('+prenid+', '+gestione+')');
}


function renderError(Exception, gotData){
    $('.loading').hide();
    $('.loadingTitle').hide();
    $(".modal-dataTitle").show();
    $(".modal-errfooter").show();
    $('#error-alert').show();
    $('#error-alert').html('<h4>ERRORE INTERNO.</h4><p>Contatta il webmaster (Codice RE).<p>' + gotData); // + " ## " + Exception);
    $('.modal-dataTitle').text("ERRORE");
}

