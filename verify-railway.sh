#!/bin/bash

echo "🔍 Verificando configuración para Railway..."
echo ""
echo "APP_URL: $APP_URL"
echo "SESSION_DRIVER: $SESSION_DRIVER"
echo "SESSION_SAME_SITE: $SESSION_SAME_SITE"
echo "SESSION_SECURE_COOKIE: $SESSION_SECURE_COOKIE"
echo "DB_CONNECTION: $DB_CONNECTION"
echo ""

# Verificar que APP_URL esté configurada
if [ -z "$APP_URL" ]; then
    echo "⚠️  WARNING: APP_URL no está configurada!"
fi

# Verificar que SESSION_SAME_SITE esté configurada
if [ -z "$SESSION_SAME_SITE" ]; then
    echo "⚠️  WARNING: SESSION_SAME_SITE no está configurada! Usando 'lax' por defecto"
fi

echo "✅ Verificación completa"
