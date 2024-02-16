#!/bin/bash

# On regarde si php est installé actuellement
php_installed=$(dpkg -l | grep -E 'php ' | wc -l)

# On regarde si PDO est installé actuellement
pdo_installed=$(php -m | grep -c PDO)

# On regarde si pdo_sqlite est installé actuellement
pdo_sqlite_installed=$(php -m | grep -c pdo_sqlite)

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

if [ "$pdo_sqlite_installed" -eq 0 ]; then
    echo "pdo_sqlite n'est pas installé. Installation en cours..."
    sudo apt install php-sqlite3
    echo "pdo_sqlite a été installé avec succès."
else
    echo "pdo_sqlite est déjà installé."
fi