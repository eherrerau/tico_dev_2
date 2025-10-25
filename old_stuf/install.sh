#!/bin/bash

# TICO Installation Script
# Este script automatiza la instalación y configuración del sistema modernizado

set -e

echo "🚀 Iniciando instalación de TICO - Tickets Control Center"
echo "======================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Helper functions
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Step 1: Check prerequisites
echo ""
print_status "Verificando prerequisitos..."

if ! command_exists php; then
    print_error "PHP no está instalado. Instala PHP 8.1 o superior."
    exit 1
fi

PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
if [[ $(echo "$PHP_VERSION < 8.1" | bc -l) -eq 1 ]]; then
    print_error "PHP 8.1+ requerido. Versión actual: $PHP_VERSION"
    exit 1
fi
print_success "PHP $PHP_VERSION encontrado"

if ! command_exists composer; then
    print_error "Composer no está instalado. Instala Composer desde https://getcomposer.org/"
    exit 1
fi
print_success "Composer encontrado"

# Step 2: Install dependencies
echo ""
print_status "Instalando dependencias de PHP..."
composer install --optimize-autoloader

if [ $? -eq 0 ]; then
    print_success "Dependencias instaladas correctamente"
else
    print_error "Error instalando dependencias"
    exit 1
fi

# Step 3: Setup environment
echo ""
print_status "Configurando entorno..."

if [ ! -f .env ]; then
    cp .env.example .env
    print_success "Archivo .env creado desde .env.example"
else
    print_warning "Archivo .env ya existe"
fi

# Generate random APP_KEY
if ! grep -q "APP_KEY=base64:" .env; then
    RANDOM_KEY=$(openssl rand -base64 32)
    sed -i.bak "s/APP_KEY=base64:CHANGE_THIS_TO_A_RANDOM_32_CHAR_STRING/APP_KEY=base64:$RANDOM_KEY/" .env
    print_success "Clave de aplicación generada"
fi

# Step 4: Create directories and set permissions
echo ""
print_status "Creando directorios y configurando permisos..."

mkdir -p logs templates_c/cache public/uploads
chmod 755 public/
chmod 777 logs/ templates_c/

print_success "Directorios creados y permisos configurados"

# Step 5: Check PHP extensions
echo ""
print_status "Verificando extensiones PHP..."

REQUIRED_EXTENSIONS=("pdo" "mbstring" "json" "curl" "openssl" "zip")
MISSING_EXTENSIONS=()

for ext in "${REQUIRED_EXTENSIONS[@]}"; do
    if php -m | grep -q "^$ext$"; then
        print_success "Extensión $ext: ✓"
    else
        print_error "Extensión $ext: ✗ (requerida)"
        MISSING_EXTENSIONS+=("$ext")
    fi
done

OPTIONAL_EXTENSIONS=("pdo_sqlsrv" "pdo_mysql" "gd" "intl")
for ext in "${OPTIONAL_EXTENSIONS[@]}"; do
    if php -m | grep -q "^$ext$"; then
        print_success "Extensión $ext: ✓ (opcional)"
    else
        print_warning "Extensión $ext: ✗ (opcional)"
    fi
done

if [ ${#MISSING_EXTENSIONS[@]} -ne 0 ]; then
    print_error "Extensiones requeridas faltantes: ${MISSING_EXTENSIONS[*]}"
    print_error "Instala las extensiones faltantes y ejecuta este script nuevamente"
    exit 1
fi

# Step 6: Database configuration prompt
echo ""
print_status "Configuración de base de datos..."
print_warning "Edita el archivo .env para configurar tu conexión a la base de datos:"
echo ""
echo "DB_HOST=tu_servidor_sql_server"
echo "DB_DATABASE=TICO_DB"
echo "DB_USERNAME=tu_usuario"
echo "DB_PASSWORD=tu_password"
echo ""

# Step 7: Security recommendations
echo ""
print_status "Recomendaciones de seguridad..."
print_warning "Para producción, asegúrate de:"
echo "1. Configurar HTTPS/SSL"
echo "2. Configurar APP_ENV=production en .env"
echo "3. Configurar APP_DEBUG=false en .env"
echo "4. Configurar un servidor web real (Apache/Nginx)"
echo "5. Configurar backups automáticos de la base de datos"
echo ""

# Step 8: Final steps
echo ""
print_status "Pasos finales..."

echo "📝 Archivos de configuración importantes:"
echo "   .env                    - Configuración de entorno"
echo "   apache-config.conf      - Configuración Apache"
echo "   docker-compose.yml      - Para desarrollo con Docker"
echo ""

echo "📊 Comandos útiles:"
echo "   composer test           - Ejecutar tests"
echo "   composer phpstan        - Análisis estático"
echo "   composer cs-check       - Verificar estilo de código"
echo ""

echo "🌐 Para desarrollo local:"
echo "   php -S localhost:8000 -t public/"
echo "   Luego visita: http://localhost:8000"
echo ""

echo "🐳 Para desarrollo con Docker:"
echo "   docker-compose up --build"
echo "   Luego visita: http://localhost:8080"
echo ""

echo "🔍 Health Check:"
echo "   Visita: http://localhost:8000/../health-check.php"
echo ""

print_success "¡Instalación completada!"
print_status "Revisa README_MODERNIZATION.md para documentación completa"

# Check if we can start a development server
if command_exists php && [ -f public/index.php ]; then
    echo ""
    read -p "¿Quieres iniciar el servidor de desarrollo ahora? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        print_status "Iniciando servidor de desarrollo en http://localhost:8000"
        print_warning "Presiona Ctrl+C para detener el servidor"
        echo ""
        php -S localhost:8000 -t public/
    fi
fi
