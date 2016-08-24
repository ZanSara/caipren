
function enableEditing(fillme){
    $('#newB_Modal input').prop('readonly', false);
    $('#newB_Modal textarea').prop('readonly', false);
    $('#new-btn').prop('disabled', false);
    $('#new-btn').show();
    $('#bottom-close-btn').show();
    $('#enable-btn').hide();
    $('#top-close-btn').hide();
    $('#modnew').prop('checked', 'checked');
    $('#modgest').prop('disabled', '');

    if (fillme){
        $('#del-btn').show();
        $('#del-btn').prop('disabled', false);
        $('#new-btn').prop('disabled', false);
        $('#modnew').prop('checked', '');
    }
}


function prepareDelete(){
    if (confirm('Sei sicuro di voler cancellare questa prenotazione?')) {
        $('#moddel').prop('checked', 'checked');
        $('#modnew').prop('checked', '');
        $('#booking-form').submit();
    }
}

function openNewBModal(fillme, prenid, gestione){

    $('#newB_Modal').modal('show');

    if(fillme) {

        $('.modal-dataTitle').hide();
        $('#enable-btn').hide();
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
                $('#modnome').val(decoded.nome);
                $('#modtel').val(decoded.tel);
                $('#modprov').val(decoded.provincia);
                $('#modarrivo').val(decoded.arrivo);
                $('#moddurata').val(decoded.durata);
                $('#modposti').val(decoded.posti);
                $('#modresp').val(decoded.resp);
                $('#modnote').val(decoded.note);
                $('#modprenid').val(decoded.prenid);
                $('#modnew').prop('checked', gestione );
                $('#modgest').prop('disabled', 'disabled');
                if(gestione){
                    $('#line-posti').hide();
                    $('#modgest').prop('checked', 'checked');
                }
                $('#modnew').prop('checked', '' );

                $('#enable-btn').attr("href", "javascript:enableEditing("+fillme+", "+gestione+");");
                $('#enable-btn').show();

        }).fail(function() {
            renderError("[$.get().fail() message]", "[$.get().fail() message]")
        });

    }else{

        $('#loadingNB').hide();
        $('#loadingTitle').hide();
        $('.modal-dataTitle').text('Nuova Prenotazione');
        $('.modal-databox').show();
        $('.modal-footer').show();
        $('#modnew').prop('checked', 'checked' );

        enableEditing(0, gestione);
    }

}


function renderError(Exception, gotData){
    $('.loading').hide();
    $('.loadingTitle').hide();
    $(".modal-dataTitle").show();
    $(".modal-errfooter").show();
    $('#error-alert').show();
    $('#error-alert').html('<h4>ERRORE INTERNO.</h4><p>Contatta il webmaster (Codice RE).<p>');// + gotData); // + " ## " + Exception);
    $('.modal-dataTitle').text("ERRORE");
}

function renderFindError(Exception, gotData){
    $('.loading').hide();
    $('.loadingTitle').hide();
    $(".modal-dataTitle").show();
    $(".modal-errfooter").show();
    $('#finderror-alert').show();
    $('#finderror-alert').html('<h4>ERRORE INTERNO.</h4><p>Contatta il webmaster (Codice RE).<p>');// + gotData); // + " ## " + Exception);
    $('.modal-dataTitle').text("ERRORE");
}



function toggleGestione(){
    if($('.mod-gest').prop('readonly') == false){
        $('#line-posti').toggle();
        
        var text = $('.modal-dataTitle').text(function(index,text){
            if($('.mod-gest').prop('checked') == true){
                return text.replace("Prenotazione", "Gestione");
            }
            return text.replace("Gestione", "Prenotazione");
        });
        $('.modal-dataTitle').text(text.text() );
    }
}


function findBooking(){

    $('.modal-dataTitle').hide();
    $('.modal-databox').hide();
    $('.modal-footer').hide();
    $('.loading').show();
    $('.loadingTitle').show();
    $('.message').hide();
    $('.message').text('');
    
    // Non va...?
    $('td[style*="border:4px solid black"]').css("border","1px solid white");
        
    $('.loading').show();
    $.get('find.php', {
            prenid: $('#cercaid').val(),
            nome : $('#cercanome').val(),
            tel : $('#cercatel').val(),
            arrivo : $('#cercaarrivo').val(),
            prov : $('#cercaprov').val(),
            durata : $('#cercadurata').val(),
            posti : $('#cercaposti').val(),
            resp : $('#cercaresp').val(),
            note : $('#cercanote').val(),
        }).done(function(gotData) {
            try{
                var decoded = JSON.parse(gotData);
            }catch (Exception) {
                renderFindError(Exception, gotData);
                return;
            };
            $('.loading').hide();
            $('.loadingTitle').hide();
            
            $('#findresults').append(
                "<tr>"+
                   "<th>Numero</th>"+
                   "<th>Nome</th>"+
                   "<th>Telefono</th>"+
                   "<th>Arrivo</th>"+
                   "<th>Durata</th>"+
                   "<th>Posti</th>"+
                   "<th></th>"+
                "<tr>"
                );
            
            nresults = 0;
            $.each( decoded, function( key, value ) {
                nresults++;
                $('#findresults').append(
                "<tr>"+
                   "<td>"+value.id+"</td>"+
                   "<td>"+value.nome+"</td>"+
                   "<td>"+value.tel+"</td>"+
                   "<td>"+value.giorno_inizio+"</td>"+
                   "<td>"+value.durata+"</td>"+
                   "<td>"+value.posti+"</td>"+
                   "<td> <button id='btn-mostra-"+value.id+"' class='btn btn-success' >Mostra</button> </td>"+
                "<tr>"
                );
                $("#btn-mostra-" + value.id).click(function(){
                    // Rimuove un eventuale 0 iniziale
                    if(value.giorno_inizio.slice(0, 1) == "0"){
                        anchor = value.giorno_inizio.slice(1, -5);
                    } else {
                        anchor = value.giorno_inizio.slice(0, -5);
                    } 
                    // Scrolla alla linea giusta
                    var row = document.getElementById(anchor);
                    row.scrollIntoView(true);
                    // Mette il bordo
                    $(".P"+value.id).css("border", "4px solid black");
                    // chiude il modal
	                  $('#Find_Modal').modal('hide');
                });
                
            });
            $('#nresults').text(nresults);

            $('.modal-dataTitle').show();
            $('.modal-resultbox').show();
            $('.modal-footer').show();
            $('#find-btn').hide();
            
    }).fail(function() {
        renderError("[$.get().fail() message]", "[$.get().fail() message]")
    });


}

