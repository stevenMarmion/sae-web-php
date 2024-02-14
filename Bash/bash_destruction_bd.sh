#!/bin/bash

# Spécifie le chemin du dossier
dossier="Database/DatabaseScripts"

# Vérifie si le dossier existe
if [ -d "$dossier" ]; then
    # Supprime tous les fichiers .sqlite3 dans le dossier
    find "$dossier" -type f -name "*.sqlite3" -exec rm -f {} \;

    echo "Fichiers .sqlite3 supprimés avec succès."
else
    echo "Le dossier $dossier n'existe pas."
fi
