<!-- FOOTER -->
    <footer>
      <div>
        <button class="btn btn-success" onclick="javascript:openNewBookingModal(0, 0, 0)">Nuova Prenotazione</button>
        <a data-toggle="modal" data-target="#FindModal" ><li class="btn btn-primary">Cerca</li></a>
        <a class="btn btn-info" data-toggle="modal" data-target="#Adv_Modal">Avanzate</a>
        <a class="btn btn-warning" data-toggle="modal" data-target="#About_Modal">About</a>
        <a href="../prenotazioni/#<? echo date('j-n', strtotime('yesterday')); ?>" class="btn btn-danger">Logout</a>
      </div>
    </footer>

  </body>
</html>
