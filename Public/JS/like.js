var req = new XMLHttpRequest();

req.open('POST', 'https://localhost:8000/', true);

req.addEventListener('load', function () {

	if (req.status >= 200 && req.status < 400) {

        console.log(this.responseText);

    } else {

        console.error(req.status + " " + req.statusText);

    }

});

// Lorsque la requête rencontre un erreur la fonction est enclenchée
req.addEventListener('error', function () {
    console.error('La requête à recontrer un problème');
});

req.send();