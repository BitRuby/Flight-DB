    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="https://use.fontawesome.com/53dce02109.js"></script>
    <script src="themes/<?php echo $ThemeName ?>/scripts/sc.js"></script>
    <script>
       $(".menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
            $("#wrapper").toggleClass("toggledmenu");
    });
   
      $(".sidebar-nav>li").has("ul").click(
            function(e){
              if (!$(e.target).parents('ul').hasClass('toggled')) {
                $(this).children("ul").toggleClass('toggled');
                $(this).toggleClass("toggled");
              }
              if($("#wrapper").hasClass("toggledmenu")){
                $("#wrapper").toggleClass("toggled");
                $("#wrapper").toggleClass("toggledmenu");
              }
            }    
        );
        //Chart.js from frontpage.php
        <?php $ameryka = array();
              $europa = array();
                $Queries1 = new Queries("3","2001"); 
                $Queries2 = new Queries("3","2005"); 
                $Queries3 = new Queries("3","2010"); 
                $Queries4 = new Queries("3","2015"); 
                $Queries5 = new Queries("7","2001"); 
                $Queries6 = new Queries("7","2005"); 
                $Queries7 = new Queries("7","2010"); 
                $Queries8 = new Queries("7","2015"); 
                $ameryka = [
                Content::getNumRows($Queries1->lotyLataKontynenty),
                Content::getNumRows($Queries2->lotyLataKontynenty),
                Content::getNumRows($Queries3->lotyLataKontynenty),
                Content::getNumRows($Queries4->lotyLataKontynenty)    
                ];
                $europa = [
                Content::getNumRows($Queries5->lotyLataKontynenty),
                Content::getNumRows($Queries6->lotyLataKontynenty),
                Content::getNumRows($Queries7->lotyLataKontynenty),
                Content::getNumRows($Queries8->lotyLataKontynenty)     
                ];
        ?>

        
        $(document).ready(function () {
        new Chart(document.getElementById("bar-chart-grouped"), {
            type: 'bar',
            data: {
              labels: ["2001", "2005", "2010", "2015"],
              datasets: [
                {
                  label: "Ameryka Północna",
                  backgroundColor: "#2c3e50",
                  data: <?=json_encode(array_values($ameryka));?>,
                }, {
                  label: "Europa",
                  backgroundColor: "#2980b9",
                  data: <?=json_encode(array_values($europa));?>,
                }
              ]
            },
            options: {
              title: {
                display: true,
                text: 'Wykres sumy lotów w Europie i Ameryce Północnej według lat'
              }
            }
        });
     });
    </script>
  </body>
</html>
