 <!-- MAIN TABLE -->
    <div class="row">
        <div id="calendario-box" class="calendario-outer-box shadow2">
          <table id="tableid" style="overflow:hidden;">
              <tr><td>
                <div class='scrollable'>
                <table class="calendario">
                    <tr>
                        <td class='giorno' style="border: 1px solid #fff;">Data</td>
                        <td class='gestore' style="border: 1px solid #fff;">Gestore</td>
                        <td class='letto'></td>
                        <td class='letto'></td>
                        <td class='letto'></td>
                        <td class='letto'>Letto</td>
                        <td class='letto'></td>
                        <td class='letto'></td>
                        <td class='bivacco' style="border-left: 1px solid #fff;"></td>
                        <td class='bivacco'>Bivacco</td>
                        <td class='bivacco'></td>
                        <td class='bivacco'></td>
                        <td class="brandina" style="border-left: 1px solid #fff;">Brandina</td>
                        <td class="brandina"></td>
                        <td class="materasso" style="border-left: 1px solid #fff;"></td>
                        <td class="materasso">Materasso</td>
                        <td class="materasso"></td>
                        <td class="materasso"></td>
                    </tr>
                </table>
                </div>
              </td></tr>
              <tr><td>
                <div class="calendario-inner-box scrollable">
                <table class="calendario grid">
                  <colgroup>
                        <col class='giorno'>
                        <col class='gestore'>
                        <col class='letto'>
                        <col class='letto'>
                        <col class='letto'>
                        <col class='letto'>
                        <col class='letto'>
                        <col class='letto'>
                        <col class="bivacco">
                        <col class="bivacco">
                        <col class="bivacco">
                        <col class="bivacco">
                        <col class="brandina">
                        <col class="brandina">
                        <col class="materasso">
                        <col class="materasso">
                        <col class="materasso">
                        <col class="materasso">
                  </colgroup>
                  <tbody>
                  
                  <?
                    
                    for($d = 0; $d < ($this->LastDay - $this->FirstDay); $d++){
                    
                        $fulldate = $this->Dates[$d];
                        
                        $absday = $fulldate[0];
                        $day = $fulldate[1];
                        $monthname = $fulldate[2];
                        $monthnum = $fulldate[3];
                        $weekday = $fulldate[4];
                  
                        // Building tr
                        echo("<tr id='".$day."-0".$monthnum."'");
                            if( $day == 1 ) echo ("style='border-top: 4px solid black;'");
                            if( $absday == $this->LastDay-1 ) echo ("style='border-bottom: 4px solid #000;'");
                            if( $this->Today == $absday) echo("id='today' class='border'");
                        echo(">");

                        // Building Date td
                        echo("<td ");
                            if( $weekday == "S" ) echo("style='color:blue;'");
                            if( $weekday == "D" ) echo("style='color:red;'");
                            if( $monthname == "Ago" and $day==15 ) echo("style=color:red;text-decoration:underline;");
                        echo(">");
                            echo($day." ".$monthname." ".$weekday );//." / ".$absday);
                        echo("</td>");
                    
                    
                        // Building Gestore td
                        $gestore = $this->Gestori[$d];
                        $numGestori = $gestore[0];                        
                        if ($numGestori == 0){
                            echo("<td class='nogestore'>Nessuno!</td>");

                        } else {
                            $idGestore = $gestore[1];
                            $nomeGestore = $gestore[2];
                           
                            echo("<td class='P".$idGestore."' ");
                            if($numGestori > 1){
                                echo(" style='border:4px solid red;'");
                            }
                            if($idGestore == $this->LastPrenID){
                                echo(" style='border:4px solid yellow;'");
                            }
                            echo(">");
                            echo("<a id='".$absday."-G' onclick='javascript:openNewBookingModal(1, ".$idGestore.", 1);' ><div>");
                            echo($nomeGestore);
                            echo("</div></a>");
                            echo("</td>");
                        }
                        
                        
                        // Filling the rest of the table
                        $bookings = $this->Bookings[$d];
                        
                        $tot = 0;
                        foreach($bookings as $cell){
                            $tot++;
                            echo("<td class='P".$cell[0]."' style='background:".$cell[1].";");
                            if($cell[0] == $this->LastPrenID){
                                echo("border: yellow 4px solid;'>");
                            }else{
                                echo("'>");
                            }
                            echo("<a onclick='javascript:openNewBookingModal(1, ".$cell[0].", 0);' ><div>");//<div style='border: red 1px solid;'>");
                            echo('<b>P'.$cell[0].'</b>');
                            echo("</div></a>");
                            echo("</td>");
                        }
                        for(;$tot<16; $tot++){
                            echo ("<td></td>");
                        }

                        // Close tr
                        echo ("</tr>");       
                    
                    }
                                     
                  ?>
                  
                  </tbody>
                </table>
              </div>
            </td></tr>
          </tbody>
        </table>
        
        
        <script type="text/javascript">

            // Reset NewB_Modal when closed
            $("#newBookingModal").on("hidden.bs.modal", function (event) {
                
                // Reset titles
                $("#nBM-dataTitle").show();
                $("#nBM-dataTitle").text("Prenotazione");
                $("#nBM-loadingTitle").hide();
                $("#nBM-errorTitle").hide();
                
                // Reset Top Buttons
                $("#nBM-ChiudiTopBtn").hide();
                $("#nBM-ModificaBtn").hide();
                
                //Reset body
                $("#nBM-errorBox").hide();
                $("#nBM-spinningWheel").hide();
                $("#nBM-mainForm").show();
                $("#newBookingModal form")[0].reset();
                $("#newBookingModal input").prop("readonly", true);
                $("#newBookingModal textarea").prop("readonly", true);
                $("#nBM-gestioneChk").prop("disabled", "" );
                $("#nBM-gestioneChk").prop("checked", "" );
                $("#nBM-linePosti").show();
                
                // Reset hidden checks
                $("#nBM-newChk").prop("disabled", "disabled");
                $("#nBM-newChk").prop("checked", true );
                $("#nBM-delChk").prop("disabled", "disabled");
                $("#nBM-delChk").prop("checked", "" );
                
                // Show buttons
                $("#nBM-arrivoBtn").hide();
                $("#nBM-postiBtn").hide();
    
                // Reset footer
                $("nBM-SalvaBtn").hide();
                $("nBM-EliminaBtn").hide();
            });


            // ************* STILL TO DO **************************************************************
            
            // Open Error_Modal in case of PHP errors (like "No more beds on these days")
            if( $("#ErrorModal").data("error") ){
                $("#ErrorModal").modal("show");
            }
            // Open Id_Modal in case of success of the registration
            if( $("#IdModal").data("open") ){
                $("#IdModal").modal("show");
            }
            
            // Reset Find_Modal when closed
            $("#Find_Modal").on("hidden.bs.modal", function (event) {
                $("#findmessage-alert").hide();
                $("#finderror-alert").hide();
                $(".modal-errfooter").hide();
                
                $("#cercaid").val(""),
                $("#cercanome").val(""),
                $("#cercatel").val(""),
                $("#cercaarrivo").val(""),
                $("#cercaprov").val(""),
                $("#cercadurata").val(""),
                $("#cercaposti").val(""),
                $("#cercaresp").val(""),
                $("#cercanote").val(""),
                
                $("#find-dataTitle").text("Cerca");
                $("#findwarning").show();
                $(".modal-databox").show();
                $(".modal-resultbox").hide();
                $("#findresults tr").remove();
                $("#findresults").hide();
                $(".modal-footer").show();
                $("#find-btn").show();
            });

        </script>
        
        
      </div>
    </div>
