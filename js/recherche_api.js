var trier = "";
var genre = "";
var annee = "";
var delay = (function() {
    var timer = 0;
    return function(callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();


$( document ).ready(function(){
    $('#api_form').submit(function(event){
        $('#api_form').append("<div class=\"loader\"></div>");

        console.log($("#api_form :input[name='genre']")[0].value);
        event.preventDefault();
        jQuery.ajax({
            type: "POST",
            url: "../include/requeteAjaxJs.php",
            data: {
                functionname: 'rechercheAvanceeAPI',
                arguments: [
                    $("#api_form :input[name='trier']")[0].value,
                    $("#api_form :input[name='keywords']")[0].value,
                    $("#api_form :input[name='genre']")[0].value,
                    $("#api_form :input[name='debut']")[0].value,
                    $("#api_form :input[name='fin']")[0].value
                ]
            }
        }).done(function(reponse) {
            $('.loader').remove();
            $("#res").html(reponse);
        });
    });
});