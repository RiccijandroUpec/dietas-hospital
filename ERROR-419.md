# 🔒 Solución Error 419 - CSRF Token Mismatch

## ❌ Problema: Error 419 al iniciar sesión

El error 419 ocurre cuando el token CSRF no coincide, generalmente por problemas de sesiones.

## ✅ Solución en Railway

### 1. Configurar Variables de Entorno CRÍTICAS

En Railway → Variables, configura **EXACTAMENTE** estas variables:

```env
APP_URL=https://dietas-hospital-production.up.railway.app
SESSION_DRIVER=database
SESSION_DOMAIN=
SESSION_SECURE_COOKIE=
SESSION_SAME_SITE=lax
SESSION_LIFETIME=120
```

**IMPORTANTE:** 
- `SESSION_DOMAIN` debe estar vacío (sin valor) o no existir
- `SESSION_SECURE_COOKIE` debe estar vacío (sin valor) o no existir
- NO uses `null` ni `"null"`, déjalos VACÍOS o elimínalos

### 2. Eliminar variables incorrectas (si existen)

Si tienes estas variables, **ELIMÍNALAS** en Railway:
- SESSION_DOMAIN=null
- SESSION_SECURE_COOKIE=true
- SESSION_SECURE_COOKIE=false

### 3. Verificar APP_URL

Debe coincidir EXACTAMENTE con tu URL de Railway:

```env
APP_URL=https://dietas-hospital-production.up.railway.app
```

Sin:
- ❌ Barra final: `https://...app/`
- ❌ HTTP: `http://...`
- ❌ Puerto: `https://...:8080`

### 4. Limpiar navegador

Después de configurar Railway:

1. **Borra todas las cookies del sitio:**
   - Chrome: F12 → Application → Cookies → Eliminar todo
   - Firefox: F12 → Storage → Cookies → Eliminar todo
   - Safari: Preferencias → Privacidad → Gestionar datos → Eliminar

2. **Cierra completamente el navegador**

3. **Abre de nuevo** y ve a tu sitio

## 🎯 Configuración Completa Recomendada

Variables que DEBES tener en Railway:

```env
# App
APP_NAME=Sistema de Dietas
APP_ENV=production
APP_KEY=base64:YAqPhkBk2Q85JXuRYjhEE0jjZbDMUcgXeauV/VgreqE=
APP_DEBUG=false
APP_URL=https://dietas-hospital-production.up.railway.app

# Database
DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

# Sesiones
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SAME_SITE=lax

# Cache
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## 🔍 Verificación

Para verificar que funciona:

1. Abre el navegador en **modo incógnito**
2. Ve a: `https://dietas-hospital-production.up.railway.app/login`
3. Abre las **Herramientas de Desarrollo** (F12)
4. Ve a la pestaña **Network** (Red)
5. Intenta iniciar sesión
6. Busca la petición POST a `/login`
7. Verifica que:
   - Status code NO sea 419
   - Las cookies se están guardando

## 🚨 Si aún tienes error 419

### Opción 1: Cambiar SESSION_SAME_SITE

```env
SESSION_SAME_SITE=none
SESSION_SECURE_COOKIE=true
```

⚠️ IMPORTANTE: Si usas `none`, DEBES usar `SESSION_SECURE_COOKIE=true`

### Opción 2: Usar file driver temporalmente

```env
SESSION_DRIVER=file
```

⚠️ No recomendado para producción con múltiples instancias

### Opción 3: Verificar tabla sessions

Conéctate a tu base de datos PostgreSQL en Railway y verifica que existe la tabla `sessions`:

```sql
SELECT * FROM sessions LIMIT 1;
```

Si no existe, ejecuta:

```bash
php artisan migrate --force
```

## 📱 Para Móviles

Si el error 419 solo ocurre en móviles, usa:

```env
SESSION_SAME_SITE=none
SESSION_SECURE_COOKIE=true
```

## ✅ Checklist

- [ ] APP_URL configurada correctamente
- [ ] SESSION_DRIVER=database
- [ ] SESSION_DOMAIN vacío o eliminado
- [ ] SESSION_SECURE_COOKIE vacío o eliminado
- [ ] Tabla sessions existe en la base de datos
- [ ] Railway redespliegado
- [ ] Cookies del navegador borradas
- [ ] Probado en modo incógnito

Una vez que configures todo, Railway redesplegará automáticamente y el login funcionará. 🎉
