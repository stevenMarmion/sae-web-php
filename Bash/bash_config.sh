#!/bin/bash

# Vérifier si PHP est déjà installé
php_installed=$(dpkg -l | grep -E '^ii\s+php[0-9]+\s+' | wc -l)

# Vérifier si PDO est déjà installé
pdo_installed=$(php -m | grep -c '^pdo$')

if [ "$php_installed" -eq 0 ]; then
    echo "PHP n'est pas installé. Installation en cours..."
    sudo apt update
    sudo apt install php 
    echo "PHP a été installé avec succès."
else
    echo "PHP est déjà installé."
fi

if [ "$pdo_installed" -eq 0 ]; then
    echo "PDO n'est pas installé. Installation en cours..."
    sudo apt install php-pdo
    echo "PDO a été installé avec succès."
else
    echo "PDO est déjà installé."
fi