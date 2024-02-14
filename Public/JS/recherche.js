function changement(){
    if(document.querySelector("#type-recherche").options[document.querySelector("#type-recherche").selectedIndex].value=="genre"){
        document.querySelector("#recherche").hidden=true;
        document.querySelector("#recherche").required=false;
        document.querySelector("#recherche-genre").hidden=false;
    }
    else{
        document.querySelector("#recherche").hidden=false;
        document.querySelector("#recherche").required=true;
        document.querySelector("#recherche-genre").hidden=true;
    }
}