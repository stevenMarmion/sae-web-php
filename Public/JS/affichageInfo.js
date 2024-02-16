function affichageInfo(cheminImg,titre,compositeur,interprete,annee,genre){
    div=document.getElementsByClassName("infoAlbum")[0];
    div.hidden=false;  
    html="<img src='"+cheminImg+"' alt='image album' class='imgAlbum'>";
    html+="<div class='info'>";
    html+="<p class='titre'>"+titre+"</p>";
    html+="<p class='compositeur'>"+compositeur+"</p>";
    html+="<p class='interprete'>"+interprete+"</p>";
    html+="<p class='annee'>"+annee+"</p>";
    html+="<p class='genre'>"+genre+"</p>";
    html+="</div>";
    div.innerHTML=html;
};