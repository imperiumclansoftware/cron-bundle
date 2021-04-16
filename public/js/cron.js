$(document).ready(function(){

    $('.custom-range').each(function(index,element){

        $(this).parent().append('<div class="range-etiquette">' + $(this).val() + ' ' + $(this).attr('unite') + '</div>');

    });
    
    $('.custom-range').change(function(){
        $(this).parent().children('.range-etiquette').html($(this).val() + ' ' + $(this).attr('unite'));
    });

});