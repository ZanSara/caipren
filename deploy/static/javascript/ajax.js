
// AJAX retrieving booking data for newBookingModal
function getBookingData(prenid, gestione){

    var decoded = 0;
    $.get('/caipren/dati', {
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


// AJAX retrieving booking data for FindModal
function findBookings(args){
    
    var decoded = 0;
    $.get('/caipren/find', args).done(function(gotData) {
            
            console.log(args);
            try{
                console.log(gotData);
                var decoded = JSON.parse(gotData);
            }catch (Exception) {
                renderError(Exception, gotData);
                return;
            };
            console.log('beginning...');
            renderFindData(decoded);
            console.log('rendered');

    }).fail(function() {
        renderError("Errore AJAX", "Errore AJAX", "Errore AJAX");
    });
}



