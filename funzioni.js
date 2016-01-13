
function getData(pid){
            $('#data-box').css("display", "show");
            $("#left-box-title").text("Loading...");
            $("#left-box-nome").text('');
            $("#left-box-tel").text('');
            $("#left-box-arrivo").text('');
            $("#left-box-durata").text('');
            $("#left-box-posti").text('');
            $("#left-box-resp").text('');
            $.get('/cai/dati.php', {
                gestione : 0,
                prenid: pid
            }).done(function(gotData) {
                var data = JSON.parse(gotData)
                $("#left-box-num").html('Prenotazione № ' + data.prenid);
                $("#left-box-nome").html('<b>Nome Cliente</b>: ' + data.nome);
                $("#left-box-tel").html('<b>№ Telefono</b>: ' + data.tel);
                $("#left-box-arrivo").html('<b>Data arrivo</b>: ' + data.arrivo);
                $("#left-box-durata").html('<b>Durata</b>: ' + data.durata);
                $("#left-box-posti").html('<b>Posti prenotati</b>: ' + data.posti);
                $("#left-box-resp").html('<b>Responsabile</b>: ' + data.resp);
            }).fail(function() {
                alert('ERRORE! Il server non ha restituito i dati della prenotazione.');
                $("#left-box").text("ERRORE");
            });
        };

        function getGestore(gid){
            $("#left-box-title").text("Loading...");
            $("#left-box-nome").text('');
            $("#left-box-tel").text('');
            $("#left-box-arrivo").text('');
            $("#left-box-durata").text('');
            $("#left-box-posti").text('');
            $("#left-box-resp").text('');
            $.get('/cai/dati.php', {
                gestione : 1,
                prenid: gid
            }).done(function(gotData) {
                var data = JSON.parse(gotData)
                $("#left-box-num").html('Gestione № ' + data.prenid);
                $("#left-box-nome").html('<b>Nome Gestore</b>: ' + data.nome);
                $("#left-box-tel").html('<b>№ Telefono</b>: ' + data.tel);
                $("#left-box-arrivo").html('<b>Data arrivo</b>: ' + data.arrivo);
                $("#left-box-durata").html('<b>Durata gestione</b>: ' + data.durata);
                $("#left-box-posti").html('<b>Posti occupati</b>: ' + data.posti);
            }).fail(function() {
                alert('ERRORE! Il server non ha restituito i dati della prenotazione.');
                $("#left-box").text("ERRORE");
            });
        };

        function makeBooking(){
            var div = document.createElement("div");
            div.style.width = "100px";
            div.style.height = "100px";
            div.style.position = "absolute";
            div.style.right = "40%";
            div.style.zIndex = "50";
            div.style.background = "red";
            div.style.color = "white";
            div.innerHTML = "Hello";
            document.body.appendChild(div);
            var child = document.createElement("div");
            child.style.width = "50px";
            child.style.height = "50px";
            child.style.background = "green";
            div.appendChild(child);

            }
