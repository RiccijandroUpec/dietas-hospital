# 🚀 Guía de Despliegue - Sistema de Dietas Hospital

## 📋 Descripción

Esta guía te ayudará a desplegar la aplicación Laravel en Railway con la configuración optimizada.

## ⚡ Mejoras Implementadas

### Nuevo Sistema de Despliegue

1. **nixpacks.toml**: Configuración optimizada de build con Nixpacks
2. **start.sh**: Script de inicio automatizado que maneja:
   - Migraciones automáticas
   - Creación de tabla de sesiones
   - Seeding de usuarios
   - Reseteo de contraseñas
   - Optimización de cache
   - Enlaces de storage
3. **railway.json**: Configuración mejorada con health checks
4. **Procfile**: Soporte para múltiples procesos

### Beneficios

- ✅ Deploy más rápido y confiable
- ✅ Optimización automática de producción
- ✅ Mejor manejo de dependencias
- ✅ Health checks configurados
- ✅ Caché optimizado para producción
- ✅ Manejo automático de errores
- ✅ Setup automático de base de datos

## 🚀 Despliegue en Railway

### 1. Crear Proyecto en Railway

1. Ve a [railway.app](https://railway.app)
2. Click en "New Project"
3. Selecciona "Deploy from GitHub repo"
4. Conecta tu repositorio `dietas-hospital`
5. Railway detectará automáticamente que es un proyecto Laravel

### 2. Agregar Base de Datos PostgreSQL

1. En tu proyecto de Railway, click en "New"
2. Selecciona "Database" → "Add PostgreSQL"
3. Railway automáticamente configurará las variables de entorno:
   - `PGHOST`
   - `PGPORT`
   - `PGDATABASE`
   - `PGUSER`
   - `PGPASSWORD`

### 3. Configurar Variables de Entorno

En Railway → Tu Proyecto → Variables, agrega las siguientes variables:

```env
# ========================================
# Aplicación
# ========================================
APP_NAME=Sistema de Dietas
APP_ENV=production
APP_KEY=base64:YAqPhkBk2Q85JXuRYjhEE0jjZbDMUcgXeauV/VgreqE=
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_TIMEZONE=America/Guayaquil

# ========================================
# Base de Datos (Railway las provee automáticamente)
# ========================================
DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

# ========================================
# Sesiones (CRÍTICO para evitar error 419)
# ========================================
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SAME_SITE=lax

# ========================================
# Cache y Queue
# ========================================
CACHE_STORE=database
QUEUE_CONNECTION=database

# ========================================
# Logging
# ========================================
LOG_CHANNEL=stack
LOG_LEVEL=error
```

**⚠️ IMPORTANTE:**
- Reemplaza `your-app-name` en `APP_URL` con tu dominio real de Railway
- Si no tienes `APP_KEY`, genera una con: `php artisan key:generate --show`
- **NO** configures `SESSION_DOMAIN` ni `SESSION_SECURE_COOKIE` (déjalas sin definir)

### 4. Deploy Automático

Una vez configuradas las variables:

1. Railway desplegará automáticamente
2. El script `start.sh` se ejecutará y:
   - ✅ Ejecutará las migraciones
   - ✅ Creará la tabla de sesiones
   - ✅ Creará los usuarios por defecto
   - ✅ Optimizará el cache
3. La aplicación estará lista para usar

### 5. Verificar Deployment

Visita tu URL de Railway: `https://your-app-name.up.railway.app`

Deberías ver la página de login del sistema.

## 👥 Usuarios Creados Automáticamente

El sistema crea estos usuarios por defecto:

| Email | Contraseña | Rol |
|-------|------------|-----|
| admin@hospital.com | 123456 | admin |
| nutricionista@hospital.com | 123456 | nutricionista |
| enfermero@hospital.com | 123456 | enfermero |
| usuario@hospital.com | 123456 | usuario |

**⚠️ IMPORTANTE:** Cambia estas contraseñas después del primer login en producción.

## 🔧 Comandos Útiles (Railway CLI)

### Instalar Railway CLI

```bash
npm i -g @railway/cli
```

### Comandos Básicos

```bash
# Login
railway login

# Conectar al proyecto
railway link

# Ver logs en tiempo real
railway logs

# Ejecutar comando en producción
railway run php artisan tinker

# Conectar a la base de datos
railway connect postgres

# Ver variables de entorno
railway variables
```

### Comandos de Mantenimiento

```bash
# Limpiar caché
railway run php artisan cache:clear

# Resetear contraseñas
railway run php artisan users:reset-passwords

# Ejecutar migraciones
railway run php artisan migrate --force

# Crear nuevos usuarios
railway run php artisan db:seed --class=UsersSeeder
```

## 🔄 Actualizar Producción

### Despliegue Automático

Railway redespliega automáticamente cuando haces push a la rama principal:

```bash
git add .
git commit -m "feat: nueva funcionalidad"
git push origin main
```

### Despliegue Manual

1. Ve a Railway Dashboard
2. Tu Proyecto → Deployments
3. Click en "Deploy"

## 🐛 Solución de Problemas

### Error 419 - CSRF Token Mismatch

**Solución:**

1. Verifica que `SESSION_DRIVER=database`
2. Asegúrate de que `SESSION_DOMAIN` y `SESSION_SECURE_COOKIE` NO estén definidas
3. Verifica que `APP_URL` sea correcta
4. Borra cookies del navegador
5. Verifica que la tabla `sessions` existe:
   ```bash
   railway run php artisan migrate --force
   ```

Ver [ERROR-419.md](ERROR-419.md) para más detalles.

### Error: "This password does not use the Bcrypt algorithm"

**Solución:**

```bash
railway run php artisan users:reset-passwords
```

### La aplicación no inicia

**Solución:**

1. Ver logs:
   ```bash
   railway logs
   ```

2. Verificar que todas las variables de entorno estén configuradas

3. Verificar que la base de datos esté conectada

4. Ejecutar migraciones manualmente:
   ```bash
   railway run php artisan migrate --force
   ```

### Assets no se cargan (CSS/JS)

**Solución:**

1. Verificar que `npm run build` se ejecutó en el build
2. Verificar que `APP_URL` sea correcta
3. Forzar rebuild:
   ```bash
   git commit --allow-empty -m "Trigger rebuild"
   git push
   ```

### Base de datos no se conecta

**Solución:**

1. Verificar que las variables de PostgreSQL existan:
   ```bash
   railway variables
   ```

2. Verificar que `DB_CONNECTION=pgsql`

3. Intentar reconectar la base de datos en Railway Dashboard

## 📊 Monitoreo

### Ver Logs

```bash
# Logs en tiempo real
railway logs

# Logs de errores
railway logs --filter error
```

### Métricas

Railway Dashboard → Tu Proyecto → Metrics muestra:
- CPU usage
- Memory usage
- Network traffic
- Request count

## 🔐 Seguridad

### Checklist de Seguridad

- [ ] `APP_DEBUG=false` en producción
- [ ] `APP_ENV=production`
- [ ] Contraseñas por defecto cambiadas
- [ ] `APP_KEY` es única y segura
- [ ] HTTPS habilitado (Railway lo provee automáticamente)
- [ ] Variables de entorno sensibles no están en el código
- [ ] Sesiones configuradas correctamente

### Cambiar APP_KEY

Si necesitas regenerar la APP_KEY:

```bash
# Generar nueva key
railway run php artisan key:generate --show

# Copiar el output y actualizar en Railway Variables
# Luego redeploy
```

## 📝 Arquitectura de Despliegue

```
┌─────────────────────────────────────┐
│         Railway Platform            │
├─────────────────────────────────────┤
│                                     │
│  ┌──────────────┐  ┌─────────────┐ │
│  │   Laravel    │  │ PostgreSQL  │ │
│  │   App        │──│  Database   │ │
│  └──────────────┘  └─────────────┘ │
│         │                           │
│  ┌──────▼───────┐                  │
│  │  start.sh    │                  │
│  │  - Migrate   │                  │
│  │  - Seed      │                  │
│  │  - Cache     │                  │
│  └──────────────┘                  │
│                                     │
└─────────────────────────────────────┘
         │
         │ HTTPS
         ▼
   User Browser
```

## 🎯 Mejores Prácticas

1. **Variables de Entorno**: Nunca pongas credenciales en el código
2. **Migraciones**: Siempre usa `--force` en producción
3. **Cache**: Limpia el cache antes de optimizar
4. **Logs**: Usa `LOG_LEVEL=error` en producción
5. **Backups**: Railway hace backups automáticos de la DB
6. **Testing**: Prueba en local antes de deploy
7. **Monitoring**: Revisa logs regularmente

## 📚 Recursos Adicionales

- [Railway Docs](https://docs.railway.app/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Nixpacks](https://nixpacks.com/)

## ✅ Checklist de Deployment

Pre-deployment:
- [ ] Código en repositorio GitHub
- [ ] `.env.example` actualizado
- [ ] Migraciones probadas localmente
- [ ] Assets compilados (`npm run build`)

Deployment:
- [ ] Proyecto creado en Railway
- [ ] PostgreSQL agregado
- [ ] Variables de entorno configuradas
- [ ] Deploy exitoso
- [ ] Migraciones ejecutadas

Post-deployment:
- [ ] Login funciona
- [ ] Usuarios creados
- [ ] Assets se cargan correctamente
- [ ] No hay errores en logs
- [ ] Cambiar contraseñas por defecto

---

**¿Problemas?** Revisa los logs con `railway logs` o consulta [ERROR-419.md](ERROR-419.md) para problemas de sesión.

