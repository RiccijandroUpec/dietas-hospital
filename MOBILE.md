# 📱 Configuración para Móviles en Railway

## ⚠️ Problema: No puedo iniciar sesión desde el celular

Si no puedes iniciar sesión o registrarte desde un dispositivo móvil, sigue estos pasos:

## ✅ Solución Paso a Paso

### 1. Configurar Variables de Entorno en Railway

**IMPORTANTE:** Ve a Railway → Tu proyecto → **Variables** y configura EXACTAMENTE estas variables:

```plaintext
APP_URL=https://dietas-hospital-production.up.railway.app
APP_ENV=production
APP_DEBUG=false
SESSION_DRIVER=database
SESSION_SAME_SITE=lax
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_LIFETIME=120
CACHE_STORE=database
```

### 2. Verificar APP_URL

**MUY IMPORTANTE:** El `APP_URL` debe ser EXACTAMENTE tu URL de Railway.

Para encontrar tu URL:
1. Railway Dashboard → Tu proyecto
2. Copia la URL que aparece arriba (ejemplo: `https://dietas-hospital-production.up.railway.app`)
3. Pégala en `APP_URL` (CON https://)

### 3. Después de configurar

Railway redesplegará automáticamente. Espera 2-3 minutos.

### 4. En tu móvil

1. **Borra la caché del navegador:**
   - Safari (iOS): Ajustes → Safari → Borrar historial y datos
   - Chrome (Android): ⋮ → Historial → Borrar datos de navegación

2. **Cierra completamente el navegador** (no solo la pestaña)

3. **Abre el navegador de nuevo** y ve a tu URL de Railway

4. Prueba iniciar sesión con:
   - Email: `admin@hospital.com`
   - Contraseña: `123456`

## 🔧 Si AÚN no funciona

### Opción A: Cambiar SESSION_SAME_SITE a 'none'

En Railway Variables, cambia:
```
SESSION_SAME_SITE=none
```

**NOTA:** Esto requiere `SESSION_SECURE_COOKIE=true` (que ya tienes).

### Opción B: Verificar en modo incógnito

Abre el navegador en modo incógnito/privado y prueba de nuevo.

### Opción C: Usar otro navegador

- Si estás en iPhone: Prueba Chrome en lugar de Safari
- Si estás en Android: Prueba Firefox en lugar de Chrome

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
