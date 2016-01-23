<?

// Server-side booking routine

$username = "6786_utentesql";
$password = "databasecai";
$hostname = "localhost";

$dbhandle = mysqli_connect($hostname, $username, $password)
  or die("Unable to connect to MySQL");
$selected = mysqli_select_db($dbhandle, "6786_pernottamenti")
  or die("Could not select database");


// Validate input HERE!

if ($_POST['nome']== '')  die( json_encode( array( 'ERROR' => "Inserire il nome del cliente.") ));
if (strlen($_POST['nome'])> 100)  die( json_encode( array( 'ERROR' => "Inserire solo il nome del cliente nella prima riga!") ));
$nome = mysqli_real_escape_string($dbhandle, $_POST['nome']);

if ($_POST['telefono']== '')  die( json_encode( array( 'ERROR' => "Inserire un numero di telefono.") ));
if (strlen($_POST['telefono'])> 15)  die( json_encode( array( 'ERROR' => "Inserire un numero di telefono valido.") ));
$telefono = mysqli_real_escape_string($dbhandle, $_POST['telefono']);

// Here I should check the date format and convert it to its absolute day value T-T
if ($_POST['arrivo']== '')  die( json_encode( array( 'ERROR' => "Inserire una data di arrivo valida.") ));
$arrivo = mysqli_real_escape_string($dbhandle, $_POST['arrivo']);

if ((int)($_POST['durata'])<= 0 or (int)($_POST['durata'])>= 122)  die( json_encode( array( 'ERROR' => "La durata del soggiorno non e' valida.") ));
$durata = (int)mysqli_real_escape_string($dbhandle, $_POST['durata']);

if ((int)($_POST['posti'])<= 0 or (int)($_POST['durata'])>= 15)  die( json_encode( array( 'ERROR' => "Inserire un numero di posti prenotati valido.") ));
$posti = mysqli_real_escape_string($dbhandle, $_POST['posti']);

if (strlen($_POST['note'])> 1000)  die( json_encode( array( 'ERROR' => "Note troppo lunghe! Massimo 1000 caratteri.") ));
$note = mysqli_real_escape_string($dbhandle, $_POST['note']);

$gestione = 0;
if (isset($_POST['gestione'])) {
   $gestione = 1;
}

if ($_POST['responsabile']== '' and $gestione == 0) die( json_encode( array( 'ERROR' => "Inserire il nome del responsabile della prenotazione.") ));
if (strlen($_POST['responsabile'])> 100)  die( json_encode( array( 'ERROR' => "Nome del responsabile della prenotazione troppo lungo!") ));
$resp = mysqli_real_escape_string($dbhandle, $_POST['responsabile']);



// Retrieve the new color
$dbLastColor = mysqli_fetch_array( mysqli_query($dbhandle, "SELECT ID FROM Colori WHERE last = 1") );
$dbNewColor = mysqli_fetch_array( mysqli_query($dbhandle, "SELECT ID FROM Colori WHERE ID = ".($dbLastColor['ID']+1)) );

// Actually write reservation in DB
$values = "(NULL, '".$nome."', '".$telefono."', '".$arrivo."', '".$durata."', '".$posti."', '".$note."', '".$gestione."', '".$resp."', '".$dbNewColor."')";
$result = mysqli_query($dbhandle, "INSERT INTO `Pernottamenti`
                        (`id`, `nome`, `tel`, `giorno_inizio`, `durata`, `posti`, `note`, `gestione`, `responsabile`, `colore`)
                        VALUES".$values);
if ($result == 1){
    // Update Last Color
    $result2 = mysqli_query( $dbhandle, "UPDATE Colori SET last = 1 WHERE ID = ".$dbNewColor);
    $result3 = mysqli_query( $dbhandle, "UPDATE Colori SET last = 0 WHERE ID = ".$dbLastColor);
    if ($result2 == 0 or $result3 == 0){
        echo('Color update failed!');
    }
}else{
    echo('Registration failed!');
    }


mysqli_close($dbhandle);



echo(" nome ".$nome);
echo(", telefono ".$telefono);
echo(", arrivo ".$arrivo);
echo(", durata ".$durata);
echo(", posti ".$posti);
echo(", resp ".$resp);
//echo("---".strlen($_POST['responsabile'])."---");
echo(", note ".$note);
echo(", gestione ".$gestione);

?>
