# Guía de Testing - TICO Modernizado

## Credenciales de Acceso

### Usuario Administrador
- **Username:** `admin`
- **Password:** `admin123`
- **Role:** admin
- **Email:** admin@tico.local
- **Team:** Selecciona cualquier team disponible (Support, IT, Engineering, QA, Management)

**Nota:** El team seleccionado en el login es solo para la sesión actual, no modifica el perfil del usuario.

### Usuarios de Prueba
- **engineer1** / password: `admin123` (Ingeniero)
- **user1** / password: `admin123` (Usuario regular)
- **sarah_tech** / password: `admin123` (Ingeniero)
- **mike_senior** / password: `admin123` (Ingeniero Senior)

## URLs de Acceso

- **Aplicación Principal:** http://localhost:8080
- **Login:** http://localhost:8080/login.php
- **Health Check:** http://localhost:8080/health.php
- **Adminer (DB Admin):** http://localhost:8081
  - Sistema: PostgreSQL
  - Servidor: postgres
  - Usuario: tico_user
  - Password: tico_password
  - Base de datos: tico_db

## Casos de Prueba

### 1. Login y Logout ✅

**Objetivo:** Verificar que el login funciona y el botón de logout está visible

**Pasos:**
1. Ir a http://localhost:8080
2. Debería redirigir a `/login.php`
3. Ingresar credenciales: `admin` / `admin123`
4. Seleccionar **cualquier Team** del dropdown (Support, IT, Engineering, QA, o Management)
5. Click en "Sign In"
6. Verificar que se muestra el dashboard
7. **IMPORTANTE:** Buscar el botón "Logout" arriba a la derecha (junto al nombre de usuario)
8. Click en botón "Logout"
9. Verificar redirección a login
10. Verificar que sesión fue destruida

**Nota:** El sistema ahora permite login con cualquier team. La validación es solo de username y password.

**Resultado Esperado:**
- ✅ Login exitoso
- ✅ Botón de logout visible en header
- ✅ Logout funciona correctamente

### 2. Health Check ✅

**Objetivo:** Verificar que todos los componentes del sistema están funcionando

**Pasos:**
1. Ir a http://localhost:8080/health.php
2. Verificar respuesta JSON

**Resultado Esperado:**
```json
{
    "status": "healthy",
    "checks": {
        "config": {"status": "ok"},
        "database": {"status": "ok"},
        "directory_templates_c": {"status": "ok"},
        "directory_logs": {"status": "ok"},
        "php_version": {"status": "ok"},
        "extension_pdo": {"status": "ok"},
        "extension_pdo_pgsql": {"status": "ok"},
        "extension_openssl": {"status": "ok"},
        "extension_json": {"status": "ok"}
    }
}
```

### 3. Navegación de Módulos ✅

**Objetivo:** Verificar que todos los módulos cargan correctamente

**Pasos:**
1. Login como admin
2. Navegar por los siguientes módulos:
   - Dashboard (página principal)
   - Cases (gestión de casos)
   - Engineers (lista de ingenieros)
   - News (noticias/anuncios)
3. Verificar que no hay errores 404 o errores de carga de assets

**Resultado Esperado:**
- ✅ Todos los módulos cargan sin errores
- ✅ CSS y JavaScript se cargan correctamente
- ✅ No hay errores en consola del navegador

### 4. Assets Estáticos ✅

**Objetivo:** Verificar que los assets (CSS, JS, imágenes) se sirven correctamente

**Pasos:**
1. Abrir navegador y Developer Tools (F12)
2. Ir a http://localhost:8080
3. En la pestaña "Network" verificar que:
   - Archivos CSS se cargan desde `/assets/css/`
   - Archivos JS se cargan desde `/assets/js/`
   - Status code 200 para todos los assets
   - No hay errores 404

**Resultado Esperado:**
- ✅ Todos los assets cargan con status 200
- ✅ No hay errores 404
- ✅ El diseño visual se muestra correctamente

### 5. Base de Datos PostgreSQL ✅

**Objetivo:** Verificar la conexión y datos en PostgreSQL

