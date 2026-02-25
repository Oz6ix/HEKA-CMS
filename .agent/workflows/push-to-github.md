---
description: Push latest dev changes to GitHub main branch
---
// turbo-all

## Push to GitHub (Dev Branch)

This pushes your local changes to the `main` branch on GitHub (Dev environment).

1. Stage all changes:
```bash
cd "/Users/sittpaing/Desktop/HEKA Vibe coding Antigravity/heka-modern"
git add -A
```

2. Commit with a descriptive message:
```bash
git commit -m "<describe your changes>"
```

3. Push to main (Dev). The remote URL should already be configured with credentials. If authentication fails, reconfigure the remote URL with your PAT:
```bash
git push origin main
```
