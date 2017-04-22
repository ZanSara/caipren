<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <title>Prenotazioni - CAI Sovico</title>
     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="static/css/mainStyle.css" rel="stylesheet">
    <link href="static/css/calendarStyle.css" rel="stylesheet">
    <link href="static/css/bannerStyle.css" rel="stylesheet">
    <link href="static/css/modalStyle.css" rel="stylesheet">
    
    <script src="static/javascript/jQuery/jquery-1.11.2.min.js"></script>
    <script src="static/javascript/jQuery/jquery.validate.min.js"></script>
    <script src="static/bootstrap/js/bootstrap.min.js"></script>
    <script src="static/javascript/modalUtils.js"></script>
    <script src="static/javascript/ajax.js"></script>

    <script type="text/javascript">
    // jQuery validation plugin settings

        var now     = new Date();//(2017, 01, 01);
        var year    = now.getFullYear();

        $.validator.addMethod("customData", function(value) {
            // test this terrible regex here http://www.regular-expressions.info/javascriptexample.html
            // Matches only days between 1 June and 30 Sept
            var re = new RegExp("^(((0[1-9]|1[0-9]|2[0-9]|30)-(0[6-9]))|((31)-(0[7-8])))-"+year+"$");
            if (!re.test(value)) {
                value1 = value+"-"+year;
                return re.test(value1);
            }
            return true;
        }, 'Inserire una data di arrivo valida (GG-MM-AAAA) compresa tra 01-06-'+year+' e 30-09-'+year);
        
        $.validator.addMethod("customPhone", function(value) {
            // Matches only string to 4 to 50 char long, allowing digits, spaces, brackets, + and -
            // Notice \\s: the escapes shoud be double escaped inside []!
            var re = new RegExp("^[+]?([0-9]|[\\s]|[-]|[(]|[)]){4,50}$");
            return re.test(value);
        }, 'Inserire un numero di telefono valido (min 4 max 50 caratteri. Permessi solo numeri, +, -, parentesi e spazi.');


        $.validator.addMethod("customDurata", function(value) {
            return (value < 122 && value > 0);
        }, 'Inserire una durata del soggiorno valida');

        $.validator.addMethod("customPosti", function(value) {
            return (value < 17 && value > 0);
        }, 'Inserire un numero di posti letto valido');


        $().ready(function() {
            $("#booking-form").validate({
                //debug: true,
                errorElement: "li",
                errorContainer: $("#message-alert"),
                errorPlacement: function(error) {
                  $("#message-alert").show();
                  $("#message-alert").append(error);
                }
            });

        });
    </script>

  </head>
  <body class="mainpage">
  
