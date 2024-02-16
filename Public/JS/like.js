

function like(idAlbum) {
    console.log("test")
    console.log(document.getElementsByName('like' + idAlbum)[0].getAttribute("activer")=="true")
    console.log("test")

    if(document.getElementsByName('like' + idAlbum)[0].getAttribute("activer")=="false"){
        document.getElementsByName('like' + idAlbum)[0].setAttribute("activer","true")
        document.getElementsByName('like' + idAlbum)[0].children[0].src="/DataRessources/like/coeur_remplie.jpg";
        $.ajax({
            url: '../../Controllers/Album/like.php',
            type: 'POST',
            data: {
                'idAlbum': idAlbum
            },
            success: function(response) {
                console.log(response); // affiche la réponse du serveur dans la console
            },
            error: function(error) {
                console.log(error); // affiche l'erreur dans la console si quelque chose ne va pas
            }
        });
    }
    else{
        document.getElementsByName('like' + idAlbum)[0].setAttribute("activer","false")
        document.getElementsByName('like' + idAlbum)[0].children[0].src="/DataRessources/like/coeur_vide.jpg";
        $.ajax({
            url: '../../Controllers/Album/dislike.php',
            type: 'POST',
            data: {
                'idAlbum': idAlbum
            },
            success: function(response) {
                console.log(response); // affiche la réponse du serveur dans la console
            },
            error: function(error) {
                console.log(error); // affiche l'erreur dans la console si quelque chose ne va pas
            }
        });
    }
}