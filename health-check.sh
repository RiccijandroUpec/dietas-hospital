#!/bin/bash

# Simple health check script for Railway
# Returns 0 if healthy, 1 if not

# Check if PHP is running
if ! pgrep -f "php -S" > /dev/null; then
    echo "❌ PHP server not running"
    exit 1
fi

# Check if we can connect to the application
if curl -f -s -o /dev/null http://localhost:${PORT:-8080}/; then
    echo "✅ Application is healthy"
    exit 0
else
    echo "❌ Application not responding"
    exit 1
fi
