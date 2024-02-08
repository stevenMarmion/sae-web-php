#!/bin/bash

# Se déplacer dans le répertoire contenant les tests
cd Test/ModelsTest

# Boucler à travers tous les fichiers se terminant par "Test.php"
for file in *Test.php; do
    echo "Exécution de $file :"
    php "$file"
    echo "----------------------------------------"
done
