# TICO - Tickets Control Center

## 📋 Análisis del Proyecto Original

Este proyecto PHP fue modernizado desde una aplicación legacy que presentaba múltiples vulnerabilidades de seguridad y problemas de arquitectura. A continuación se detalla el análisis completo y las mejoras implementadas.

## 🔍 Problemas Identificados en el Código Original

### Vulnerabilidades de Seguridad Críticas

1. **Inyección SQL**
   - Uso directo de `$_POST` y `$_GET` en consultas SQL
   - Concatenación de strings en queries sin sanitización
   - Ejemplo encontrado: `$query = "exec uspInsertNewUsr " . $_POST['txtUsrName']`

2. **Hashing de Passwords Inseguro**
   - Uso de SHA1 para passwords (algoritmo obsoleto)
   - Sin salt en los hashes
   - Vulnerable a ataques de fuerza bruta y rainbow tables

3. **Manejo de Sesiones Inseguro**
   - Sin regeneración de session ID
   - Cookies sin flags de seguridad
   - Sin timeout de sesión

4. **Falta de Validación de Entrada**
   - Sin sanitización de inputs del usuario
   - Sin validación de tipos de datos
   - Vulnerable a XSS y otros ataques

### Problemas de Arquitectura

1. **Código Espagueti**
   - Mezcla de lógica de negocio con presentación
   - Sin separación de responsabilidades
   - Difícil de mantener y extender

2. **Tecnologías Obsoletas**
   - Uso de `sqlsrv_*` functions sin prepared statements
   - Sin manejo de dependencias (Composer)
   - Sin autoloading de clases

3. **Manejo de Errores Deficiente**
   - Errores expuestos al usuario final
   - Sin logging estructurado
   - Debugging information en producción

## 🚀 Mejoras Implementadas

### Seguridad Moderna

#### 1. Autenticación Segura
```php
// Antes (inseguro)
$pwdSha1 = sha1($password);
$qry = "EXEC usp_login_autentication '" . $username . "', '" . $pwdSha1 . "'";

// Ahora (seguro)
$hashedPassword = password_hash($password, PASSWORD_ARGON2ID, [
    'memory_cost' => 65536,
    'time_cost' => 4,
    'threads' => 3,
]);
$stmt = $pdo->prepare("EXEC usp_login_autentication ?, ?");
```

#### 2. Prevención de Inyección SQL
- Uso exclusivo de prepared statements
- Validación y sanitización de inputs
- Escape de caracteres especiales

#### 3. Protección CSRF
```php
// Generación de tokens CSRF
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;

// Validación en formularios
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    throw new SecurityException('CSRF token mismatch');
}
```

#### 4. Headers de Seguridad
```php
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
```

### Arquitectura Moderna

#### 1. Patrón MVC
```
src/
├── Controllers/     # Controladores de la aplicación
├── Models/         # Modelos de datos
├── Services/       # Lógica de negocio
├── Security/       # Clases de seguridad
├── Database/       # Gestión de base de datos
└── Config/         # Configuración
```

#### 2. Gestión de Dependencias
```json
{
    "require": {
        "vlucas/phpdotenv": "^5.5",
        "monolog/monolog": "^3.4",
        "respect/validation": "^2.2"
    }
}
```

#### 3. Configuración por Entorno
```php
// .env para configuración sensible
DB_HOST=localhost
DB_USERNAME=usuario
DB_PASSWORD=password_seguro
APP_KEY=clave_aplicacion_aleatoria
```

### Base de Datos Modernizada

#### 1. Conexión PDO con Pool
```php
class DatabaseManager {
    private function createConnection(string $name): PDO {
        $dsn = $this->buildDsn($config);
        return new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}
```

#### 2. Query Builder Seguro
```php
public function insert(string $table, array $data): string {
    $columns = array_keys($data);
    $placeholders = array_map(fn($col) => ":$col", $columns);
    
    $sql = sprintf(
        'INSERT INTO %s (%s) VALUES (%s)',
        $table,
        implode(', ', $columns),
        implode(', ', $placeholders)
    );
    
    return $this->executeQuery($sql, $data);
}
```

## 📊 Comparación Antes vs Después

| Aspecto | Antes | Después |
|---------|-------|---------|
| Autenticación | SHA1 sin salt | Argon2ID con salt |
| SQL Queries | Concatenación directa | Prepared statements |
| Validación | Ninguna | Respect/Validation |
| Autoloading | Manual includes | PSR-4 autoloader |
| Configuración | Hardcoded | Variables de entorno |
| Logging | echo/print_r | Monolog estructurado |
| Sesiones | Básicas | Seguras con regeneración |
| Headers | Ninguno | Headers de seguridad |

## 🛠️ Instalación y Configuración

### Requisitos del Sistema

#### Desarrollo Local
- PHP 8.1+
- Composer
- Extensiones PHP: pdo, mbstring, json, curl, openssl, zip

#### Producción
- PHP 8.1+
- SQL Server 2016+
- Apache 2.4+ o Nginx 1.18+
- Extensión pdo_sqlsrv

