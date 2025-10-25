# Migración a Nginx + PHP-FPM

## Resumen

El proyecto TICO ha sido migrado exitosamente de Apache + mod_php a Nginx + PHP-FPM.

## Arquitectura Actual

### Servicios Docker

1. **nginx** (puerto 8080)
   - Servidor web Nginx Alpine
   - Maneja peticiones HTTP y sirve contenido estático
   - Proxy reverso para PHP-FPM

2. **php-fpm** (puerto interno 9000)
   - PHP 8.2-FPM
   - Procesa archivos PHP
   - Extensiones: PDO, PDO_PGSQL, PGSQL, GD, MBString, XML, ZIP, BCMath

3. **postgres** (puerto 5432)
   - PostgreSQL 18
   - Base de datos principal

4. **adminer** (puerto 8081)
   - Interfaz de administración de base de datos

## Ventajas de Nginx + PHP-FPM

### Rendimiento
- ✅ Mejor manejo de concurrencia (arquitectura event-driven asíncrona)
- ✅ Menor uso de memoria
- ✅ Procesamiento más rápido de contenido estático
- ✅ Escalabilidad mejorada bajo alta carga

### Arquitectura
- ✅ Separación de responsabilidades (web server vs PHP processor)
- ✅ Posibilidad de escalar PHP-FPM independientemente
- ✅ Mejor aislamiento de procesos
- ✅ Configuración más moderna y mantenible

### Operaciones
- ✅ Reiniciar PHP sin afectar Nginx
- ✅ Logs separados por servicio
- ✅ Monitoreo granular de cada componente

## Configuración

### Nginx (nginx.conf)
```nginx
server {
    listen 80;
    root /var/www/html/public;
    index index.php;
    
    location ~ \.php$ {
        fastcgi_pass php-fpm:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### PHP-FPM (Dockerfile)
- Base: `php:8.2-fpm`
- Extensiones PostgreSQL: pdo_pgsql, pgsql
- Configuración PHP optimizada para producción
- Límites: upload_max_filesize=100M, memory_limit=256M

## Comandos Útiles

### Iniciar servicios
```bash
docker-compose up -d
```

### Ver logs
```bash
docker logs tico_dev_2-nginx-1
docker logs tico_dev_2-php-fpm-1
```

### Reiniciar servicios
```bash
docker restart tico_dev_2-nginx-1
docker restart tico_dev_2-php-fpm-1
```

### Verificar salud del sistema
```bash
curl http://localhost:8080/health.php
```

## Acceso

- **Aplicación Web**: http://localhost:8080
- **Adminer (DB Admin)**: http://localhost:8081
- **PostgreSQL**: localhost:5432

## Archivos Importantes

- `nginx.conf` - Configuración de Nginx
- `Dockerfile` - Imagen PHP-FPM personalizada
- `docker-compose.yml` - Orquestación de servicios
- `.env` - Variables de entorno

## Migrado desde Apache

Los archivos de configuración de Apache se han movido a `old_stuf/`:
- `apache-config.conf` - Configuración VirtualHost de Apache (obsoleta)

## Estado Actual

✅ Nginx + PHP-FPM funcionando correctamente
✅ Conexión a PostgreSQL 18 verificada
✅ Health check: HEALTHY
✅ Interfaz web accesible
✅ Sesiones PHP funcionando
✅ Headers de seguridad aplicados

## Fecha de Migración

24 de Octubre, 2025
