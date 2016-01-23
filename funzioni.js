
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
    $("#left-box-num").text("");
    $("#loading").show()
    $("#left-box-nome").text('');
    $("#left-box-tel").text('');
    $("#left-box-arrivo").text('');
    $("#left-box-durata").text('');
    $("#left-box-posti").text('');
    $("#left-box-resp").text('');
    $("#modify-btn").hide()
    $("#delete-btn").hide()
    $("#err-btn").hide();
    showBox();
    $.get('dati.php', {
        gestione : gest,
        prenid: pid
    }).done(function(gotData) {
        try{
            var data = JSON.parse(gotData);
        }catch (Exception) {
            alert('ERRORE! Il server non ha restituito i dati della prenotazione. Contatta il webmaster.' + gotData);
            $('#loading').hide();
            $("#left-box-num").text("ERRORE");
            $("#err-btn").show();
        };
        $('#loading').hide();
        if (gest == 1){
            $("#left-box-num").html('Gestione № ' + data.prenid);
            $("#left-box-nome").html('<b>Nome Gestore</b>: ' + data.nome);
            $("#left-box-durata").html('<b>Durata pernottamento</b>: ' + data.durata);
        }else{
            $("#left-box-num").html('Prenotazione № ' + data.prenid);
            $("#left-box-nome").html('<b>Nome Cliente</b>: ' + data.nome);
            $("#left-box-durata").html('<b>Durata soggiorno</b>: ' + data.durata);
            $("#left-box-resp").html('<b>Responsabile</b>: ' + data.resp);
        }
        $("#left-box-tel").html('<b>№ Telefono</b>: ' + data.tel);
        $("#left-box-arrivo").html('<b>Data arrivo</b>: ' + data.arrivo);
        $("#left-box-posti").html('<b>Posti prenotati</b>: ' + data.posti);
        $("#err-btn").hide();
        $("#modify-btn").show()
        $("#delete-btn").show()

    }).fail(function(gotData) {
        alert('ATTENZIONE! Il server non ha restituito i dati della prenotazione. Contatta il webmaster.');
        $('#loading').hide();
        $("#left-box-num").text("ERRORE");
        $("#err-btn").show();
    });
};



function makeBooking(){
    //var button = $(event.relatedTarget) // Button that triggered the modal
    //var recipient = button.data('whatever') // Extract info from data-* attributes
    //   If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    //   Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    //modal.find('.modal-title').text('New message to ' + recipient)
    var content = modal.find('.modal-body input').text()
    alert(content);
}
