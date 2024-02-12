function estLike(idAlbum) {
    console.log(idAlbum)
$.ajax({
    url: '../../Controllers/Album/estLike.php',
    type: 'POST',
    data: {
        'idAlbum': idAlbum
    },
    success: function(response) {
        console.log("reponse sdjivhsuhvb "+response);
        if(response == "1"){
            document.getElementsByName('like' + idAlbum)[0].children[0].src="/DataRessources/like/coeur_remplie.jpg";
            document.getElementsByName('like' + idAlbum)[0].setAttribute("activer","true");
        }
        else{
            document.getElementsByName('like' + idAlbum)[0].children[0].src="/DataRessources/like/coeur_vide.jpg";
            document.getElementsByName('like' + idAlbum)[0].setAttribute("activer","false");
        }
    },
    error: function(error) {
        console.log("test"); // affiche l'erreur dans la console si quelque chose ne va pas
    }
});
}