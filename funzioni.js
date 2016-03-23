
function deleteBooking(prenid){
    //$('#left-prenid').val( $('#left-span-num').text() );
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

    getData(gestione, prenid, $('#loadingNB'), $('#loadingNB-title'), $('#messageNB'), $("#newB_ModalTitle"));


    //var button = $(event.relatedTarget);  // Button that triggered the modal
    //var title = button.data('title') + ' Prenotazione';  // Extract info from data-* attributes

    /*
    if(fillme == 1) {
        $('#loadingNB').show();

        var decoded = JSON.parse(
                        $.ajax({
                            dataType: "json",
                            url: 'dati.php?gestione='+ gestione+'&prenid='+prenid,
                            //data: someData,
                            async: false,
                            success: function() {
                                        $('loadingNB').hide()
                                    }
                        }).responseText);

        title = title + ' № ' + prenid;
        $('#mod_prenid').val(modal.data('prenid'));
        $('#mod_nome').val(decoded.nome);
        $('#mod_tel').val(decoded.tel);
        $('#mod_arrivo').val(decoded.arrivo);
        $('#mod_durata').val(decoded.durata);
        $('#mod_posti').val(decoded.posti);
        $('#mod_resp').val(decoded.resp);
        $('#mod_note').val(decoded.note);
        $('#mod_gest').prop('checked', modal.data('gestione'));
        $('#mod_new').prop('checked', false);
    }else{
        title = 'Nuova ' + title;
    }
    $('#newB_ModalTitle').text(title);


    */
}

function prepareLeftModal(prenid, gestione){
    $('#modify-btn').attr('onclick', 'javascript:switch2NewBModal(1, '+prenid+', '+gestione+')');
    $('#delete-btn').attr('onclick', 'javascript:deleteBooking('+prenid+')');

    $('#LeftBox_Modal').modal('show');

    getData(gestione, prenid, $('#loadingL'), $('#loadingL-title'), $('#messageL'), $("#leftmodal-title"));

}


function getData(gestione, prenid, loadingGif, loadingTitle, messageBox, modalTitle, fillingFunction){

    // Hide everything
    $('.modal-dataTitle').text('Prenotazione');

    $('.modal-databox').hide();
    $('.modal-footer').hide();
    $('#loadingsL').show();
    $('#loadingTitle').show();
    $('#message').hide();
    $('#message').text('');

    var decoded = 0;
    loadingGif.show();
    $.get('dati.php', {
            gestione : gestione,
            prenid: prenid
        }).done(function(gotData) {
            try{
                var decoded = JSON.parse(gotData);
            }catch (Exception) {
                loadingGif.hide();
                loadingTitle.hide();
                messageBox.show();
                messageBox.html('<h5>ERRORE INTERNO.</h5><p>Contatta il webmaster.<p>' + gotData);
                modalTitle.text("ERRORE");
                return;
            };
            loadingGif.hide();
            loadingTitle.hide();
            if(gestione == 1) {
                modalTitle.text('Gestione');
            }else{
                modalTitle.text('Prenotazione');
            }

            $(".modal-dataTitle").append(' № '+ decoded.prenid);
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
            $('.mod_gest').prop('checked', modal.data('gestione'));
            $('.mod_new').prop('checked', fillme);

            $('#modify-btn').attr('data-id', decoded.prenid);

    });
}



/*
function showBox(){
    $('#data-box').addClass('data-shown');
    $('#data-box').removeClass('data-hidden');
    $('#calendario-box').addClass('cal-moved');
    $('#calendario-box').removeClass('cal-full');
}

function hideBox(){
    $('#data-box').removeClass('data-shown');
    $('#data-box').addClass('data-hidden');
    $('#calendario-box').removeClass('cal-moved');
    $('#calendario-box').addClass('cal-full');
}


function getData(pid, gest){
    $("#loading").show()
    $("#left-box-title").hide();
    $("#left-box-pn").hide();
    $("#left-box-pt").hide();
    $("#left-box-pa").hide();
    $("#left-box-pd").hide();
    $("#left-box-pp").hide();
    $("#left-box-pr").hide();
    $("#modify-btn").hide()
    $("#delete-btn").hide()
    showBox();
    $.get('dati.php', {
        gestione : gest,
        prenid: pid
    }).done(function(gotData) {
        $('#loading').hide();
        try{
            var data = JSON.parse(gotData);
        }catch (Exception) {
            alert('ERRORE INTERNO. Contatta il webmaster.' + gotData);
            $("#left-box-title").show();
            $("#left-span-bt").text("ERRORE");
            $("#left-span-num").text("");
            return;
        };
        if (gest == 1){
            $("#left-box-title").show();
            $("#left-box-pn").show();
            $("#left-box-pd").show();
            $("#left-span-bt").text('Gestione № ');
            $("#left-span-num").text(data.prenid);
            $("#left-bn").text('Nome Gestore: ');
            $("#left-span-nome").text(data.nome);
            $("#left-span-durata").html(data.durata);
            $("#modify-btn").data('gestione', 1);
        }else{
            $("#left-box-title").show();
            $("#left-box-pn").show();
            $("#left-box-pd").show();
            $("#left-box-pr").show();
            $("#left-span-bt").text('Prenotazione № ');
            $("#left-span-num").text(data.prenid);
            $("#left-bn").html('Nome Cliente: ');
            $("#left-span-nome").text(data.nome);
            $("#left-span-durata").text(data.durata);
            $("#left-span-resp").text(data.resp);
            $("#modify-btn").data('gestione', 0);
        }
        $("#left-box-pt").show();
        $("#left-box-pa").show();
        $("#left-box-pp").show();
        $("#left-span-tel").text(data.tel);
        $("#left-span-arrivo").text(data.arrivo);
        $("#left-span-posti").text(data.posti);
        $("#err-btn").hide();
        $("#modify-btn").show()
        $("#delete-btn").show()
        $("#modify-btn").data('num', data.prenid);

    }).fail(function(gotData) {
        alert('ATTENZIONE! Il server non ha restituito i dati. Contatta il webmaster.');
        $('#loading').hide();
        $("#left-box-*").hide();
        $("#left-box-title").show();
        $("#left-span-bt").text("ERRORE");
        $("#left-span-num").text("");
        $("#err-btn").show();
    });
};


function retrieveData(gest, pid){
    var someData;
    return JSON.parse(
        $.ajax({
            dataType: "json",
            url: 'dati.php?gestione='+gest+'&prenid='+pid,
            data: someData,
            //async: false,  // -------------------> How can I avoid this?
        }).responseText
    )
};
*/
