
// AJAX retrieving booking data for newBookingModal
function getBookingData(prenid, gestione){

    var decoded = 0;
    $.get('services/dati.php', {
            prenid: prenid,
            gestione : gestione
        }).done(function(gotData) {
            
            try{
                var decoded = JSON.parse(gotData);
            }catch (Exception) {
                renderError(Exception, gotData);
                return;
            };
            
            renderDecodedData(decoded);

    }).fail(function() {
        renderError("Errore AJAX", "Errore AJAX", "Errore AJAX")
    });

}




// FIX ME

function findBooking(){

    $('#find-dataTitle').hide();
    $('#findwarning').hide();
    $('.modal-databox').hide();
    $('.modal-footer').hide();
    $('.loading').show();
    $('.loadingTitle').show();
    $('.message').hide();
    $('.message').text('');
    
    // Non va...?
    //$('td[style*="border:4px solid white"]').css("border","1px solid white");
        
    $('.loading').show();
    $.get('services/find.php', {
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
                    $(".P"+value.id).css("border", "4px solid yellow");
                    // chiude il modal
	                  $('#Find_Modal').modal('hide');
                });
                
            });
            $('#nresults').text(nresults);
            
            
            if(nresults==0){
                $('#findresults').hide();
            } else {
                $('#findresults').show();
            }
            
            $('#find-dataTitle').text("Risultati");
            $('#find-dataTitle').show();
            $('.modal-resultbox').show();
            $('.modal-footer').show();
            $('#find-btn').hide();
            
    }).fail(function() {
        renderError("[$.get().fail() message]", "[$.get().fail() message]")
    });


}
