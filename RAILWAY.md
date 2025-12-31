# 🚂 Railway Deployment - Quick Start Guide

## One-Click Deploy

[![Deploy on Railway](https://railway.app/button.svg)](https://railway.app/template/)

## 📋 Prerequisites

- GitHub account
- Railway account (free tier available)

## 🚀 Quick Deploy (5 minutes)

### Step 1: Fork/Clone Repository

```bash
git clone https://github.com/RiccijandroUpec/dietas-hospital.git
cd dietas-hospital
```

### Step 2: Create Railway Project

1. Go to [railway.app](https://railway.app)
2. Click "New Project"
3. Select "Deploy from GitHub repo"
4. Choose this repository
5. Railway will auto-detect Laravel

### Step 3: Add PostgreSQL Database

1. In Railway project, click "New"
2. Select "Database" → "PostgreSQL"
3. Railway auto-configures database variables

### Step 4: Configure Environment Variables

Copy these to Railway → Variables:

```env
APP_NAME=Sistema de Dietas
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app
APP_KEY=base64:YAqPhkBk2Q85JXuRYjhEE0jjZbDMUcgXeauV/VgreqE=

DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SAME_SITE=lax

CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_LEVEL=error
```

**Important:** Replace `your-app` in APP_URL with your Railway domain.

### Step 5: Deploy!

Railway will automatically:
- ✅ Install dependencies
- ✅ Build assets
- ✅ Run migrations
- ✅ Create users
- ✅ Optimize cache
- ✅ Start server

## 🎉 That's it!

Visit your Railway URL to access the application.

### Default Users

| Email | Password | Role |
|-------|----------|------|
| admin@hospital.com | 123456 | admin |
| nutricionista@hospital.com | 123456 | nutricionista |
| enfermero@hospital.com | 123456 | enfermero |

**⚠️ Change these passwords after first login!**

## 📚 More Information

- See [DEPLOY.md](DEPLOY.md) for detailed deployment guide
- See [ERROR-419.md](ERROR-419.md) for troubleshooting session errors

## 🛠️ Railway CLI

```bash
# Install
npm i -g @railway/cli

# Login and link
railway login
railway link

# View logs
railway logs

# Run commands
railway run php artisan tinker
```

## 🔄 Updates

Push to your main branch - Railway auto-deploys:

```bash
git push origin main
```

## 💡 Tips

1. **Free Tier**: Railway offers $5/month free credit
2. **Custom Domain**: Add in Railway → Settings → Domains
3. **Monitoring**: Check Railway → Metrics for usage
4. **Backups**: Railway auto-backs up PostgreSQL

## 🆘 Need Help?

- [Railway Docs](https://docs.railway.app/)
- [DEPLOY.md](DEPLOY.md) - Full deployment guide
- [ERROR-419.md](ERROR-419.md) - Session issues

---

Made with ❤️ for Hospital Diet Management System
