# Guía de Despliegue - Sistema de Dietas Hospital

## 📋 Problema Resuelto

### Error: "This password does not use the Bcrypt algorithm"

Este error ocurre cuando las contraseñas en la base de datos no están correctamente hasheadas con Bcrypt.

### Solución

Se han creado las siguientes herramientas:

1. **Comando Artisan**: `users:reset-passwords`
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

### 2. Después del Deploy

Una vez que Railway haya desplegado tu aplicación, necesitas ejecutar comandos para configurar usuarios:

#### Opción A: Desde Railway CLI

```bash
# Instalar Railway CLI si no lo tienes
npm i -g @railway/cli

# Login
railway login

# Conectar al proyecto
railway link

# Ejecutar comando para resetear contraseñas
railway run php artisan users:reset-passwords

# O ejecutar el seeder de usuarios
railway run php artisan db:seed --class=UsersSeeder
```

#### Opción B: Agregar al comando de inicio

Edita el archivo `railway.json` para incluir el seeder en el deploy:

```json
{
  "build": {
    "builder": "nixpacks"
  },
  "deploy": {
    "startCommand": "php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear && php artisan migrate --force && php artisan db:seed --class=UsersSeeder --force && php artisan config:cache && php artisan route:cache && php -S 0.0.0.0:${PORT:-8080} -t public"
  }
}
```

### 3. Usuarios Creados

Después de ejecutar el seeder, tendrás estos usuarios disponibles:

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

## 🐛 Solución de Problemas

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
2. Ejecuta el seeder de usuarios
3. Limpia la caché del navegador
4. Verifica que la URL en `APP_URL` sea correcta

## 🔄 Actualizar Producción

```bash
git add .
git commit -m "Fix: Passwords y sesiones para producción"
git push origin main
```

Railway redesplegará automáticamente.