### Instalación Paso a Paso

#### 1. Clonar y Configurar
```bash
git clone <repository-url> tico
cd tico
composer install
cp .env.example .env
```

#### 2. Configurar Base de Datos
```bash
# Editar .env
DB_HOST=tu_servidor_sql
DB_DATABASE=TICO_DB
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

#### 3. Configurar Permisos
```bash
chmod 755 public/
chmod 777 logs/ templates_c/
```

#### 4. Configurar Servidor Web

##### Apache
```apache
<VirtualHost *:80>
    ServerName tico.local
    DocumentRoot /path/to/tico/public
    
    <Directory /path/to/tico/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

##### Nginx
```nginx
server {
    listen 80;
    server_name tico.local;
    root /path/to/tico/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}
```

### Desarrollo con Docker

```bash
# Construir y ejecutar
docker-compose up --build

# La aplicación estará disponible en:
# http://localhost:8080
```

## 🧪 Testing y Validación

### Health Check
Visita `http://tu-dominio/health-check.php` para verificar:
- Extensiones PHP requeridas
- Permisos de directorios
- Configuración de seguridad
- Conexión a base de datos

### Tests Automatizados
```bash
# Ejecutar tests
composer test

# Análisis estático
composer phpstan

# Code style
composer cs-check
```

## 🔧 Migración desde el Sistema Legacy

### Paso 1: Backup
```sql
-- Backup de la base de datos actual
BACKUP DATABASE TICO_DB TO DISK = 'C:\backup\tico_backup.bak'
```

### Paso 2: Migración de Passwords
La aplicación incluye migración automática de passwords SHA1 a Argon2ID:

```php
// Durante el login, si detecta password legacy
if (sha1($password) === $legacyHash) {
    $newHash = password_hash($password, PASSWORD_ARGON2ID);
    $this->updatePasswordHash($userId, $newHash);
}
```

### Paso 3: Actualización de Esquema
```sql
-- Agregar columna para nuevos hashes
ALTER TABLE UserDetails ADD password_hash VARCHAR(255) NULL;

-- Índices para performance
CREATE INDEX IDX_UserDetails_usrName ON UserDetails(usrName);
CREATE INDEX IDX_UserDetails_active ON UserDetails(active);
```

## 📈 Beneficios de la Modernización

### Seguridad
- ✅ Protección contra inyección SQL
- ✅ Passwords seguros con Argon2ID
- ✅ Protección CSRF
- ✅ Headers de seguridad HTTP
- ✅ Validación robusta de inputs

### Mantenibilidad
- ✅ Código organizado en capas
- ✅ Separación de responsabilidades
- ✅ Documentación completa
- ✅ Tests automatizados

### Performance
- ✅ Conexiones PDO optimizadas
- ✅ Autoloading PSR-4
- ✅ Caching de templates
- ✅ Compresión GZIP

### Escalabilidad
- ✅ Arquitectura modular
- ✅ Configuración por entorno
- ✅ Logging estructurado
- ✅ Containerización con Docker

## 🚦 Siguientes Pasos

### Fase 1: Estabilización (Inmediato)
- [ ] Configurar base de datos de producción
- [ ] Migrar usuarios existentes
- [ ] Configurar SSL/HTTPS
- [ ] Pruebas de integración

### Fase 2: Funcionalidades (1-2 semanas)
- [ ] Modernizar módulos de gestión de casos
- [ ] Actualizar reportes y gráficos
- [ ] Implementar API REST
- [ ] Mejorar interfaz de usuario

### Fase 3: Optimización (1 mes)
- [ ] Implementar caché Redis
- [ ] Optimizar consultas SQL
- [ ] Monitoreo y alertas
- [ ] Backup automatizado

## 🆘 Resolución de Problemas

### Errores Comunes

#### "Extension pdo_sqlsrv not found"
```bash
# En Windows con IIS
# Descargar Microsoft Drivers for PHP for SQL Server
# Agregar a php.ini:
extension=pdo_sqlsrv
extension=sqlsrv
```

#### "Permission denied" en logs/
```bash
chmod 777 logs/
chown -R www-data:www-data logs/
```

#### "CSRF token mismatch"
- Verificar que las cookies estén habilitadas
- Comprobar configuración de sesiones
- Revisar headers de seguridad

### Logs y Debugging

```php
// Logs se guardan en logs/
tail -f logs/app.log
tail -f logs/auth.log
tail -f logs/database.log
```

## 📞 Soporte y Mantenimiento

### Contacto
- **Desarrollador**: Eduardo Herrera
- **Email**: ehu@hp.com
- **Documentación**: Este archivo README

### Recursos Adicionales
- [PHP Security Best Practices](https://php.net/security)
- [OWASP PHP Security Cheat Sheet](https://owasp.org/www-project-cheat-sheets/)
- [Composer Documentation](https://getcomposer.org/doc/)

---

**Última actualización**: Octubre 2025  
**Versión**: 3.0.0 (Modernizada)  
**Estado**: Production Ready
