#!/bin/bash

# Se déplacer dans le répertoire contenant les tests des modèles
cd Test/ModelsTest

# Boucler à travers tous les fichiers se terminant par "Test.php"
for file in *Test.php; do
    echo "Exécution de $file :"
    php "$file"
    echo "----------------------------------------"
done

# Se déplacer dans le répertoire contenant les tests de la base de données
cd ../DatabaseTest

# Boucler à travers tous les fichiers se terminant par "Test.php"
for file in *Test.php; do
    echo "Exécution de $file :"
    php "$file"
    echo "----------------------------------------"
done
