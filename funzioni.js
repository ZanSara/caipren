
function getData(pid, gest){
        $("#left-box-title").text("Loading...");
        $("#left-box-nome").text('');
        $("#left-box-tel").text('');
        $("#left-box-arrivo").text('');
        $("#left-box-durata").text('');
        $("#left-box-posti").text('');
        $("#left-box-resp").text('');
        $.get('dati.php', {
            gestione : gest,
            prenid: pid
        }).done(function(gotData) {
            showBox();
            var data = JSON.parse(gotData);
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
        }).fail(function() {
            alert('ERRORE! Il server non ha restituito i dati della prenotazione.' + gotData);
            $("#left-box").text("ERRORE");
        });
    };


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
