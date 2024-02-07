function estLike(idAlbum) {
$.ajax({
    url: '../../Controllers/Album/estLike.php',
    type: 'POST',
    data: {
        'idAlbum': idAlbum
    },
    success: function(response) {
        if(response == "true"){
            document.getElementsByName('checkbox' + idAlbum)[0].checked=true;
        }
        else{
            document.getElementsByName('checkbox' + idAlbum)[0].checked=false;
        }
    },
    error: function(error) {
        console.log("test"); // affiche l'erreur dans la console si quelque chose ne va pas
    }
});
}