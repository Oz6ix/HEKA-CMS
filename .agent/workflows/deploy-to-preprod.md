---
description: Deploy to pre-prod (merge main into pre-prod, push, and deploy to Cloud Run)
---

## Deploy to Pre-Prod (Google Cloud Run)

This merges the latest `main` (Dev) into `pre-prod`, pushes to GitHub, and deploys to Cloud Run.

### Step 1: Ensure all dev changes are committed and pushed
```bash
cd "/Users/sittpaing/Desktop/HEKA Vibe coding Antigravity/heka-modern"
git add -A && git status
```

If there are uncommitted changes, commit them first:
```bash
git commit -m "<describe changes>"
```

### Step 2: Push dev changes to main
// turbo
```bash
git push origin main
```

### Step 3: Merge main into pre-prod
// turbo
```bash
git checkout pre-prod
git merge main --no-edit
```

### Step 4: Push pre-prod branch
// turbo
```bash
git push origin pre-prod
```

### Step 5: Switch back to main (Dev)
// turbo
```bash
git checkout main
```

### Step 6: Deploy to Cloud Run
Tell the user to run this in their Terminal (or run it yourself if they request):
```bash
cd "/Users/sittpaing/Desktop/HEKA Vibe coding Antigravity/heka-modern"
git checkout pre-prod

gcloud run deploy heka-cms \
  --source . \
  --region us-central1 \
  --platform managed \
  --allow-unauthenticated \
  --port 8080 \
  --memory 512Mi \
  --cpu 1 \
  --timeout 300 \
  --min-instances 0 \
  --max-instances 2 \
  --set-env-vars "APP_NAME=HEKA,APP_ENV=production,APP_DEBUG=false,APP_URL=https://heka-cms-1024385989093.us-central1.run.app,DB_CONNECTION=sqlite,SESSION_DRIVER=file,CACHE_STORE=file,LOG_CHANNEL=stderr"

git checkout main
```

### Environment Info
- **Dev URL**: http://127.0.0.1:8000 (local PHP artisan serve)
- **Pre-Prod URL**: https://heka-cms-1024385989093.us-central1.run.app
- **Git Branches**: `main` (Dev) → `pre-prod` (Pre-Production/Cloud Run)
