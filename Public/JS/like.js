

function like(idAlbum) {
    if(document.getElementsByName('checkbox' + idAlbum)[0].checked == true){
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