**Pasos:**
1. Ir a http://localhost:8081 (Adminer)
2. Login con credenciales:
   - Sistema: PostgreSQL
   - Servidor: postgres
   - Usuario: tico_user
   - Password: tico_password
   - Base de datos: tico_db
3. Verificar tablas existentes:
   - users
   - cases
   - teams
   - products
   - schedule_exceptions
   - news
   - sessions
4. Consultar datos: `SELECT * FROM users LIMIT 5;`

**Resultado Esperado:**
- ✅ Conexión exitosa a PostgreSQL
- ✅ Todas las tablas están presentes
- ✅ Datos de prueba cargados correctamente

### 6. Docker Containers ✅

**Objetivo:** Verificar que todos los contenedores están corriendo correctamente

**Comando:**
```bash
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}" | grep tico
```

**Resultado Esperado:**
```
NAMES                     STATUS              PORTS
tico_dev_2-nginx-1        Up X minutes        0.0.0.0:8080->80/tcp
tico_dev_2-adminer-1      Up X minutes        0.0.0.0:8081->8080/tcp
tico_dev_2-php-fpm-1      Up X minutes        9000/tcp
tico_dev_2-postgres-1     Up X minutes        0.0.0.0:5432->5432/tcp
```

### 7. Logs de Nginx y PHP-FPM ✅

**Objetivo:** Verificar que no hay errores en los logs

**Comandos:**
```bash
# Logs de Nginx
docker logs tico_dev_2-nginx-1 --tail 50

# Logs de PHP-FPM
docker logs tico_dev_2-php-fpm-1 --tail 50
```

**Resultado Esperado:**
- ✅ No hay errores críticos
- ✅ Requests se procesan correctamente
- ✅ PHP-FPM responde sin errores

## Comandos Útiles

### Reiniciar servicios
```bash
docker-compose restart
```

### Ver estado de contenedores
```bash
docker-compose ps
```

### Reconstruir imagen PHP-FPM
```bash
docker-compose down
docker-compose build --no-cache php-fpm
docker-compose up -d
```

### Acceder a contenedor PHP-FPM
```bash
docker exec -it tico_dev_2-php-fpm-1 bash
```

### Acceder a PostgreSQL
```bash
docker exec -it tico_dev_2-postgres-1 psql -U tico_user -d tico_db
```

### Limpiar logs
```bash
docker-compose down
rm -rf logs/nginx/*
docker-compose up -d
```

## Checklist de Verificación Completo

```markdown
- [x] ✅ Nginx + PHP-FPM migrado y funcionando
- [x] ✅ PostgreSQL 18 configurado y conectado
- [x] ✅ Health check retorna "healthy"
- [x] ✅ Login funciona con credenciales correctas
- [x] ✅ Botón de logout visible en header
- [x] ✅ Logout destruye sesión correctamente
- [x] ✅ Archivos legacy movidos a old_stuf/
- [x] ✅ Estructura optimizada (public/, src/, views/)
- [x] ✅ Assets se sirven correctamente desde /assets/
- [x] ✅ Todos los contenedores Docker corriendo
- [x] ✅ Variables de entorno configuradas correctamente
- [x] ✅ No hay errores en logs de Nginx
- [x] ✅ No hay errores en logs de PHP-FPM
```

## Problemas Conocidos y Soluciones

### Problema: "Connection to localhost refused"
**Solución:** Las variables de entorno deben estar en `$_SERVER` en PHP-FPM. Usar función `$env()` helper en Config.php

### Problema: "chmod: cannot access '/var/www/html/logs'"
**Solución:** Agregar `mkdir -p /var/www/html/logs /var/www/html/views/templates_c` en Dockerfile antes de chmod

### Problema: "AUTOINCREMENT syntax error"
**Solución:** Scripts de seed usan sintaxis SQLite. Para PostgreSQL usar `SERIAL` o `GENERATED ALWAYS AS IDENTITY`

### Problema: Assets 404
**Solución:** Verificar que nginx.conf tiene:
```nginx
location /assets/ {
    alias /var/www/html/public/assets/;
}
```

## Contacto de Soporte

Para reportar problemas o sugerencias:
- Revisar logs: `docker logs tico_dev_2-php-fpm-1`
- Verificar health: http://localhost:8080/health.php
- Acceder a Adminer: http://localhost:8081

