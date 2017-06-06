<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><? echo $this->title; ?> - CAI Sovico</title>
     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="/caipren/static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/caipren/static/css/loginStyle.css" rel="stylesheet">
    <link href="/caipren/static/css/calendarStyle.css" rel="stylesheet">
    
    <script src="/caipren/static/javascript/jQuery/jquery-1.11.2.min.js"></script>
    <script src="/caipren/static/bootstrap/js/bootstrap.min.js"></script>

  </head>

  <body>

    <div class="container">

      <form class="form-signin text-center shadow1" action='/caipren/calendar'>
        <h3 class="form-signin-heading">Inserisci le tue credenziali</h3>
        <label for="inputEmail" class="sr-only">Username</label>
        <input type="text" id="inputEmail" class="form-control" placeholder="Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Accedi</button>
      </form>

    </div> <!-- /container -->

  </body>
</html>

