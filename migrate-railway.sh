#!/bin/bash

# Script de migration pour Railway
echo "🚂 Migration MaxIT vers Railway PostgreSQL"
echo "=========================================="

# Variables Railway
DATABASE_URL="postgresql://postgres:NvkogRfRpUphAVmRnzpiKKUSKnHTwQMw@yamabiko.proxy.rlwy.net:16680/railway"

# Vérifier que psql est installé
if ! command -v psql &> /dev/null; then
    echo "❌ psql n'est pas installé. Installez-le avec :"
    echo "sudo apt install postgresql-client"
    exit 1
fi

# Vérifier que le fichier de migration existe
if [ ! -f "migrations/script.sql" ]; then
    echo "❌ Fichier migrations/script.sql non trouvé"
    exit 1
fi

echo "📁 Fichier de migration: migrations/script.sql"
echo "🔗 Base de données: yamabiko.proxy.rlwy.net:16680/railway"
echo ""

# Test de connexion
echo "🔍 Test de connexion..."
if psql "$DATABASE_URL" -c "SELECT version();" > /dev/null 2>&1; then
    echo "✅ Connexion réussie !"
else
    echo "❌ Échec de connexion. Vérifiez vos credentials."
    exit 1
fi

# Exécution des migrations
echo ""
echo "📊 Exécution des migrations..."
psql "$DATABASE_URL" -f migrations/script.sql

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Migrations exécutées avec succès !"
    
    # Vérification des tables
    echo ""
    echo "📋 Tables créées :"
    psql "$DATABASE_URL" -c "\dt"
    
    echo ""
    echo "📊 Nombre d'enregistrements :"
    psql "$DATABASE_URL" -c "
        SELECT 'users' as table_name, COUNT(*) as count FROM users
        UNION ALL
        SELECT 'compte' as table_name, COUNT(*) as count FROM compte
        UNION ALL
        SELECT 'transaction' as table_name, COUNT(*) as count FROM transaction;
    "
    
    echo ""
    echo "🎉 Base de données Railway prête pour MaxIT !"
else
    echo "❌ Erreur lors de l'exécution des migrations"
    exit 1
fi
