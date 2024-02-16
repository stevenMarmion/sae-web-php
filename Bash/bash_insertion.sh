#!/bin/bash

# Chemin du dossier contenant les fichiers .yml
dossier="DataRessources/"

# Liste des fichiers .yml dans le dossier
fichiers_yaml=("$dossier"*.yml)

# Affichage des fichiers .yml disponibles
echo "Liste des fichiers .yml disponibles :"
for fichier in "${fichiers_yaml[@]}"; do
    echo " - $(basename "$fichier")"
done

# Demande à l'utilisateur de choisir un fichier
read -p "Veuillez choisir un fichier .yml : " fichier_utilisateur

# Vérification si le fichier choisi existe
if [[ ! " ${fichiers_yaml[@]} " =~ " ${dossier}${fichier_utilisateur} " ]]; then
    echo "Erreur : Le fichier choisi n'est pas valide."
    exit 1
fi

# Exécution de la commande avec le fichier choisi
commande="php Database/DatabaseConnection/InstancesTables.php $fichier_utilisateur"
echo "Exécution de la commande : $commande"
eval "$commande"
