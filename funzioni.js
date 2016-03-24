
function validate_and_send(prenid, gestione){

    var form = $('#booking-form');
    var alertbox = $('#message-alert');
    var haderror = false

    //alert($('.mod-nome').val());
    //alert($('.mod-nome').value);
    //alert($('.mod-nome').prop('value'));

    if( $('.mod-nome').val() == undefined ){
        haderror = true;
        alertbox.text("Inserire il nome del cliente.")
    }
    else if( $('.mod-nome').val().lenght > 100 ){
        haderror = true;
        alertbox.text("Inserire solo il nome del cliente nella prima riga (massimo 100 caratteri).");
    }
    else if( $('.mod-tel').val() == undefined ){
        haderror = true;
        alertbox.text("Inserire un numero di telefono.");
    }
    else if( $('.mod-tel').val().lenght > 15 ){
        haderror = true;
        alertbox.text("Inserire un numero di telefono valido.");
    }
    // Controllare che il numero di telefono sia composto di sole cifre!!
    else if( $('.mod-durata').val() < 1 || $('.mod-durata').val() > 122 ){
        haderror = true;
        alertbox.text("Inserire una durata del pernottamento valida.");
    }
    else if( $('.mod-arrivo').val() == undefined ){
        haderror = true;
        alertbox.text("Inserire una data di arrivo.");
    }else if( $('.mod-posti').val() < 1 || $('.mod-posti').val() > 16 ){
        haderror = true;
        alertbox.text("Inserire un numero di posti prenotati valido. Attenzione: sono disponibili al massimo 16 posti letto.");
    }
    else if( $('.mod-note').val() != undefined ){
        if( $('.mod-note').val().lenght > 1000 ){
            haderror = true;
            alertbox.text("Attenzione! La nota è troppo lunga (massimo 1000 caratteri)");
        }
    }
    else if( $('.mod-resp').val() == undefined && $('.mod-gest').prop('checked') != 'checked' ){
        haderror = true;
        alertbox.text("Inserisci il tuo nome nel campo 'Responsabile della Prenotazione'.");
    }


    if( haderror ){
        alertbox.show();
        haderror = false;

    }else{
        form.submit();
        $('#newB_Modal').modal('hide');
    }
}


function switch2NewBModal(fillme, prenid, gestione){
    $('#LeftBox_Modal').modal('hide');
    openNewBModal(fillme, prenid, gestione);
}


function openNewBModal(fillme, prenid, gestione){
    var modal = $('#newB_Modal');
    var title = $('#newB_ModalTitle').text();

    if(gestione == 1){
         $('#newB_ModalTitle').text('Gestione');
    }
    modal.modal('show');

    if(fillme) {
        getData(gestione, prenid);
    }else{
        $('#newB_ModalTitle').text('Nuova Prenotazione');
        $('.modal-databox').show();
        $('.modal-footer').show();
        $('#loadingNB').hide();
        $('#loadingTitle').hide();
        $('#message').hide();
        $('#message').text('');
        $('.mod-new').prop('checked', 'checked' );
    }

    //$('#new-btn').attr('onclick', 'javascript:validate_and_send('+prenid+', '+gestione+')');


}

function prepareLeftModal(prenid, gestione){
    $('#modify-btn').attr('onclick', 'javascript:switch2NewBModal(1, '+prenid+', '+gestione+')');
    $('#delete-btn').attr('onclick', 'javascript:deleteBooking('+prenid+')');

    $('#LeftBox_Modal').modal('show');

    getData(gestione, prenid);

}


function getData(gestione, prenid){

    // Hide everything
    $('.modal-dataTitle').text('Prenotazione');
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
                $('.loading').hide();
                $('.loadingTitle').hide();
                $('.message').show();
                $('.message').html('<h5>ERRORE INTERNO.</h5><p>Contatta il webmaster.<p>' + gotData);
                $('.modal-dataTitle').text("ERRORE");
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
            $('.mod-nome').text(decoded.nome).val(decoded.nome);
            $('.mod-tel').text(decoded.tel).val(decoded.tel);
            $('.mod-arrivo').text(decoded.arrivo).val(decoded.arrivo);
            $('.mod-durata').text(decoded.durata).val(decoded.durata);
            $('.mod-posti').text(decoded.posti).val(decoded.posti);
            $('.mod-resp').text(decoded.resp).val(decoded.resp);
            $('.mod-note').text(decoded.note).val(decoded.note);
            $('.mod-prenid').text(decoded.prenid).val(decoded.prenid);
            $('.mod-new').prop('checked', gestione );
            if(gestione){
                //alert('qui!');
                $('.mod-gest').prop('checked', 'checked');
            }
            $('.mod-new').prop('checked', '' );

            $('#modify-btn').attr('data-id', decoded.prenid);

    });
}
