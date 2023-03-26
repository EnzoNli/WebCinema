const ratingStars = [...document.getElementsByClassName("rating__star")];

function createPopup(isSuccess, message){
    let res = "";
    res = `
        <section id="popup" class="active">
        <div class="modal-box">`;
        
    if(isSuccess){
        res += `
            <i class="fa-regular fa-circle-check" id="logo"></i>
            <h2>Succès !</h2>`;
    }else{
        res += `
            <i class="fa-regular fa-circle-xmark" id="logo"></i>
            <h2>Echec !</h2>`;
    }
    
    res += "<h3>" + message + `</h3>
    <div class="buttons">
    <button class="close-btn">Ok</button>
    </div>
    </div>
    </section>
    `;


    $("#popup-container").html(res);
    
    if(!isSuccess){
        $("#logo").css("color", "#f12222");
    }
    $(".close-btn").click(function(){
        $("#popup-container").html("");
        location.reload();
    });
}

function executeRating(stars) {
    const starClassActive = "rating__star fas fa-star";
    const starClassInactive = "rating__star far fa-star";
    const starsLength = stars.length;
    let i;
    stars.map((star) => {
        star.onclick = () => {
            i = stars.indexOf(star);

            if (star.className === starClassInactive) {
                for (i; i >= 0; --i) stars[i].className = starClassActive;
            } else {
                for (i; i < starsLength; ++i) stars[i].className = starClassInactive;
            }
        };
    });
}

executeRating(ratingStars);

$("#sub").submit(function(event) {
    var numItems = $('.fas').length
    var comment = $('textarea#com').val();
    event.preventDefault();
    jQuery.ajax({
        type: "POST",
        url: "../include/requeteAjaxJs.php",
        data: {
            functionname: 'noteFilm',
            arguments: [nom_utilisateur, id_movie, numItems, comment]
        }
    }).done(function(reponse) {
        switch (reponse) {
            case "0":
                createPopup(true, "Le commentaire a bien été pris en compte");
                break;
            case "23000":
                createPopup(false, "Vous avez déjà noté ce film !");
                break;
            default:
                createPopup(false, "Oups ! Quelque chose s'est mal passé !");
                break;
        }
    });
});