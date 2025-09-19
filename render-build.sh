#!/bin/bash
# render-build.sh - Script de build simplificado para Render.com

echo "🚀 Iniciando build PHP no Render.com"
echo "PHP Version: $(php -v | head -n 1)"

# Verificar se vendor existe, se não, tentar instalar
if [ ! -d "vendor" ]; then
    echo "📦 Instalando dependências com composer..."
    composer install --no-dev --optimize-autoloader --no-interaction
else
    echo "✅ Vendor já existe, pulando instalação"
fi

echo "✅ Build completo com sucesso!"