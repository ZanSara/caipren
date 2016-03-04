
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
            async: false,  // -------------------> How can I avoid this?
            success: function(someData) {
                        //console.log(someData);
                        //return someData;
                    }
        }).responseText
    )
};


function deleteBooking(){
    $('#left-prenid').val( $('#left-span-num').text() );
}
