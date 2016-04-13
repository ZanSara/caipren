<html>
  <head>
      <meta charset='utf-8'>
      <title>Svuota Database - CAI Sovico</title>
      <link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="static/css/style.css" rel="stylesheet">
  </head>
  <body style="margin:20px;text-align:center;">
    <h1>SVUOTA DATABASE</h1>

    <div style="background:#FFCCCC;">
        <hr>
        <p>
            Sei SICURO di voler cancellare completamente il database?
            <br>
            Questa operazione è IRREVERSIBILE e comporta la perdita di TUTTI i dati della stagione corrente.
        </p>
        <hr>
    </div>


    <a class="btn btn-danger btn-erasedb" href="erase-database-stage2.php">Svuota il Database</a>
    <br>

    <a class="btn btn-success" href="index.php#<? echo date('j-n', strtotime('yesterday')); ?>">Torna Indietro</a>

  </body>
</html>
