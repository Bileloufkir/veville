$(document).ready(function(){

    $('#photo').on('change',function(e){
        $('#fichier').html(e.target.files[0].name );
    });
    $('.confirm').on('click',function(){
        return(confirm('Etes vous certain(e) de vouloir supprimer ce produit ? '));
    });


    // tester l'existence d'élément sur la page 
    if($('#maModale').length == 1 ){
        $('#maModale').modal('show');
    }

    // $.extend($.fn.datetimepicker.Constructor.Default, {
    //     icons: {
    //         time: 'fas fa-clock h3 text-success',
    //         date: 'fas fa-calendar h3 text-success',
    //         up: 'fas fa-arrow-up text-success',
    //         down: 'fas fa-arrow-down text-success',
    //         previous: 'fas fa-chevron-left text-success',
    //         next: 'fas fa-chevron-right text-success',
    //         today: 'fas fa-calendar-check-o text-success',
    //         clear: 'fas fa-trash',
    //         close: 'fas fa-times'
    //     } 
    // });

  
});    // -----------tri par prix croissant -----------

 