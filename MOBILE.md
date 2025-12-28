# 📱 Configuración para Móviles en Railway

## Problema: No puedo iniciar sesión desde el celular

Si no puedes iniciar sesión o registrarte desde un dispositivo móvil, el problema está en la configuración de cookies.

## Solución: Configurar Variables de Entorno en Railway

Ve a tu proyecto en Railway → **Settings** → **Variables** y asegúrate de tener estas variables:

### Variables Críticas para Móviles:

```env
# Sesiones - CRUCIAL para móviles
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
SESSION_HTTP_ONLY=true
SESSION_LIFETIME=120

# APP
APP_URL=https://tu-dominio.up.railway.app
APP_ENV=production
APP_DEBUG=false
```

### ¿Por qué SESSION_SAME_SITE=none?

Los navegadores móviles (Safari, Chrome mobile) tienen restricciones más estrictas con cookies. `SESSION_SAME_SITE=none` permite que las cookies funcionen correctamente en:
- Navegadores móviles
- Peticiones cross-site
- PWAs (Progressive Web Apps)

**Nota:** `SESSION_SAME_SITE=none` REQUIERE que `SESSION_SECURE_COOKIE=true` (HTTPS obligatorio).

## Pasos para Configurar:

### 1. En Railway Dashboard:

1. Abre tu proyecto en Railway
2. Ve a **Settings** → **Variables**
3. Agrega/actualiza estas variables:

```
SESSION_SAME_SITE=none
SESSION_SECURE_COOKIE=true
SESSION_DRIVER=database
SESSION_HTTP_ONLY=true
APP_URL=https://tu-url-railway.up.railway.app
```

### 2. Verificar APP_URL:

**MUY IMPORTANTE:** El `APP_URL` debe ser EXACTAMENTE tu URL de Railway:

```env
APP_URL=https://dietas-hospital-production.up.railway.app
```

No uses:
- ❌ `http://...` (sin SSL)
- ❌ URLs con puertos
- ❌ localhost
- ❌ URLs temporales de ngrok

### 3. Redesplegar:

Después de cambiar las variables, Railway redesplegará automáticamente.

## Registro de Usuarios

El registro está habilitado en la ruta `/register`. Puedes:

1. **Registrar un usuario nuevo:**
   - Ir a: `https://tu-url.up.railway.app/register`
   - Llenar el formulario
   - Automáticamente tendrás rol "usuario"

2. **Usar usuarios pre-creados:**
   - Email: `admin@hospital.com` - Contraseña: `123456`
   - Email: `nutricionista@hospital.com` - Contraseña: `123456`
   - Email: `enfermero@hospital.com` - Contraseña: `123456`

## Verificación de Problemas

### Test 1: Ver la URL en el navegador
Verifica que estés accediendo a: `https://...` (con S)

### Test 2: Limpiar caché del navegador móvil
En Safari (iOS):
- Ajustes → Safari → Borrar historial y datos

En Chrome (Android):
- Configuración → Privacidad → Borrar datos de navegación

### Test 3: Modo incógnito
Prueba en una ventana de incógnito/privada

## Configuración Completa de Variables en Railway:

```env
# App
APP_NAME="Sistema de Dietas"
APP_ENV=production
APP_KEY=base64:TU_APP_KEY_DE_RAILWAY
APP_DEBUG=false
APP_URL=https://tu-url-railway.up.railway.app

# Database (Railway PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

# Sesiones para móviles
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=none

# Cache
CACHE_STORE=database
CACHE_PREFIX=

# Queue
QUEUE_CONNECTION=database
```

## Si aún no funciona:

1. Verifica los logs en Railway: **Deployments** → **View Logs**
2. Asegúrate que la migración de sessions se ejecutó correctamente
3. Verifica que exista la tabla `sessions` en la base de datos
4. Prueba desde otro dispositivo móvil

## Alternativa: Usar SESSION_SAME_SITE=lax

Si `none` causa problemas, puedes probar:

```env
SESSION_SAME_SITE=lax
```

Esto funciona para la mayoría de casos, pero puede fallar en algunos navegadores móviles antiguos.
