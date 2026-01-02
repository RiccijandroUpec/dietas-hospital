# Guía de Despliegue - Sistema de Dietas Hospital

## 📋 Problema Resuelto

### Error: "This password does not use the Bcrypt algorithm"

Este error ocurre cuando las contraseñas en la base de datos no están correctamente hasheadas con Bcrypt.

### Solución

Se han creado las siguientes herramientas:

1. **Comando Artisan**: `users:reset-passwords` (solo reseta en primer deploy)
2. **Seeder**: `UsersSeeder`

## 🚀 Pasos para Despliegue en Railway

### 1. Configurar Variables de Entorno en Railway

Entra a tu proyecto en Railway → Settings → Variables y agrega:

```env
APP_NAME=Sistema de Dietas
APP_ENV=production
APP_KEY=base64:YAqPhkBk2Q85JXuRYjhEE0jjZbDMUcgXeauV/VgreqE=
APP_DEBUG=false
APP_URL=https://tu-dominio.up.railway.app

# Sesiones - IMPORTANTE
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Base de datos PostgreSQL (Railway provee automáticamente)
DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

# Cache y Queue
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 2. Después del Deploy (Primer Deploy)

Una vez que Railway haya desplegado tu aplicación por primera vez, el comando `users:reset-passwords` se ejecutará automáticamente y reseteará todas las contraseñas.

**✓ Nota**: En los próximos deploys, las contraseñas NO serán reseteadas automáticamente. Se creará un marcador (`.deploy-initialized`) que evita resetear contraseñas en futuras actualizaciones.

#### Si necesitas resetear contraseñas nuevamente

Para resetear contraseñas manualmente en Railway (después del primer deploy):

```bash
# Instalar Railway CLI si no lo tienes
npm i -g @railway/cli

# Login
railway login

# Conectar al proyecto
railway link

# Resetear contraseñas con force flag
railway run php artisan users:reset-passwords --force
```

### 3. Usuarios Creados

Después del primer deploy, tendrás estos usuarios disponibles:

| Email | Contraseña | Rol |
|-------|------------|-----|
| admin@hospital.com | 123456 | admin |
| nutricionista@hospital.com | 123456 | nutricionista |
| enfermero@hospital.com | 123456 | enfermero |
| usuario@hospital.com | 123456 | usuario |

⚠️ **IMPORTANTE**: Cambia estas contraseñas después del primer login en producción.

## 🔧 Desarrollo Local

### Resetear Contraseñas

Si ya tienes usuarios y recibes el error de Bcrypt:

```bash
php artisan users:reset-passwords
```

Con contraseña personalizada:

```bash
php artisan users:reset-passwords --password=micontraseña
```

Si necesitas forzar un reset (por ejemplo, después de múltiples deploys locales):

```bash
php artisan users:reset-passwords --force
```

### Crear Usuarios Iniciales

```bash
php artisan db:seed --class=UsersSeeder
```

### Fresh Install

```bash
php artisan migrate:fresh --seed
```

## 📝 Notas Importantes

1. **Contraseñas Hasheadas**: Todas las contraseñas DEBEN ser hasheadas con `Hash::make()` o `bcrypt()`
2. **Sesiones en Producción**: Usar `SESSION_DRIVER=database` con `SESSION_SECURE_COOKIE=true`
3. **HTTPS Obligatorio**: Railway provee HTTPS automáticamente
4. **APP_KEY**: Debe estar configurada (se genera con `php artisan key:generate`)
5. **Marcador de Deploy**: El archivo `app/.deploy-initialized` controla el reset automático (no se trackea en git)

## 🐛 Solución de Problemas

### Las contraseñas se resetan en cada deploy

Este problema ha sido solucionado. El comando ahora solo reseta en el primer deploy. Si quieres resetear de nuevo:

```bash
# En desarrollo
php artisan users:reset-passwords --force

# En producción (Railway)
railway run php artisan users:reset-passwords --force
```

### Error: "This password does not use the Bcrypt algorithm"

```bash
# Ejecutar en Railway
railway run php artisan users:reset-passwords
```

### Error: "CSRF token mismatch"

Verificar que estas variables estén configuradas:
- `SESSION_DRIVER=database`
- `SESSION_SECURE_COOKIE=true`
- `APP_URL` debe coincidir con tu dominio de Railway

### No puedo iniciar sesión después del deploy

1. Verifica que las migraciones se hayan ejecutado
2. Ejecuta el seeder de usuarios si es necesario
3. Limpia la caché del navegador
4. Verifica que la URL en `APP_URL` sea correcta

## 🔄 Actualizar Producción

```bash
git add .
git commit -m "Fix: Las contraseñas solo se resetan en primer deploy"
git push origin main
```

Railway redesplegará automáticamente.
