</main>

    <footer class="container-fluid">
    <div class="row text-center py-3 mt-4 piedpage">

        <div class="container rgpd pb-4">
        <small class="row">
           <div class="rgpd2">  <a href="<?= URL . 'cgv.php' ?>">CGV</a> </div>
           <div class="rgpd2">   <a href="<?= URL . 'mention.php' ?>"  >Mentions Légales</a> </div>
           <div class="rgpd2">   <a href="<?= URL . 'rgpd.php' ?>">RGPD</a>   </div>
           <div class="rgpd2">     <a href="<?= URL . 'cookies.php' ?>">Cookies</a>  </div>
                    </small>
        </div>
        <div class="container-fluid">
            <div class="row bas">                        
                <div class="copyrights col-12"><span style="color:white">
                    &copy; <?= date('Y') ?> - Veville -
                            Bilel OUFKIR
                    <p> Ce site n'a aucun but commercial, il est réalisé dans le cadre d'un atelier pédagogique  au sein de l'organisme de formation IFOCOP. </p>

        </div>
            </div>
                </div>
    </div>
</footer>


<script type="text/javascript">



$.extend($.fn.datetimepicker.Constructor.Default, {
    icons: {
        time: 'far fa-clock h4 ',
        date: 'fas fa-calendar-alt h4 ',
        up: 'fas fa-arrow-up ',
        down: 'fas fa-arrow-down ',
        previous: 'fas fa-chevron-left ',
        next: 'fas fa-chevron-right ',
        today: 'fas fa-calendar-check-o ',
        clear: 'fas fa-trash',
        close: 'fas fa-times'
    } 
});



    $(function () {
        $('#datetimepicker7').datetimepicker({
            stepping: 30,
            format: 'YYYY-MM-DD'
           // forceMinuteStep: true
           // defaultDate: new Date(),

        });
        $('#datetimepicker8').datetimepicker({
            stepping: 30,
            useCurrent: false,
            format: 'YYYY-MM-DD'
           // forceMinuteStep: true
           // defaultDate: new Date(),

        });
  
        
        $("#datetimepicker7").on("change.datetimepicker", function (e) {
            $('#datetimepicker8').datetimepicker('minDate', e.date);
        });
        $("#datetimepicker8").on("change.datetimepicker", function (e) {
            $('#datetimepicker7').datetimepicker('maxDate', e.date);
        });


        $('#datetimepicker8').datetimepicker({
            useCurrent: false,   
        });


          
        

});  
  
</script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.js" integrity="sha512-nO7wgHUoWPYGCNriyGzcFwPSF+bPDOR+NvtOYy2wMcWkrnCNPKBcFEkU80XIN14UVja0Gdnff9EmydyLlOL7mQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        
        <!-- Icons -->
        <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
        <script>
          feather.replace()
        </script>


	
  </body>
</html>